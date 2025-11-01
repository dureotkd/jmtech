import React from "react";

import "./App.css";

import Loading from "./components/Loading";
import SheetSection from "./components/SheetSection";
import ExcelImportModal from "./components/ExcelImportModal";

import estimateApi from "./apis/estimateApi";
import SimpleAutocomplete from "./components/SimpleAutoComplete";
import { useExcelStore } from "./store/useExcelStore";
import { wait } from "./utils/util";

export default function App() {
  const { getActiveHotRef, setActiveSheet } = useExcelStore((state) => state);
  const [loading, setLoading] = React.useState(false);
  const [form, setForm] = React.useState({
    partnerId: "",
    estimateDate: "",
    phoneNumber: "",
    faxNumber: "",
    title: "",
  });
  const [files, setFiles] = React.useState([]);
  const fileInputRef = React.useRef(null);

  const [partners, setPartners] = React.useState([]);
  const [sheets, setSheets] = React.useState([
    // * 모의 데이터 (템플릿은 PHP 서버에서 가져옴)
    {
      name: "견적서",
      data: new Array(20).fill(["='내역서'!D2"]),
      columns: [
        { title: "품목", type: "dropdown", source: [] },
        { title: "규격" },
        { title: "수량" },
        { title: "단가" },
        { title: "공급가액" },
        { title: "세액" },
        { title: "비고" },
      ],
      colWidths: [278, 100, 80, 100, 120, 100, 150],
      height: "auto",
    },
    {
      name: "내역서",
      data: new Array(20).fill(["='견적서'!D2"]),
      columns: [
        { title: "품목" },
        { title: "규격" },
        { title: "수량" },
        { title: "단가" },
        { title: "공급가액" },
        { title: "세액" },
        { title: "비고" },
      ],
      colWidths: [278, 100, 80, 100, 120, 100, 150],
      height: 400,
    },
  ]);

  const loadExcelTemplate = async () => {
    const res = await estimateApi.초기엑셀템플릿();
    setSheets(res);
  };

  const loadPartnerList = async () => {
    const res = await estimateApi.거래처목록();
    setPartners(res);
  };

  const registerSheetEvents = async () => {
    if (!sheets.length) return;

    // 순차 실행: 각 시트를 잠깐씩 활성화
    let index = 0;

    const interval = setInterval(() => {
      const current = sheets[index];
      if (current) {
        setActiveSheet(current.name);
        console.log(`🔹 Activated sheet: ${current.name}`);
      }

      index++;

      // 모든 시트를 순회한 후 0번째 시트로 복귀
      if (index >= sheets.length) {
        setTimeout(() => {
          setActiveSheet(sheets[0].name);
          console.log(`✅ Returned to first sheet: ${sheets[0].name}`);
        }, 200);
        clearInterval(interval);
      }
    }, 200); // 시트간 딜레이 (ms 단위)

    await wait(sheets.length * 250);

    return () => clearInterval(interval);
  };

  React.useEffect(() => {
    (async () => {
      try {
        setLoading(true);

        // * 엑셀 템플릿 로드
        await loadExcelTemplate();

        // * 거래처 목록 로드
        await loadPartnerList();

        // * 시트 이벤트 등록 (한바퀴 돌아야 Formula 적용 가능)
        await registerSheetEvents();
      } catch (error) {
        alert("엑셀 템플릿 로드 중 오류가 발생했습니다.");
      } finally {
        setLoading(false);
      }
    })();
  }, []);

  const handleFormSubmit = (e) => {
    e.preventDefault();
    console.log("폼 제출:", form);
  };

  const phoneNumberMask = (e) => {
    const value = e.target.value.replace(/\D/g, "");
    const masked = value
      .replace(/^(\d{2,3})(\d{3,4})(\d{4})$/, "$1-$2-$3")
      .slice(0, 13);
    setForm({ ...form, [e.target.name]: masked });
  };

  // 📎 파일 첨부 버튼 클릭 → input 클릭 트리거
  const handleAttachClick = () => {
    fileInputRef.current?.click();
  };

  // 📂 파일 선택 시 이벤트
  const handleFileChange = (e) => {
    const newFiles = Array.from(e.target.files);
    setFiles((prev) => {
      const merged = [...prev];
      newFiles.forEach((file) => {
        const isDuplicate = merged.some(
          (f) => f.name === file.name && f.size === file.size
        );
        if (!isDuplicate) merged.push(file);
      });
      return merged;
    });

    // 같은 파일 다시 선택 가능하도록 input 초기화
    e.target.value = "";
  };

  // ❌ 파일 삭제
  const handleRemove = (idx) => {
    setFiles((prev) => prev.filter((_, i) => i !== idx));
  };

  return (
    <>
      {loading && <Loading />}
      <ExcelImportModal sheets={sheets} setSheets={setSheets} />
      <form id="form1" onSubmit={handleFormSubmit}>
        <h1 className="!text-md bg-[#4b5563] !text-white !font-sans  !px-4 !py-2 !mb-4">
          견적서 등록{" "}
        </h1>
        <div className="w-full px-2 text-xs font-sans font-light">
          <div className="w-full relative flex justify-center items-center">
            <img
              className="mb-2 mx-auto"
              src="https://jmtech.test/assets/app_hyup/images/%EA%B2%AC%EC%A0%81%EC%84%9C.png"
              alt="견적서"
            />
          </div>

          {/* 왼쪽 섹션 */}
          <div className="flex border-x-2 border-t-2 border-black">
            <div className="relative flex-1 border-b border-black p-3 pr-14">
              <div className="space-y-2">
                {/* 거래처명 */}
                <div className="flex items-center">
                  <label className="w-[75px]">거 래 처 명 :</label>
                  <div className="flex items-center">
                    <SimpleAutocomplete data={partners} />
                    <button
                      type="button"
                      className="bg-gray-200 border border-gray-400 h-[24px] px-2 text-xs"
                    >
                      🔍
                    </button>
                  </div>
                </div>

                {/* 견적일자 */}
                <div className="flex items-center">
                  <label className="w-[75px]">견 적 일 자 :</label>
                  <input
                    type="date"
                    name="estimateDate"
                    className="border w-[180px] h-[24px] px-1"
                    value={form.estimateDate}
                    onChange={(e) =>
                      setForm({ ...form, estimateDate: e.target.value })
                    }
                  />
                </div>

                {/* 전화번호 */}
                <div className="flex items-center">
                  <label className="w-[75px]">전 화 번 호 :</label>
                  <input
                    type="text"
                    name="phoneNumber"
                    className="border w-[100px] h-[24px] px-1"
                    value={form.phoneNumber}
                    onChange={phoneNumberMask}
                  />
                  <span className="ml-2 w-[75px]">팩 스 번 호 :</span>
                  <input
                    type="text"
                    name="faxNumber"
                    className="border w-[100px] h-[24px] px-1"
                    value={form.faxNumber}
                    onChange={phoneNumberMask}
                  />
                </div>

                {/* 제목 */}
                <div className="flex items-center">
                  <label className="w-[75px]">
                    제&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목 :
                  </label>
                  <input
                    type="text"
                    name="title"
                    className="border flex-1 h-[24px] px-1"
                    value={form.title}
                    onChange={(e) =>
                      setForm({ ...form, title: e.target.value })
                    }
                  />
                </div>
              </div>

              <p className="absolute bottom-[10px] font-semibold text-[13px]">
                견적요청에 감사드리며 아래와 같이 견적합니다.
              </p>
            </div>

            {/* 오른쪽 섹션 (공급자 정보)는 JSX table로 그대로 변환) */}
            <div className="w-[580px] border-l border-black">
              <table className="w-full border-collapse text-sm">
                <colgroup>
                  <col style={{ width: "35px" }} />
                  <col style={{ width: "82px" }} />
                  <col style={{ width: "25px" }} />
                  <col style={{ width: "25px" }} />
                  <col style={{ width: "53px" }} />
                  <col style={{ width: "86px" }} />
                </colgroup>

                <thead>
                  <tr>
                    <td
                      rowSpan="6"
                      className="bg-[#d9d9d9] text-lg font-semibold font-serif text-center align-middle"
                    >
                      공<br />급<br />자
                    </td>
                    <td className="text-center">등록번호</td>
                    <td colSpan="6" className="border-r-0">
                      <span className="text-black">312-86-30100</span>
                    </td>
                  </tr>

                  <tr>
                    <td className="text-center">
                      상&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;호
                    </td>
                    <td colSpan="3">제이엠테크</td>
                    <td className="text-center">
                      성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명
                    </td>
                    <td className="border-r-0">
                      <div className="relative flex items-center">
                        <span>전용준</span>
                        <img
                          className="w-14 h-14 absolute left-6 -top-4"
                          src="https://jmtech.test/assets/app_hyup/images/stamp.png"
                          alt="stamp"
                        />
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td className="text-center">
                      주&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;소
                    </td>
                    <td colSpan="5" className="border-r-0">
                      충청남도 천안시 서북구 두정공단1길 149-2 (두정동,
                      미라클(주)) 제이엠테크
                    </td>
                  </tr>

                  <tr>
                    <td className="text-center">
                      업&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;태
                    </td>
                    <td colSpan="3">제조업</td>
                    <td className="text-center">
                      종&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목
                    </td>
                    <td className="border-r-0">산업기계 설계 및 개발</td>
                  </tr>

                  <tr>
                    <td className="text-center">전화번호</td>
                    <td colSpan="3">041-483-1111</td>
                    <td className="text-center">팩스번호</td>
                    <td className="border-r-0">041-1111-1111</td>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        <div className="flex items-center mx-2 !py-1 px-3 text-xs !border-x-2 !border-b-2 !border-black justify-start">
          <span className="font-semibold mr-2">
            합&nbsp;&nbsp;계&nbsp;&nbsp;금&nbsp;&nbsp;액 : 일금{" "}
          </span>
          <input
            type="text"
            className="border w-[150px] h-[24px]"
            value="₩0"
            readOnly
          />
        </div>

        <div className="flex items-center justify-between px-2.5 !my-1 !py-1">
          <select className="text-[12px]" id="">
            <option value="N">부가세 별도</option>
            <option value="Y">부가세 포함</option>
            <option value="X">부가세 없음</option>
          </select>

          <div className="flex items-center gap-1">
            <button
              type="button"
              onClick={() => {
                document.getElementById("my_modal_1").showModal();
              }}
              className="flex items-center gap-1 border border-gray-300 rounded h-7 !px-1 bg-white hover:bg-gray-50 transition text-xs"
            >
              <img
                width="16"
                alt="Logo of Microsoft Excel since 2019"
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e3/Microsoft_Office_Excel_%282019%E2%80%932025%29.svg/32px-Microsoft_Office_Excel_%282019%E2%80%932025%29.svg.png?20190925171014"
              />
              <span>일괄등록</span>
            </button>

            <button
              type="button"
              onClick={() => {
                const hot = getActiveHotRef();
                if (!hot || hot.isDestroyed) return;
                hot.alter("insert_row_above", hot.countRows());
              }}
              className="flex items-center justify-center w-7 h-7 border border-gray-300 rounded bg-white hover:bg-gray-50 transition"
            >
              <span className="text-blue-600 !text-xl !font-bold !mb-1 leading-none">
                +
              </span>
            </button>

            <button
              type="button"
              onClick={() => {
                const hot = getActiveHotRef();
                if (!hot || hot.isDestroyed) return;
                if (hot.countRows() > 1) {
                  hot.alter("remove_row", hot.countRows() - 1);
                }
              }}
              className="flex items-center justify-center w-7 h-7 border border-gray-300 rounded bg-white hover:bg-gray-50 transition"
            >
              <span className="text-red-500 !text-xl !font-bold leading-none">
                −
              </span>
            </button>
          </div>
        </div>

        <div className="border-2 border-black mx-[9px]">
          {!loading && <SheetSection sheets={sheets} />}

          {/* 하단 입력 테이블 */}
          <table className="w-full border-t-2 border-black text-black text-xs">
            <thead>
              <tr>
                <th className="border-t w-[100px] text-center text-black bg-gray-100">
                  납기일자
                </th>
                <th className="border-t w-[400px]">
                  <input
                    type="date"
                    name="due_at"
                    className="text-black border w-full h-[24px] px-1"
                  />
                </th>
                <th className="border-t bg-gray-100 w-[100px] text-center">
                  납품장소
                </th>
                <th className="border-t">
                  <input
                    type="text"
                    name="location"
                    className="text-black border w-full h-[24px] px-1"
                  />
                </th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td className="border text-center bg-gray-100">유효일자</td>
                <td className="border w-[400px]">
                  <input
                    type="date"
                    name="valid_at"
                    className="text-black border w-full h-[24px] px-1"
                  />
                </td>
                <td className="border bg-gray-100 w-[100px] text-center">
                  결제조건
                </td>
                <td className="border">
                  <input
                    type="text"
                    name="payment_type"
                    className="text-black border w-full h-[24px] px-1"
                  />
                </td>
              </tr>

              <tr>
                <td className="border text-center bg-gray-100">비고</td>
                <td className="border" colSpan="3">
                  <input
                    type="text"
                    name="etc_memo"
                    className="text-black border w-full h-[24px] px-1"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div className="w-full !px-2 !text-xs font-sans font-300">
          <div className="flex items-center gap-4">
            <button
              type="button"
              id="attachBtn"
              onClick={handleAttachClick}
              className="!my-2 flex items-center gap-1 border border-gray-300 rounded h-7 !text-xs !px-1 bg-white hover:bg-gray-50 transition text-sm"
            >
              <input
                type="file"
                multiple
                ref={fileInputRef}
                onChange={handleFileChange}
                style={{ display: "none" }}
              />
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="12"
                height="12"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinecap="round"
                strokeLinejoin="round"
                className="lucide lucide-paperclip-icon lucide-paperclip"
              >
                <path d="m16 6-8.414 8.586a2 2 0 0 0 2.829 2.829l8.414-8.586a4 4 0 1 0-5.657-5.657l-8.379 8.551a6 6 0 1 0 8.485 8.485l8.379-8.551" />
              </svg>
              <span>첨부파일</span>
            </button>

            <div className="flex gap-1">
              {files.length > 0 && (
                <>
                  {files.map((file, idx) => (
                    <div
                      key={idx}
                      className="flex items-center justify-between border border-gray-200 rounded px-2 py-1 bg-gray-50 text-sm"
                    >
                      <span className="text-gray-700 truncate max-w-[350px]">
                        {file.name}
                      </span>
                      <button
                        type="button"
                        onClick={() => handleRemove(idx)}
                        className="text-gray-400 hover:text-red-500 transition text-xs"
                      >
                        ✕
                      </button>
                    </div>
                  ))}
                </>
              )}
            </div>
          </div>

          <input type="file" id="fileInput" className="hidden" multiple />
        </div>

        <div className="w-full !px-2 !text-[13px] flex justify-center items-center gap-1.5 font-sans font-300 !my-2">
          <button
            type="button"
            className="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]"
          >
            저장 후 인쇄
          </button>

          <button className="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
            저장
          </button>

          <button
            type="button"
            className="px-2 py-1 bg-[#fff] text-gray-700 hover:bg-gray-100 border border-gray-300"
          >
            취소
          </button>
        </div>
      </form>
    </>
  );
}
