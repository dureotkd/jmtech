/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,jsx,ts,tsx}", // ✅ 이게 꼭 들어가야 합니다
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
