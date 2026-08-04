import React from "react";
import { HotTable } from "@handsontable/react-wrapper";
import { registerAllModules } from "handsontable/registry";
import "handsontable/styles/handsontable.css";
import "handsontable/styles/ht-theme-main.css";
import { useExcelStore } from "../store/useExcelStore";
import estimateApi from "../apis/estimateApi";

registerAllModules();

const NormalSheetSection = ({
  sheets,
  vatType,
  setAmount,
  theme = "light",
  subType = "G",
  type = "SELL",
  partnerId = "",
}) => {
  const hotRef = React.useRef(null);
  const amountUpdateTimeoutRef = React.useRef(null);
  const suggestionCacheRef = React.useRef(new Map());
  const suggestionLookupRef = React.useRef(new Map());
  const storedActiveSheet = useExcelStore((state) => state.activeSheet);
  const setActiveSheet = useExcelStore((state) => state.setActiveSheet);
  const registerHotRef = useExcelStore((state) => state.registerHotRef);
  const activeSheet = storedActiveSheet ?? sheets[0]?.name;

  // 템플릿이 비동기로 바뀌면 현재 문서의 첫 시트를 활성화합니다.
  React.useEffect(() => {
    const hasActiveSheet = sheets.some((sheet) => sheet.name === activeSheet);
    if (!hasActiveSheet && sheets.length > 0) {
      setActiveSheet(sheets[0].name);
    }
  }, [activeSheet, setActiveSheet, sheets]);

  const activeSheetOptions = React.useMemo(() => {
    return sheets.find((sheet) => sheet.name === activeSheet) || {};
  }, [activeSheet, sheets]);

  const suggestionContext = `${type}|${subType}|${partnerId || ""}`;

  const itemAutocompleteSource = React.useCallback(
    async (query, process) => {
      const keyword = String(query || "").trim();

      if (!keyword) {
        process([]);
        return;
      }

      const cacheKey = `${suggestionContext}|${keyword.toLocaleLowerCase()}`;
      const cached = suggestionCacheRef.current.get(cacheKey);

      if (cached) {
        process(cached.map((suggestion) => suggestion.item));
        return;
      }

      try {
        const res = await estimateApi.품목자동완성({
          query: keyword,
          type,
          sub_type: subType,
          partner_id: partnerId || "",
        });
        const suggestions = res?.ok && Array.isArray(res?.data) ? res.data : [];

        suggestionCacheRef.current.set(cacheKey, suggestions);
        suggestions.forEach((suggestion) => {
          const itemKey = String(suggestion.item || "")
            .trim()
            .toLocaleLowerCase();
          if (itemKey) {
            suggestionLookupRef.current.set(
              `${suggestionContext}|${itemKey}`,
              suggestion,
            );
          }
        });

        process(suggestions.map((suggestion) => suggestion.item));
      } catch (error) {
        console.error("품목 자동완성 조회 중 오류:", error);
        process([]);
      }
    },
    [partnerId, subType, suggestionContext, type],
  );

  const columns = React.useMemo(() => {
    const originalColumns = activeSheetOptions.columns || [];

    if (subType !== "S" && subType !== "B") {
      return originalColumns;
    }

    return originalColumns.map((column, index) =>
      index === 0
        ? {
            ...column,
            type: "autocomplete",
            source: itemAutocompleteSource,
            strict: false,
            allowInvalid: true,
          }
        : column,
    );
  }, [activeSheetOptions.columns, itemAutocompleteSource, subType]);

  const updateOrderAmount = React.useCallback(() => {
    if (amountUpdateTimeoutRef.current) {
      clearTimeout(amountUpdateTimeoutRef.current);
    }

    amountUpdateTimeoutRef.current = setTimeout(() => {
      const instance = hotRef.current?.hotInstance;
      if (!instance || instance.isDestroyed) {
        return;
      }

      const sumAmount = instance.getData().reduce((acc, row) => {
        const supplyValue = parseFloat(row[4]) || 0;
        const taxValue = parseFloat(row[5]) || 0;
        return acc + supplyValue + taxValue;
      }, 0);

      setAmount(sumAmount);
    }, 50);
  }, [setAmount]);

  React.useEffect(() => {
    return () => {
      if (amountUpdateTimeoutRef.current) {
        clearTimeout(amountUpdateTimeoutRef.current);
      }
    };
  }, []);

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
        columns={columns}
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
        afterChange={function (changes, source) {
          switch (subType) {
            case "S": // 수주서
            case "B": // 발주서
              if (
                changes &&
                source !== "loadData" &&
                source !== "autoCalc" &&
                source !== "itemAutocomplete"
              ) {
                changes.forEach(([row, prop, oldValue, newValue]) => {
                  if (prop === 0) {
                    const itemValue =
                      typeof newValue === "object" && newValue
                        ? newValue.title || newValue.value || ""
                        : String(newValue || "").trim();
                    const suggestionKey = `${suggestionContext}|${itemValue.toLocaleLowerCase()}`;
                    const suggestion =
                      suggestionLookupRef.current.get(suggestionKey);

                    if (suggestion) {
                      const quantity =
                        parseFloat(this.getDataAtCell(row, 2)) || 0;
                      const unitPrice =
                        parseFloat(suggestion.unit_price) || 0;
                      const total = quantity * unitPrice;
                      let supply = total;
                      let tax = 0;

                      if (vatType === "Y") {
                        tax = Math.round(total - total / 1.1);
                        supply = total - tax;
                      } else if (vatType === "N") {
                        tax = Math.round(supply * 0.1);
                      }

                      this.setDataAtCell(
                        row,
                        0,
                        suggestion.item,
                        "itemAutocomplete",
                      );
                      this.setDataAtCell(
                        row,
                        1,
                        suggestion.spec || "",
                        "itemAutocomplete",
                      );
                      this.setDataAtCell(
                        row,
                        3,
                        suggestion.unit_price,
                        "itemAutocomplete",
                      );
                      this.setDataAtCell(
                        row,
                        4,
                        supply || "",
                        "autoCalc",
                      );
                      this.setDataAtCell(
                        row,
                        5,
                        tax || "",
                        "autoCalc",
                      );
                      updateOrderAmount();
                    }
                  }

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
                    updateOrderAmount();
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
