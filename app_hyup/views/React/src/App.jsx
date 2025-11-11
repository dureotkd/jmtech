import React from "react";

import "./App.css";

import CommonDocument from "./pages/CommonDocument";
import TranscationStatement from "./pages/TranscationStatement";

function App() {
  const queryString = new URLSearchParams(window.location.search);
  const subType = queryString.get("sub_type") ?? "G"; // * G:견적서 / S:수주서 / B:발주서 / MI:매입 거래명세표 / MC:매출 거래명세표

  // 매핑 객체
  const components = {
    G: CommonDocument, // * 견적서
    S: CommonDocument, // * 수주서
    B: CommonDocument, // * 발주서
    MI: TranscationStatement, // * 매입 거래명세표
    MC: TranscationStatement, // * 매출 거래명세표
  };

  // 컴포넌트 선택
  const SelectedComponent =
    components[subType] ?? (() => <div>잘못된 문서 유형입니다.</div>);

  return <SelectedComponent />;
}

export default App;
