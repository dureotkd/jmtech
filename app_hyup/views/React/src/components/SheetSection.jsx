import React from "react";
import { HotTable } from "@handsontable/react-wrapper";
import { registerAllModules } from "handsontable/registry";
import "handsontable/styles/handsontable.css";
import "handsontable/styles/ht-theme-main.css";
import HyperFormula from "hyperformula";
import { useExcelStore } from "../store/useExcelStore";
import { registerCellType, TextCellType } from "handsontable/cellTypes";

registerAllModules();

registerCellType("formula", {
  editor: TextCellType.editor,
  renderer: TextCellType.renderer,
  validator: TextCellType.validator,
});

const SheetSection = ({
  sheets,
  vatType,
  setAmount,
  theme = "light",
  subType = "G",
}) => {
  console.log("Render SheetSection");
  const hotRef = React.useRef(null);
  const {
    activeSheet = sheets[0]?.name,
    setActiveSheet,
    registerHotRef,
    getHotRef,
  } = useExcelStore((state) => state);

  // ✅ HyperFormula 엔진 생성 (전역 1개)
  const hfInstance = React.useMemo(() => {
    return HyperFormula.buildEmpty({});
  }, []);

  // 초기 activeSheet 설정
  React.useEffect(() => {
    if (!activeSheet && sheets.length > 0) {
      setActiveSheet(sheets[0].name);
    }
  }, [activeSheet, sheets, setActiveSheet]);

  const activeSheetOptions = React.useMemo(() => {
    return sheets.find((sheet) => sheet.name === activeSheet) || {};
  }, [activeSheet, sheets]);

  React.useEffect(() => {
    const instance = hotRef.current?.hotInstance;
    if (instance && !instance.isDestroyed) {
      registerHotRef(activeSheet, instance);
    }
  }, [activeSheet, registerHotRef]);

  const showSheet = (sheetName) => {
    setActiveSheet(sheetName);
  };

  const columnsWithHeader = (activeSheetOptions.columns || []).map(
    (col, index) => {
      const alphabet = String.fromCharCode(65 + index);
      return {
        ...col,
        title: `${col.title || ""} ${alphabet}`,
      };
    }
  );

  // nestedHeaders 배열을 Handsontable 형식으로 변환
  // nestedHeaders는 이미 2차원 배열 형태로 전달됨
  const nestedHeadersArray =
    activeSheetOptions.nestedHeaders &&
    activeSheetOptions.nestedHeaders.length > 0
      ? activeSheetOptions.nestedHeaders.map((row) =>
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

  const themeStyles = {
    light: {
      base: "bg-gray-100 hover:bg-gray-200 text-gray-800",
      active: "bg-white text-black border-gray-400",
    },
    blue: {
      base: "bg-gray-100 hover:bg-gray-200 text-gray-800",
      active: "bg-white text-black border-gray-400",
      // base: "bg-blue-50 hover:bg-blue-100 text-blue-800",
      // active: "bg-white text-blue-600 border-blue-500",
    },
    red: {
      base: "bg-gray-100 hover:bg-gray-200 text-gray-800",
      active: "bg-white text-black border-gray-400",
      // base: "bg-red-50 hover:bg-red-100 text-red-800",
      // active: "bg-white text-red-600 border-red-500",
    },
  };

  return (
    <>
      <div className="sheet-tabs flex border-b border-gray-300 bg-gray-100">
        {sheets.map((sheet) => {
          const currentTheme = themeStyles[theme] ?? themeStyles.light;
          return (
            <button
              key={sheet.name}
              onClick={() => showSheet(sheet.name)}
              type="button"
              className={`px-4 py-2 text-sm font-medium border-r border-gray-300 transition-colors ${
                activeSheet === sheet.name
                  ? currentTheme.active
                  : currentTheme.base
              }`}
            >
              {sheet.name}
            </button>
          );
        })}
      </div>
      <HotTable
        ref={hotRef}
        themeName="ht-theme-main"
        className={`hot-table-theme-${theme}`}
        columns={columnsWithHeader}
        data={activeSheetOptions.data || []}
        colWidths={activeSheetOptions.colWidths || 100}
        height={activeSheetOptions.height || "auto"}
        stretchH="all"
        rowHeaders={true}
        colHeaders={true}
        nestedHeaders={nestedHeadersArray}
        viewportColumnRenderingOffset={5}
        viewportColumnRenderingThreshold={10}
        afterRender={() => {
          // 재료비와 가공비 헤더를 bold 처리
          if (nestedHeadersArray && nestedHeadersArray.length > 0) {
            const hotInstance = hotRef.current?.hotInstance;
            if (!hotInstance) return;

            // 헤더 테이블 찾기
            const headerTable = hotInstance.rootElement?.querySelector(
              ".ht_clone_top thead"
            );
            if (!headerTable) return;

            const firstRow = headerTable.querySelector("tr:first-child");
            if (!firstRow) return;

            // 첫 번째 행의 모든 셀을 확인하여 재료비와 가공비 찾기
            const cells = firstRow.querySelectorAll("th");
            cells.forEach((cell) => {
              const text = cell.textContent?.trim();
              if (text === "재료비" || text === "가공비") {
                cell.style.fontWeight = "bold";
              }
            });
          }
        }}
        beforeChange={function (changes, source) {
          console.log("beforeChange");
        }}
        afterCreateRow={(row, amount) => {
          // 새 행이 추가될 때 비중 컬럼에 수식 설정
          if (activeSheet === "내역서") {
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
            case "G": // 일반
            case "S": // 수주서
            case "B": // 발주서
              if (source === "edit" && changes) {
                // * 0번쨰 품목 수정시
                if (changes[0][3]?.key) {
                  changes.forEach(([row, prop, oldValue, newValue]) => {
                    console.log(oldValue, newValue);
                    if (prop === 0 && oldValue !== newValue.title) {
                      this.setDataAtCell(row, 0, newValue.title); // * 품목
                    }
                  });
                }

                changes.forEach(([row, prop, oldValue, newValue]) => {
                  if (prop === 2 || prop === 3) {
                    console.log(row, prop, newValue, vatType);

                    let targetValue = "";

                    // * 수량
                    if (prop === 2) {
                      targetValue = parseFloat(this.getDataAtCell(row, 3)) || 0; // * 단가
                    }

                    // * 단가
                    if (prop === 3) {
                      targetValue = parseFloat(this.getDataAtCell(row, 2)) || 0; // * 수량
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
                        console.log("🚀 Debug: ~ SheetSection ~ tax:", tax);
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
                      console.log(
                        "🚀 Debug: ~ SheetSection ~ hotData:",
                        hotData
                      );
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
                    console.log(row, prop, newValue, vatType);

                    let targetValue = "";

                    // * 수량
                    if (prop === 3) {
                      targetValue = parseFloat(this.getDataAtCell(row, 4)) || 0; // * 단가
                    }

                    // * 단가
                    if (prop === 4) {
                      targetValue = parseFloat(this.getDataAtCell(row, 3)) || 0; // * 수량
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
                        console.log("🚀 Debug: ~ SheetSection ~ tax:", tax);
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
                      console.log(
                        "🚀 Debug: ~ SheetSection ~ hotData:",
                        hotData
                      );
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
          sheetName: activeSheet,
        }}
        licenseKey="non-commercial-and-evaluation"
      />
    </>
  );
};

export default React.memo(SheetSection);
