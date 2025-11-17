import React from "react";

import "./App.css";

import Loading from "./components/Loading";
import PdfDocument from "./pages/PdfDocument";

function App() {
  const queryString = new URLSearchParams(window.location.search);
  const subType = queryString.get("sub_type") ?? "G"; // * G:견적서 / S:수주서 / B:발주서 / MI:매입 거래명세표 / MC:매출 거래명세표
  const mainType = queryString.get("main_type") ?? ""; // * pdf:PDF 출력용 / 그외 화면 출력용
  const id = queryString.get("id") ?? "";

  if (mainType === "pdf") {
    return (
      <>
        <PdfDocument id={id} subType={subType} />
      </>
    );
  }

  // 매핑 객체
  const components = {
    G: React.lazy(() => import("./pages/CommonDocument")),
    S: React.lazy(() => import("./pages/CommonDocument")),
    B: React.lazy(() => import("./pages/CommonDocument")),
    MI: React.lazy(() => import("./pages/TranscationStatement")),
    MC: React.lazy(() => import("./pages/TranscationStatement")),
  };

  // 컴포넌트 선택
  const SelectedComponent =
    components[subType] ?? (() => <div>잘못된 문서 유형입니다.</div>);

  return (
    <React.Suspense fallback={<Loading />}>
      <SelectedComponent />
    </React.Suspense>
  );
}

export default App;
