import React from "react";

import "./App.css";
import Loading from "./components/Loading";
import EstimateDocument from "./pages/EstimateDocument";

// 🔥 lazy load 적용
const CommonDocument = React.lazy(() => import("./pages/CommonDocument"));
const TranscationStatement = React.lazy(
  () => import("./pages/TranscationStatement"),
);
const PdfDocument = React.lazy(() => import("./pages/PdfDocument"));
const PdfEstimate = React.lazy(() => import("./pages/PdfEstimate"));

function App() {
  const queryString = new URLSearchParams(window.location.search);
  const subType = queryString.get("sub_type") ?? "G";
  const mainType = queryString.get("main_type") ?? "";
  const id = queryString.get("id") ?? "";

  // * -------------------- PDF 모드 --------------------

  if (mainType === "pdf") {
    switch (subType) {
      case "MI":
      case "MC":
        return (
          <React.Suspense fallback={<Loading />}>
            <PdfDocument id={id} subType={subType} />
          </React.Suspense>
        );
      case "G":
      case "S":
      case "B":
        return (
          <React.Suspense fallback={<Loading />}>
            <PdfEstimate id={id} subType={subType} />
          </React.Suspense>
        );
      default:
        return (
          <React.Suspense fallback={<Loading />}>
            <div>잘못된 문서 유형입니다.</div>
          </React.Suspense>
        );
    }
  }

  // 문서 매핑
  const components = {
    G: EstimateDocument,
    S: CommonDocument,
    B: CommonDocument,
    MI: TranscationStatement,
    MC: TranscationStatement,
  };

  const SelectedComponent =
    components[subType] ?? (() => <div>잘못된 문서 유형입니다.</div>);

  return (
    <React.Suspense fallback={<Loading />}>
      <SelectedComponent id={id} subType={subType} />
    </React.Suspense>
  );
}

export default App;
