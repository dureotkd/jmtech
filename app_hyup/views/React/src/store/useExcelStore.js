// src/stores/useExcelStore.js
import { create } from "zustand";
import HyperFormula from "hyperformula";
// import { persist } from "zustand/middleware";

export const useExcelStore = create((set, get) => ({
  hotRefs: {}, // 각 시트 이름별 ref 저장
  activeSheet: undefined, // 현재 활성 시트 이름
  excelData: [], // 업로드된 데이터 (옵션)
  // ✅ HyperFormula 엔진 (전역 1개, 모든 시트가 공유)
  hfInstance: HyperFormula.buildEmpty({}),

  // ✅ 특정 시트의 ref 등록
  registerHotRef: (sheetName, instance) =>
    set((state) => {
      // ✅ 이미 등록된 인스턴스면 중복 저장 안 함
      if (state.hotRefs[sheetName] === instance) return state;
      return { hotRefs: { ...state.hotRefs, [sheetName]: instance } };
    }),

  setHotRefs: (refs) => {
    console.log("🚀 Debug: ~ refs:", refs);
    set({ hotRefs: refs });
  },

  // ✅ 현재 시트 설정
  setActiveSheet: (name) => set({ activeSheet: name }),

  getHotRef: () => {
    return get().hotRefs;
  },

  // * 현재 활성화 시트 가져오기
  getActiveHotRef: () => {
    const { hotRefs, activeSheet } = get();
    console.log("🚀 Debug: ~ hotRefs:", hotRefs);
    return hotRefs[activeSheet];
  },
}));
