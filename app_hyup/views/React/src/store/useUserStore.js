// src/stores/useUserStore.js
import { create } from "zustand";
import { persist } from "zustand/middleware";

const useUserStore = create(
  persist(
    (set) => ({
      // 🔹 상태
      user: null, // 사용자 정보
      isLoggedIn: false, // 로그인 여부

      // 🔹 액션
      login: (userData, token) =>
        set({
          user: userData,
          token: token,
          isLoggedIn: true,
        }),

      logout: () =>
        set({
          user: null,
          token: null,
          isLoggedIn: false,
        }),
    }),

    {
      name: "auth-storage", // localStorage 키 이름
      getStorage: () => localStorage, // 기본적으로 localStorage 사용
    }
  )
);

export default useUserStore;
