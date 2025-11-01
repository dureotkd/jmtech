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

export default function SheetSection({ sheets }) {
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
        data={activeSheetOptions.data || []}
        colWidths={activeSheetOptions.colWidths || 100}
        columns={activeSheetOptions.columns || []}
        height={activeSheetOptions.height || "auto"}
        colHeaders={true}
        autoWrapRow={true}
        autoWrapCol={true}
        stretchH="all"
        viewportColumnRenderingOffset={5}
        viewportColumnRenderingThreshold={10}
        // ✅ 여기 추가
        formulas={{
          engine: hfInstance,
          sheetName: activeSheet,
        }}
        licenseKey="non-commercial-and-evaluation"
      />
    </>
  );
}
