import React from "react";
import { HotTable } from "@handsontable/react-wrapper";
import { registerAllModules } from "handsontable/registry";
import "handsontable/styles/handsontable.css";
import "handsontable/styles/ht-theme-main.css";
import { useExcelStore } from "../store/useExcelStore";
import { registerCellType, TextCellType } from "handsontable/cellTypes";

registerAllModules();

registerCellType("formula", {
  editor: TextCellType.editor,
  renderer: TextCellType.renderer,
  validator: TextCellType.validator,
});

const SheetSection = ({
  sheetName,
  sheet,
  vatType,
  setAmount,
  theme = "light",
  subType = "G",
}) => {
  const hotRef = React.useRef(null);
  const { activeSheet, setActiveSheet, registerHotRef, hfInstance, hotRefs } =
    useExcelStore((state) => state);

  // 디바운싱을 위한 ref
  const amountUpdateTimeoutRef = React.useRef(null);

  // cleanup: 컴포넌트 언마운트 시 timeout 정리
  React.useEffect(() => {
    return () => {
      if (amountUpdateTimeoutRef.current) {
        clearTimeout(amountUpdateTimeoutRef.current);
      }
    };
  }, []);

  // 현재 시트가 활성화되어 있는지 확인
  const isActive = activeSheet === sheetName;

  // nestedHeaders 배열을 Handsontable 형식으로 변환 (메모이제이션)
  const nestedHeadersArray = React.useMemo(
    () =>
      sheet?.nestedHeaders && sheet.nestedHeaders.length > 0
        ? sheet.nestedHeaders.map((row) =>
            row.map((header) => {
              // 빈 문자열이면 그대로 반환
              if (header === "") {
                return "";
              }
              // 문자열이면 그대로 반환
              if (typeof header === "string") {
                return header;
              }
              // 객체면 label과 colspan으로 변환
              return {
                label: header.label,
                colspan: header.colspan || 1,
              };
            })
          )
        : undefined,
    [sheet?.nestedHeaders]
  );

  return (
    <>
      <div style={{ display: isActive ? "block" : "none" }}>
        <HotTable
          ref={hotRef}
          themeName="ht-theme-main"
          className={`hot-table-theme-${theme}`}
          columns={sheet?.columns || []}
          data={sheet?.data || []}
          colWidths={sheet?.colWidths || 100}
          height={sheet?.height || "auto"}
          stretchH="all"
          rowHeaders={true}
          nestedHeaders={nestedHeadersArray}
          viewportColumnRenderingOffset={5}
          viewportColumnRenderingThreshold={10}
          afterRender={() => {
            // 인스턴스가 렌더링된 후 등록
            const instance = hotRef.current?.hotInstance;
            if (instance && !instance.isDestroyed && sheetName) {
              registerHotRef(sheetName, instance);
            }
          }}
          beforeChange={function (changes, source) {}}
          afterCreateRow={(row, amount) => {
            // 새 행이 추가될 때 비중 컬럼에 수식 설정
            if (sheetName === "내역서") {
              // const 비중ColIndex = 12;
              // const rowNum = row + 5;
              // const 도번Col = "B";
              // const 재질Col = "C";
              // const formula = `=IF(${도번Col}${rowNum}="","",IF(${재질Col}${rowNum}="SUS",7.93,IF(${재질Col}${rowNum}="AL",2.8,7.85)))`;
              // console.log("🚀 Debug: ~ SheetSection ~ formula:", formula);
              // this.setDataAtCell(row, 비중ColIndex, formula);
            }
          }}
          afterChange={function (changes, source) {
            switch (subType) {
              case "G": // 견적서
                // * 금액의 합을 setAmount에 넣음
                // 기존 timeout 클리어
                if (amountUpdateTimeoutRef.current) {
                  clearTimeout(amountUpdateTimeoutRef.current);
                }

                amountUpdateTimeoutRef.current = setTimeout(() => {
                  const 견적서Instance = hotRefs["견적서"];
                  if (!견적서Instance || 견적서Instance.isDestroyed) {
                    return;
                  }

                  try {
                    const hotData = 견적서Instance.getData();
                    const sumAmount = hotData.reduce((acc, cur) => {
                      const amountValue = parseFloat(cur[5]) || 0;
                      return acc + amountValue;
                    }, 0);
                    setAmount((prev) => {
                      return sumAmount;
                    });
                  } catch (error) {
                    console.error("금액 계산 중 오류:", error);
                  }
                }, 300);

                break;
              case "S": // 수주서
              case "B": // 발주서
                if (source === "edit" && changes && sheetName === "견적서") {
                  // * 0번쨰 품목 수정시
                  if (changes[0][3]?.key) {
                    changes.forEach(([row, prop, oldValue, newValue]) => {
                      if (prop === 0 && oldValue !== newValue.title) {
                        this.setDataAtCell(row, 0, newValue.title); // * 품목
                      }
                    });
                  }

                  changes.forEach(([row, prop, oldValue, newValue]) => {
                    if (prop === 2 || prop === 3) {
                      let targetValue = "";

                      // * 수량
                      if (prop === 2) {
                        targetValue =
                          parseFloat(this.getDataAtCell(row, 3)) || 0; // * 단가
                      }

                      // * 단가
                      if (prop === 3) {
                        targetValue =
                          parseFloat(this.getDataAtCell(row, 2)) || 0; // * 수량
                      }

                      let supply = 0;
                      let tax = 0;
                      const total = newValue * targetValue; // 총금액(부가세 포함)

                      switch (vatType) {
                        case "Y": // 부가세 포함
                          tax = Math.round(total - total / 1.1);
                          supply = total - tax;
                          break;

                        case "N": // 부가세 별도
                          supply = total;
                          tax = Math.round(supply * 0.1);
                          break;

                        case "X": // 면세
                        default:
                          supply = total;
                          tax = 0;
                          break;
                      }

                      this.setDataAtCell(row, 4, supply, "autoCalc"); // 공급가액(E)
                      this.setDataAtCell(row, 5, tax, "autoCalc"); // 세액(F)

                      // 기존 timeout 클리어
                      if (amountUpdateTimeoutRef.current) {
                        clearTimeout(amountUpdateTimeoutRef.current);
                      }

                      amountUpdateTimeoutRef.current = setTimeout(() => {
                        const instance = hotRef.current?.hotInstance;
                        if (!instance || instance.isDestroyed) {
                          return;
                        }

                        try {
                          const hotData = instance.getData();
                          const sumAmount = hotData.reduce((acc, cur) => {
                            const supplyValue = parseFloat(cur[4]) || 0;
                            const taxValue = parseFloat(cur[5]) || 0;
                            return acc + supplyValue + taxValue;
                          }, 0);

                          setAmount((prev) => {
                            return sumAmount;
                          });
                        } catch (error) {
                          console.error("금액 계산 중 오류:", error);
                        }
                      }, 300);
                    }
                  });
                }

                break;

              case "MI": // 매입 거래명세표
              case "MC": // 매출 거래명세표
                if (source === "edit" && changes) {
                  if (changes[0][3]?.key) {
                    changes.forEach(([row, prop, oldValue, newValue]) => {
                      if (prop === 1 && oldValue !== newValue.title) {
                        this.setDataAtCell(row, 1, newValue.title); // * 품목
                      }
                    });
                  }

                  changes.forEach(([row, prop, oldValue, newValue]) => {
                    if (prop === 3 || prop === 4) {
                      let targetValue = "";

                      // * 수량
                      if (prop === 3) {
                        targetValue =
                          parseFloat(this.getDataAtCell(row, 4)) || 0; // * 단가
                      }

                      // * 단가
                      if (prop === 4) {
                        targetValue =
                          parseFloat(this.getDataAtCell(row, 3)) || 0; // * 수량
                      }

                      let supply = 0;
                      let tax = 0;
                      const total = newValue * targetValue; // 총금액(부가세 포함)

                      switch (vatType) {
                        case "Y": // 부가세 포함
                          tax = Math.round(total - total / 1.1);
                          supply = total - tax;
                          break;

                        case "N": // 부가세 별도
                          supply = total;
                          tax = Math.round(supply * 0.1);
                          break;

                        case "X": // 면세
                        default:
                          supply = total;
                          tax = 0;
                          break;
                      }

                      this.setDataAtCell(row, 5, supply, "autoCalc"); // 공급가액(E)
                      this.setDataAtCell(row, 6, tax, "autoCalc"); // 세액(F)

                      // 기존 timeout 클리어
                      if (amountUpdateTimeoutRef.current) {
                        clearTimeout(amountUpdateTimeoutRef.current);
                      }

                      amountUpdateTimeoutRef.current = setTimeout(() => {
                        const instance = hotRef.current?.hotInstance;
                        if (!instance || instance.isDestroyed) {
                          return;
                        }

                        try {
                          const hotData = instance.getData();
                          const sumAmount = hotData.reduce((acc, cur) => {
                            const supplyValue = parseFloat(cur[5]) || 0;
                            const taxValue = parseFloat(cur[6]) || 0;
                            return acc + supplyValue + taxValue;
                          }, 0);

                          setAmount((prev) => {
                            return sumAmount;
                          });
                        } catch (error) {
                          console.error("금액 계산 중 오류:", error);
                        }
                      }, 300);
                    }
                  });
                }
                break;
            }
          }}
          // ✅ 여기 추가
          formulas={{
            engine: hfInstance,
            sheetName: sheetName,
          }}
          licenseKey="non-commercial-and-evaluation"
        />
      </div>
    </>
  );
};

// React.memo 비교 함수 - sheet.data가 실제로 변경되었을 때만 재렌더링
export default React.memo(SheetSection, (prevProps, nextProps) => {
  // sheetName, vatType, subType이 변경되면 재렌더링
  if (
    prevProps.sheetName !== nextProps.sheetName ||
    prevProps.vatType !== nextProps.vatType ||
    prevProps.subType !== nextProps.subType
  ) {
    return false;
  }

  // sheet 객체의 참조가 같으면 재렌더링 안 함
  if (prevProps.sheet === nextProps.sheet) {
    return true;
  }

  // sheet의 주요 속성들이 실제로 변경되었는지 확인
  const prevSheet = prevProps.sheet;
  const nextSheet = nextProps.sheet;

  if (!prevSheet || !nextSheet) {
    return false;
  }

  // name, columns, colWidths, height가 변경되면 재렌더링
  if (
    prevSheet.name !== nextSheet.name ||
    JSON.stringify(prevSheet.columns) !== JSON.stringify(nextSheet.columns) ||
    JSON.stringify(prevSheet.colWidths) !==
      JSON.stringify(nextSheet.colWidths) ||
    prevSheet.height !== nextSheet.height
  ) {
    return false;
  }

  // data 길이가 같고 내용이 같으면 재렌더링 안 함 (깊은 비교는 비용이 크므로 길이만 확인)
  // 실제 데이터 변경은 Handsontable이 내부적으로 처리
  if (
    prevSheet.data?.length === nextSheet.data?.length &&
    JSON.stringify(prevSheet.data) === JSON.stringify(nextSheet.data)
  ) {
    return true;
  }

  return false;
});
