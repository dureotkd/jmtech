import React from "react";
import { HotTable } from "@handsontable/react-wrapper";
import { registerAllModules } from "handsontable/registry";
import "handsontable/styles/handsontable.css";
import "handsontable/styles/ht-theme-main.css";
import HyperFormula from "hyperformula";
import { useExcelStore } from "../store/useExcelStore";
import { registerCellType, TextCellType } from "handsontable/cellTypes";

registerAllModules();

const NormalSheetSection = ({
  sheets,
  vatType,
  setAmount,
  theme = "light",
  subType = "G",
}) => {
  const hotRef = React.useRef(null);
  const {
    activeSheet = sheets[0]?.name,
    setActiveSheet,
    registerHotRef,
  } = useExcelStore((state) => state);

  // 초기 마운트 시 activeSheet가 없으면 첫 번째 시트로 설정
  React.useEffect(() => {
    if (!activeSheet && sheets.length > 0) {
      setActiveSheet(sheets[0].name);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []); // 초기 마운트 시에만 실행

  const activeSheetOptions = React.useMemo(() => {
    return sheets.find((sheet) => sheet.name === activeSheet) || {};
  }, [activeSheet, sheets]);

  const showSheet = (sheetName) => {
    setActiveSheet(sheetName);
  };

  const themeStyles = {
    light: {
      base: "bg-gray-100 hover:bg-gray-200 text-gray-800",
      active: "bg-white text-black border-gray-400",
    },
    blue: {
      base: "bg-gray-100 hover:bg-gray-200 text-gray-800",
      active: "bg-white text-black border-gray-400",
    },
    red: {
      base: "bg-gray-100 hover:bg-gray-200 text-gray-800",
      active: "bg-white text-black border-gray-400",
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
        key={activeSheet} // activeSheet가 변경되면 완전히 재생성
        ref={hotRef}
        themeName="ht-theme-main"
        className={`hot-table-theme-${theme}`}
        columns={activeSheetOptions.columns || []}
        data={activeSheetOptions.data || []}
        colWidths={activeSheetOptions.colWidths || 100}
        height={activeSheetOptions.height || "auto"}
        stretchH="all"
        rowHeaders={true}
        colHeaders={true}
        viewportColumnRenderingOffset={5}
        viewportColumnRenderingThreshold={10}
        afterRender={() => {
          // 인스턴스가 렌더링된 후 등록
          const instance = hotRef.current?.hotInstance;
          if (instance && !instance.isDestroyed && activeSheet) {
            registerHotRef(activeSheet, instance);
          }
        }}
        beforeChange={function (changes, source) {}}
        afterChange={function (changes, source) {
          switch (subType) {
            case "S": // 수주서
            case "B": // 발주서
              if (source === "edit" && changes) {
                // * 0번쨰 품목 수정시
                if (changes[0][3]?.key) {
                  changes.forEach(([row, prop, oldValue, newValue]) => {
                    if (prop === 0 && oldValue.value !== newValue.value) {
                      this.setDataAtCell(
                        row,
                        0,
                        newValue.title ? newValue.title : newValue.value
                      ); // * 품목
                    }
                  });
                }

                changes.forEach(([row, prop, oldValue, newValue]) => {
                  if (prop === 2 || prop === 3) {
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
                      // 공급가액과 세액 각각 합산
                      const sumSupplyAmount = hotData.reduce((acc, cur) => {
                        const supplyValue = parseFloat(cur[5]) || 0;
                        return acc + supplyValue;
                      }, 0);
                      const sumTaxAmount = hotData.reduce((acc, cur) => {
                        const taxValue = parseFloat(cur[6]) || 0;
                        return acc + taxValue;
                      }, 0);

                      setAmount((prev) => {
                        return {
                          supply: sumSupplyAmount,
                          tax: sumTaxAmount,
                        };
                      });
                      // setAmount((prev) => {
                      //   return sumAmount;
                      // });
                    }, 500);
                  }
                });
              }
              break;
          }
        }}
        licenseKey="non-commercial-and-evaluation"
      />
    </>
  );
};

export default React.memo(NormalSheetSection);
