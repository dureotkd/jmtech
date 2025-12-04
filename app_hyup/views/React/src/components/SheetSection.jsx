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
  const { activeSheet, setActiveSheet, registerHotRef, hfInstance } =
    useExcelStore((state) => state);

  // 현재 시트가 활성화되어 있는지 확인
  const isActive = activeSheet === sheetName;

  // nestedHeaders 배열을 Handsontable 형식으로 변환
  const nestedHeadersArray =
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
      : undefined;

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
              // case "G": // 일반
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

                      let amount = 0;

                      setTimeout(() => {
                        const hotData = hotRef.current.hotInstance.getData();
                        const sumAmount = hotData.reduce((acc, cur) => {
                          const supplyValue = parseFloat(cur[4]) || 0;
                          const taxValue = parseFloat(cur[5]) || 0;
                          return acc + supplyValue + taxValue;
                        }, 0);

                        setAmount((prev) => {
                          return sumAmount;
                        });
                      }, 500);
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

                      let amount = 0;

                      setTimeout(() => {
                        const hotData = hotRef.current.hotInstance.getData();
                        const sumAmount = hotData.reduce((acc, cur) => {
                          const supplyValue = parseFloat(cur[5]) || 0;
                          const taxValue = parseFloat(cur[6]) || 0;
                          return acc + supplyValue + taxValue;
                        }, 0);

                        setAmount((prev) => {
                          return sumAmount;
                        });
                      }, 500);
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

export default React.memo(SheetSection);
