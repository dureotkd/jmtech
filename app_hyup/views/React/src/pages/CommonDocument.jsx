import React from "react";

import Loading from "../components/Loading";
import SheetSection from "../components/SheetSection";
import ExcelImportModal from "../components/ExcelImportModal";
import SimpleAutocomplete from "../components/SimpleAutoComplete";

import { deepClone, empty, numberToKorean, wait } from "../utils/util";
import { ESTIMATE_SUB_TYPE } from "../../constants";

import request, { STATIC_URL } from "../utils/request";
import estimateApi from "../apis/estimateApi";

import { useExcelStore } from "../store/useExcelStore";

/**
 * ^ 공통문서 페이지 컴포넌트
 * * * [견적서,수주서,발주서]
 * @returns
 */
export default function CommonDocument() {
  // ? & (queryString)
  const queryString = new URLSearchParams(window.location.search);

  const tab = queryString.get("tab") ?? ""; // * copay (복사)
  const id = queryString.get("id") ?? "";
  const type = queryString.get("type") ?? "SELL"; // * SELL / BUY (판매,구매)
  const subType = queryString.get("sub_type") ?? "G"; // * G / S (견적서,수주서)

  // * title 설정

  const { hotRefs, getActiveHotRef, setActiveSheet } = useExcelStore(
    (state) => state
  );
  const [loading, setLoading] = React.useState(false);
  const [form, setForm] = React.useState({
    parent_id: "",
    estimate_date: new Date().toISOString().slice(0, 10), // default 오늘날짜
    phone_number: "",
    fax_number: "",
    title: "",
    vat_type: "N",
    due_at: "",
    location: "",
    valid_at: "",
    payment_type: "",
    etc_memo: "",
    fileIds: [],
  });
  const [files, setFiles] = React.useState([]);
  const [fileIds, setFileIds] = React.useState([]);
  const fileInputRef = React.useRef(null);
  const [amount, setAmount] = React.useState(0);
  const [partners, setPartners] = React.useState([]);
  const [sheets, setSheets] = React.useState([
    // * 모의 데이터 (템플릿은 PHP 서버에서 가져옴)
    {
      name: "내역서",
      data: [],
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
    {
      name: "견적서",
      data: [],
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
  ]);

  // * 기존 견적서 불러오기
  const loadSaveExcelTemplate = async (id) => {
    const res = await estimateApi.저장된엑셀템플릿({ id, sub_type: subType });

    if (!res?.ok && empty(res?.data)) {
      alert(res?.msg || "저장된 엑셀 템플릿 로드에 실패했습니다.");
      history.back();
      return;
    }

    const estimate = res.data.estimate || {};
    const files = res.data.files || [];
    const fileIds = files.map((f) => f.id) || [];
    const cloneForm = { ...form };
    cloneForm.partner_id = estimate.partner_id || "";
    cloneForm.estimate_date = estimate.estimate_date || "";
    cloneForm.phone_number = estimate.phone_number || "";
    cloneForm.fax_number = estimate.fax_number || "";
    cloneForm.title = estimate.title || "";
    cloneForm.due_at = estimate.due_at || "";
    cloneForm.location = estimate.location || "";
    cloneForm.valid_at = estimate.valid_at || "";
    cloneForm.payment_type = estimate.payment_type || "";
    cloneForm.etc_memo = estimate.etc_memo || "";
    cloneForm.partner_name = estimate.partner_name || "";

    setForm(cloneForm);
    setFiles(files);
    setFileIds(fileIds);
    setAmount(estimate.amount || 0);
    setSheets(estimate.sheets || []);
  };

  // * 초기 엑셀 템플릿 로드
  const loadExcelTemplate = async () => {
    const res = await estimateApi.초기엑셀템플릿();
    setSheets(res);
  };

  // * 거래처 목록 로드
  const loadPartnerList = async () => {
    const res = await estimateApi.거래처목록();
    setPartners(res);
  };

  // * 시트 이벤트 등록 (한바퀴 돌아야 Formula 적용 가능)
  const registerSheetEvents = async () => {
    if (!sheets.length) return;

    for (let i = 0; i < sheets.length; i++) {
      setActiveSheet(sheets[i].name);
      await wait(500);
    }

    setActiveSheet(sheets[0].name);
  };

  // * 견적서 저장 핸들러
  const handleFormSubmit = async (e) => {
    e.preventDefault();

    setLoading(true);

    const target = e.target;
    const formData = new FormData(target);

    console.log(hotRefs);

    const hot1 = hotRefs["견적서"];
    const hot2 = hotRefs["내역서"];

    const hots = [hot1, hot2].map((hot) => hot.getData());

    let supplyAmount = 0;
    let taxAmount = 0;

    if (!empty(hots[0])) {
      hots[0].forEach((row) => {
        const 공급가액 = parseFloat(row[4]) || 0;
        const 세액 = parseFloat(row[5]) || 0;
        supplyAmount += 공급가액;
        taxAmount += 세액;
      });
    }

    const cloneSheets = deepClone(sheets);

    cloneSheets[0].data = hots[0];
    cloneSheets[1].data = hots[1];

    formData.append("sheets", JSON.stringify(cloneSheets));
    formData.append("file_ids", fileIds);

    formData.append("tab", tab);
    formData.append("type", type);
    formData.append("sub_type", subType);
    formData.append("partner_id", form?.partner_id || "");
    formData.append("amount", amount || 0);
    formData.append("supply_amount", supplyAmount || 0);
    formData.append("tax_amount", taxAmount || 0);
    formData.append("id", id || "");

    if (files && files.length > 0) {
      files.forEach((file, i) => {
        formData.append(`files[${i}]`, file);
      });
    }

    try {
      const res = await request.post("save_estimate", formData);

      if (!res?.ok) {
        alert(res?.msg || "견적서 저장에 실패했습니다.");
        return;
      }

      alert("견적서가 성공적으로 저장되었습니다.");

      if (res?.redirect_url) {
        window.location.href = `${STATIC_URL}${res.redirect_url}`;
      }

      window?.opener?.location.reload();
    } catch (err) {
      console.error("업로드 실패:", err);
    } finally {
      setLoading(false);
    }
  };

  // * 전화번호 마스킹
  const phoneNumberMask = (e) => {
    const raw = e.target.value.replace(/[^0-9]/g, "");
    const name = e.target.name;
    const formatted = raw
      .replace(/^(\d{2,3})(\d{3,4})(\d{4})$/, "$1-$2-$3")
      .slice(0, 13);

    setForm((prev) => ({
      ...prev,
      [name]: formatted,
    }));
  };

  // * 파일 첨부 버튼 클릭
  const handleAttachClick = () => {
    fileInputRef.current?.click();
  };

  // * 파일 선택
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

  // * 파일 삭제
  const handleRemove = (idx) => {
    if (confirm("선택한 파일을 삭제하시겠습니까?")) {
      setFiles((prev) => prev.filter((_, i) => i !== idx));
      setFileIds((prev) => prev.filter((_, i) => i !== idx));
    }
  };

  // * 부가세 처리
  const handleVat = (e) => {
    const vatOption = e.target.value;
    const cloneSheets = deepClone(sheets);

    if (empty(cloneSheets[0]?.data)) return;

    switch (vatOption) {
      // * 부가세 별도
      case "N":
        cloneSheets[0].data = cloneSheets[0].data.map((row, rowIndex) => {
          const 수량 = row[2];
          const 단가 = row[3];

          const 공급가액 = 단가 > 0 ? 수량 * 단가 : "";
          const 세액 = 공급가액 > 0 ? Math.round(공급가액 * 0.1) : "";

          row[4] = 공급가액;
          row[5] = 세액;

          return row;
        });

        break;

      // * 부가세 포함
      case "Y":
        cloneSheets[0].data = cloneSheets[0].data.map((row, rowIndex) => {
          const 수량 = row[2];
          const 단가 = row[3];

          const 공급가액 = 단가 > 0 ? Math.round((수량 * 단가) / 1.1) : "";
          const 세액 = 공급가액 > 0 ? 수량 * 단가 - 공급가액 : "";

          row[4] = 공급가액;
          row[5] = 세액;

          return row;
        });

        break;

      // * 부가세 없음
      case "X":
        cloneSheets[0].data = cloneSheets[0].data.map((row, rowIndex) => {
          const 수량 = row[2];
          const 단가 = row[3];

          const 공급가액 = 단가 > 0 ? 수량 * 단가 : 0;
          const 세액 = 0;

          row[4] = 공급가액;
          row[5] = 세액;

          return row;
        });

        break;
      default:
        break;
    }

    const 견적서 = cloneSheets[0].data;
    let newAmount = 0;
    견적서.forEach((row) => {
      const 공급가액 = parseFloat(row[4]) || 0;
      const 세액 = parseFloat(row[5]) || 0;
      newAmount += 공급가액 + 세액;
    });

    setAmount(newAmount);
    setForm((prev) => ({ ...prev, vat_type: vatOption }));
    setSheets(cloneSheets);
  };

  React.useEffect(() => {
    (async () => {
      try {
        document.title = `${ESTIMATE_SUB_TYPE[subType]} 등록`;
        setLoading(true);

        if (id) {
          // * 기존 견적서 불러오기
          await loadSaveExcelTemplate(id);
        } else {
          // * 초기 엑셀 템플릿 로드
          await loadExcelTemplate();
        }

        // * 거래처 목록 로드
        await loadPartnerList();

        // * 시트 이벤트 등록 (한바퀴 돌아야 Formula 적용 가능)
        if (sheets.length > 0) {
          await registerSheetEvents();
        }
      } catch (error) {
        alert("엑셀 템플릿 로드 중 오류가 발생했습니다.");
      } finally {
        setLoading(false);
      }
    })();
  }, []);

  return (
    <>
      {loading && <Loading />}
      <ExcelImportModal
        sheets={sheets}
        setSheets={setSheets}
        setAmount={setAmount}
      />
      <form id="form1" onSubmit={handleFormSubmit}>
        <input type="hidden" name="id" value={id} />
        <h1 className="!text-md bg-[#4b5563] !text-white !font-sans  !px-4 !py-2 !mb-4">
          {ESTIMATE_SUB_TYPE[subType]} 등록{" "}
        </h1>
        <div className="w-full px-2 text-xs font-sans font-light">
          <div className="w-full relative flex justify-center items-center">
            <img
              className="mb-2 mx-auto"
              src={`https://www.jmtech.asia/assets/app_hyup/images/${ESTIMATE_SUB_TYPE[subType]}.png`}
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
                    <SimpleAutocomplete
                      defaultValue={form.partner_name}
                      data={partners}
                      name="partner_name"
                      onChange={(id) => {
                        setForm((prev) => ({ ...prev, partner_id: id }));
                      }}
                    />
                    <input
                      type="hidden"
                      name="partner_id"
                      defaultValue={form.parent_id}
                    />
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
                    name="estimate_date"
                    className="border w-[180px] h-[24px] px-1"
                    defaultValue={form.estimate_date}
                  />
                </div>

                {/* 전화번호 */}
                <div className="flex items-center">
                  <label className="w-[75px]">전 화 번 호 :</label>
                  <input
                    type="text"
                    name="phone_number"
                    className="border w-[100px] h-[24px] px-1"
                    value={form.phone_number}
                    onChange={phoneNumberMask}
                  />
                  <span className="ml-2 w-[75px]">팩 스 번 호 :</span>
                  <input
                    type="text"
                    name="fax_number"
                    className="border w-[100px] h-[24px] px-1"
                    value={form.fax_number}
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
                    defaultValue={form.title}
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
                          src="https://www.jmtech.asia/assets/app_hyup/images/stamp.png"
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
        <div className="relative flex items-center mx-2 !py-1 px-3 text-xs !border-x-2 !border-b-2 !border-black justify-start">
          <span className="font-semibold mr-1">
            합&nbsp;&nbsp;계&nbsp;&nbsp;금&nbsp;&nbsp;액 : 일금{" "}
          </span>

          <h2 className="font-bold">
            {numberToKorean(amount)}
            <input
              type="text"
              name="amount"
              className="ml-1 border w-[150px] h-[24px]"
              value={`₩ ${amount.toLocaleString()}`}
              readOnly
            />
          </h2>
        </div>

        <div className="flex items-center justify-between px-2.5 !my-1 !py-1">
          <select
            id="select-vat"
            name="vat_type"
            className="text-[12px]"
            defaultValue={form.vat_type}
            onChange={handleVat}
          >
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
          <SheetSection
            sheets={sheets}
            vatType={form.vat_type}
            setAmount={setAmount}
          />

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
                    defaultValue={form.due_at}
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
                    defaultValue={form.location}
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
                    defaultValue={form.valid_at}
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
                    defaultValue={form.payment_type}
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
                    defaultValue={form.etc_memo}
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
              className="!my-2 min-w-[80px] flex items-center gap-1 border border-gray-300 text-center rounded h-7 !px-1 bg-white hover:bg-gray-50 transition text-xs"
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

            <div className="flex !flex-wrap my-2 gap-1">
              {files.length > 0 && (
                <>
                  {files.map((file, idx) => (
                    <div
                      key={idx}
                      className="flex items-center justify-between border border-gray-200 rounded px-2 py-1 bg-gray-50 text-sm"
                    >
                      <span className="text-gray-700 truncate max-w-[350px]">
                        {file?.name || file?.file_name}
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
          <button className="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
            저장
          </button>

          <button
            type="button"
            onClick={() => {
              window.close();
            }}
            className="px-2 py-1 bg-[#fff] text-gray-700 hover:bg-gray-100 border border-gray-300"
          >
            취소
          </button>
        </div>
      </form>
    </>
  );
}
