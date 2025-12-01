const ESTIMATE_SUB_TYPE = {
  G: "견적서",
  S: "수주서",
  B: "발주서",
  MI: "매입 거래명세표",
  MC: "매출 거래명세표",
};

const ESTIMATE_TYPE = {
  SELL: "판매",
  BUY: "구매",
};

const VAT_TYPE = {
  Y: "부가세 포함",
  N: "부가세 미포함",
  X: "부가세 없음",
};

// * localhost일 경우 "development", production일 경우 "production"
const ENVIRONMENT =
  (typeof window !== "undefined" &&
    (window.location.hostname === "localhost" ||
      window.location.hostname === "127.0.0.1")) ||
  window.location.hostname.includes("jmtech.test")
    ? "development"
    : "production";

export { ESTIMATE_SUB_TYPE, ESTIMATE_TYPE, ENVIRONMENT, VAT_TYPE };
