import React from "react";
import { HotTable } from "@handsontable/react-wrapper";
import { registerAllModules } from "handsontable/registry";
import "handsontable/styles/handsontable.css";
import "handsontable/styles/ht-theme-main.css";
import HyperFormula from "hyperformula";
import { useExcelStore } from "../store/useExcelStore";
import { registerCellType, TextCellType } from "handsontable/cellTypes";
import Loading from "./Loading";

registerAllModules();

registerCellType("formula", {
  editor: TextCellType.editor,
  renderer: TextCellType.renderer,
  validator: TextCellType.validator,
});

const SheetSection = ({ sheets, vatType, setAmount }) => {
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

  return (
    <>
      <div className="sheet-tabs flex border-b border-gray-300 bg-gray-100">
        {sheets.map((sheet) => (
          <button
            key={sheet.name}
            onClick={() => showSheet(sheet.name)}
            type="button"
            className={`px-4 py-2 text-sm font-medium border-r border-gray-300 
              transition-colors focus:outline-none ${
                activeSheet === sheet.name
                  ? "bg-white text-black"
                  : "bg-gray-100 hover:bg-gray-200"
              }`}
          >
            {sheet.name}
          </button>
        ))}
      </div>

      <HotTable
        ref={hotRef}
        themeName="ht-theme-main"
        columns={columnsWithHeader}
        data={activeSheetOptions.data || []}
        colWidths={activeSheetOptions.colWidths || 100}
        height={activeSheetOptions.height || "auto"}
        stretchH="all"
        rowHeaders={true}
        colHeaders={true}
        viewportColumnRenderingOffset={5}
        viewportColumnRenderingThreshold={10}
        beforeChange={function (changes, source) {
          console.log("beforeChange");
        }}
        afterChange={function (changes, source) {
          if (source === "edit" && changes) {
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
                  console.log("🚀 Debug: ~ SheetSection ~ hotData:", hotData);
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
