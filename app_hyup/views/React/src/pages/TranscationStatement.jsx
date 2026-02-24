import React from "react";

import { useExcelStore } from "../store/useExcelStore";
import { ESTIMATE_SUB_TYPE } from "../../constants";
import purchaseApi from "../apis/purchaseApi";
import request, { STATIC_URL } from "../utils/request";
import { deepClone, empty, numberToKorean, wait } from "../utils/util";

import Loading from "../components/Loading";
import ExcelImportModal from "../components/ExcelImportModal";
import SimpleAutocomplete from "../components/SimpleAutoComplete";
import NormalSheetSection from "../components/NormalSheetSection";
import ComponentLoading from "../components/ComponentLoading";
/**
 * ^ 거래명세표 페이지 컴포넌트
 * * [매입(거래명세표),매출(거래명세표)]
 * @returns
 */
function TranscationStatement() {
  // ? & (queryString)
  const queryString = new URLSearchParams(window.location.search);

  const tab = queryString.get("tab") ?? ""; // * copay (복사)
  const id = queryString.get("id") ?? "";
  const type = queryString.get("type") ?? "BUY"; // * SELL / BUY (판매,구매)
  const subType = queryString.get("sub_type") ?? "MI"; // * MI / MO (매입,매출)
  const subTypeKorean =
    subType === "MI" ? "매입 거래명세표" : "매출 거래명세표";
  // * title 설정
  const { hotRefs, getActiveHotRef, setActiveSheet } = useExcelStore(
    (state) => state
  );
  const [loading, setLoading] = React.useState(false);

  const [tradeHistory, setTradeHistory] = React.useState([]);
  const [tradeHistoryLoading, setTradeHistoryLoading] = React.useState(false);
  const [tradeHistoryToggle, setTradeHistoryToggle] = React.useState(false);
  const tradeHistoryRef = React.useRef(null);
  const tradeHistoryButtonRef = React.useRef(null);

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
  const [amount, setAmount] = React.useState({
    supply: 0,
    tax: 0,
  });
  const [partners, setPartners] = React.useState([]);
  const [searchPartnerName, setSearchPartnerName] = React.useState("");
  const [sheets, setSheets] = React.useState([
    // * 모의 데이터 (템플릿은 PHP 서버에서 가져옴)
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
  ]);

  // * 기존 견적서 불러오기
  const loadSaveExcelTemplate = async (id) => {
    const res = await purchaseApi.저장된엑셀템플릿({
      id,
      theme: "blue",
      sub_type: subType,
    });

    if (!res?.ok && empty(res?.data)) {
      alert(res?.msg || "저장된 엑셀 템플릿 로드에 실패했습니다.");
      history.back();
      return;
    }

    const statement = res.data.statement || {};
    const files = res.data.files || [];
    const fileIds = files.map((f) => f.id) || [];
    const cloneForm = { ...form };
    cloneForm.partner_id = statement.partner_id || "";
    cloneForm.estimate_date = statement.estimate_date || "";
    cloneForm.phone_number = statement.phone_number || "";
    cloneForm.fax_number = statement.fax_number || "";
    cloneForm.title = statement.title || "";
    cloneForm.due_at = statement.due_at || "";
    cloneForm.location = statement.location || "";
    cloneForm.valid_at = statement.valid_at || "";
    cloneForm.payment_type = statement.payment_type || "";
    cloneForm.etc_memo = statement.etc_memo || "";
    cloneForm.partner_name = statement.partner_name || "";
    setForm(cloneForm);
    setFiles(files);
    setFileIds(fileIds);
    setAmount({
      supply: statement.supply_amount || 0,
      tax: statement.tax_amount || 0,
    });
    setSheets(statement.sheets || []);
  };

  // * 초기 엑셀 템플릿 로드
  const loadExcelTemplate = async () => {
    const res = await purchaseApi.초기엑셀템플릿(subType);
    setSheets(res);
  };

  // * 거래처 목록 로드
  const loadPartnerList = async () => {
    const res = await purchaseApi.거래처목록();
    setPartners(res);
  };

  // * 견적서 저장 핸들러
  const handleFormSubmit = async (e) => {
    // * # form1
    setLoading(true);

    const formData = new FormData(document.getElementById("form1"));
    const hot = hotRefs[subTypeKorean].getData();

    const cloneSheets = deepClone(sheets);
    cloneSheets[0].data = hot;

    formData.append("sheets", JSON.stringify(cloneSheets));
    formData.append("file_ids", fileIds);

    formData.append("tab", tab);
    formData.append("type", type);
    formData.append("sub_type", subType);
    formData.append("partner_id", form?.partner_id || "");
    formData.append(
      "amount",
      parseInt(amount.supply) + parseInt(amount.tax) || 0
    );
    formData.append("supply_amount", parseInt(amount.supply) || 0);
    formData.append("tax_amount", parseInt(amount.tax) || 0);
    formData.append("id", id || "");

    if (files && files.length > 0) {
      const filePromises = files.map(async (fileObj, i) => {
        const realFile = await convertToFileIfNeeded(fileObj);
        formData.append(`files[${i}]`, realFile);
      });

      // 모든 파일 변환 끝날 때까지 기다림
      await Promise.all(filePromises);
    }

    try {
      const res = await purchaseApi.명세표저장(formData);

      if (!res?.ok) {
        alert(res?.msg || "명세표 저장에 실패했습니다.");
        return;
      }

      alert("명세표가 성공적으로 저장되었습니다.");

      if (res?.redirect_url) {
        window.location.href = `${STATIC_URL}${res.redirect_url}`;
      }

      // * 부모 창 새로고침
      window?.opener?.location.reload();
    } catch (err) {
      console.error("업로드 실패:", err);
    } finally {
      setLoading(false);
    }
  };

  async function convertToFileIfNeeded(fileObj) {
    // 1) 이미 File 이면 그대로 반환
    if (fileObj instanceof File) {
      return fileObj;
    }

    // 2) 기존 저장된 객체라면 File로 변환
    // fileObj = { name, type, url(or file_path) ... }
    const url = fileObj.file_url;

    const res = await fetch(url);
    const blob = await res.blob();

    return new File([blob], fileObj.file_name, {
      type: fileObj.type || blob.type,
    });
  }

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
    let supplyAmount = 0;
    let taxAmount = 0;
    견적서.forEach((row) => {
      const 공급가액 = parseFloat(row[4]) || 0;
      const 세액 = parseFloat(row[5]) || 0;
      supplyAmount += 공급가액;
      taxAmount += 세액;
    });

    setAmount({
      supply: supplyAmount,
      tax: taxAmount,
    });
    setForm((prev) => ({ ...prev, vat_type: vatOption }));
    setSheets(cloneSheets);
  };

  const fetchTradeHistory = async () => {
    setTradeHistoryLoading(true);
    try {
      const res = await purchaseApi.거래내역불러오기(searchPartnerName);
      setTradeHistory(res?.data || []);
    } catch (error) {
      console.error(error);
    } finally {
      setTradeHistoryLoading(false);
    }
  };

  // * 전체거래처 div외에 다른 div 클릭시 tradeHistoryToggle 초기화
  React.useEffect(() => {
    const handleClickOutside = (event) => {
      if (
        tradeHistoryToggle &&
        tradeHistoryRef.current &&
        tradeHistoryButtonRef.current &&
        !tradeHistoryRef.current.contains(event.target) &&
        !tradeHistoryButtonRef.current.contains(event.target)
      ) {
        setTradeHistoryToggle(false);
      }
    };

    if (tradeHistoryToggle) {
      document.addEventListener("mousedown", handleClickOutside);
    }

    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, [tradeHistoryToggle]);

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
      } catch (error) {
        console.error(error);
      } finally {
        setLoading(false);
      }
    })();
  }, []);

  const tableTheme = React.useMemo(() => {
    if (subType === "MI") {
      return {
        border: "border-blue-700",
        table: "blue-table",
        color: "!text-blue-700",
        backgroundColor: "!bg-[#edf2f8]",
      };
    } else if (subType === "MC") {
      return {
        border: "border-red-700",
        table: "red-table",
        color: "!text-[#E53935]",
        backgroundColor: "!bg-[#FFEBEE]",
      };
    }
    return {
      border: "",
      table: "",
    };
  }, [subType]);

  return (
    <>
      {loading && <Loading />}
      <ExcelImportModal
        sheets={sheets}
        setSheets={setSheets}
        setAmount={setAmount}
      />
      <form id="form1">
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
            <div className="absolute right-0 top-0">
              <div
                ref={tradeHistoryButtonRef}
                onClick={async (e) => {
                  e.stopPropagation();
                  setTradeHistoryToggle((prev) => !prev);

                  if (tradeHistoryToggle) {
                    return;
                  }

                  await fetchTradeHistory();
                }}
                className="px-2 py-1 text-xs cursor-pointer hover:underline"
              >
                거래내역 불러오기
              </div>
              {tradeHistoryToggle && (
                <div
                  ref={tradeHistoryRef}
                  className="absolute top-[24px] w-[420px] right-0 z-10 bg-white border border-gray-900"
                >
                  <div className="border border-gray-300 rounded bg-white shadow-sm text-xs font-sans">
                    {/* Filter bar */}
                    <div className="flex items-center border-b border-gray-200 px-2 py-1 bg-gray-50">
                      <div className="flex items-center gap-1">
                        <select className="border border-gray-300 rounded px-1 py-0.5 text-xs h-[24px]">
                          <option>최근 3개월</option>
                        </select>
                        <select className="border border-gray-300 rounded px-1 py-0.5 text-xs h-[24px]">
                          <option>전체</option>
                        </select>
                      </div>
                      <div className="ml-auto flex items-center">
                        <input
                          type="text"
                          placeholder="거래처명"
                          value={searchPartnerName}
                          onChange={(e) => {
                            setSearchPartnerName(e.target.value);
                          }}
                          className="border border-gray-300 rounded px-2 py-0.5 text-xs h-[24px] w-[200px]"
                        />
                        <button
                          type="button"
                          onClick={async () => {
                            await fetchTradeHistory();
                          }}
                          className="bg-gray-200 border border-gray-400 h-[23px] px-2 text-xs"
                        >
                          🔍
                        </button>
                      </div>
                    </div>
                    {/* List */}
                    <div className="max-h-[220px] overflow-y-auto">
                      <ul className="divide-y divide-gray-100">
                        {tradeHistoryLoading ? (
                          <ComponentLoading className="!w-8 !h-8 mx-auto" />
                        ) : tradeHistory.length > 0 ? (
                          tradeHistory.map((item, index) => {
                            return (
                              <li
                                onClick={async () => {
                                  /**
                                   * * List 클릭시 Excel 템플릿 로드
                                   * * 거래처명, 거래처ID, 거래처 정보 표시
                                   */
                                  setForm((prev) => ({
                                    ...prev,
                                    partner_id: 1,
                                    partner_name: item.거래처명,
                                  }));
                                  item.sheets[0]["name"] = subTypeKorean;

                                  let supplyAmount = 0;
                                  let taxAmount = 0;

                                  /**
                                   * * --------------------- 매출/매입 거래명세표에 맞게 FIELD 수정 -------------------------
                                   */
                                  item.sheets[0]["data"] = item.sheets[0][
                                    "data"
                                  ].map((item) => {
                                    return [
                                      new Date().toISOString().slice(0, 10),
                                      ...item,
                                    ];
                                  });
                                  item.sheets[0]["data"].forEach((row) => {
                                    supplyAmount += parseFloat(row[5]) || 0;
                                    taxAmount += parseFloat(row[6]) || 0;
                                  });
                                  const cloneSheets = deepClone(sheets);
                                  cloneSheets[0]["data"] =
                                    item.sheets[0]["data"];
                                  setSheets(cloneSheets);
                                  setAmount({
                                    supply: supplyAmount,
                                    tax: taxAmount,
                                  });
                                  setTradeHistoryToggle(false);
                                }}
                                key={`${index}-${item.구분}-${item.품목명}`}
                                className="flex items-center px-2 space-x-4 py-1 hover:bg-gray-50 transition"
                              >
                                <div className="w-[70px] text-gray-400 font-mono">
                                  {item.월일}
                                </div>
                                <div className="w-[80px] text-gray-700 text-[13px]">
                                  {item.구분}
                                </div>
                                <div
                                  title={item.거래처명}
                                  className="flex-1 truncate px-1 text-gray-700"
                                >
                                  {item.거래처명}
                                </div>
                                <div className="w-[80px] text-right">
                                  <span className="text-blue-500 font-semibold">
                                    {item.공급가액 > 0
                                      ? `${Number(
                                          item.공급가액
                                        ).toLocaleString()}원`
                                      : "-"}
                                  </span>
                                </div>
                              </li>
                            );
                          })
                        ) : (
                          <div className="flex items-center justify-center h-full !py-4">
                            <div className="text-gray-500">
                              거래내역이 없습니다.
                            </div>
                          </div>
                        )}
                      </ul>
                    </div>
                  </div>
                </div>
              )}
            </div>
          </div>

          {/* 왼쪽 섹션 */}
          <div className={`flex border-x-2 border-t-2 ${tableTheme.border}`}>
            <div
              className={`relative flex-1 border-b ${tableTheme.border} p-3 pr-14`}
            >
              <div className="space-y-2">
                {/* 거래처명 */}
                <div className="flex items-center">
                  <label className={`${tableTheme.color} w-[75px]`}>
                    거 래 처 명 :
                  </label>
                  <div className="flex items-center">
                    <SimpleAutocomplete
                      defaultValue={form.partner_name}
                      data={partners}
                      name="partner_name"
                      onChange={(id) => {
                        const partner = partners.find(
                          (partner) => partner.id === id
                        );
                        setSearchPartnerName(partner?.company_name || "");
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
                  <label className={`${tableTheme.color} w-[75px]`}>
                    견 적 일 자 :
                  </label>
                  <input
                    type="date"
                    name="estimate_date"
                    className="border w-[180px] h-[24px] px-1"
                    defaultValue={form.estimate_date}
                  />
                </div>

                {/* 전화번호 */}
                <div className="flex items-center">
                  <label className={`${tableTheme.color} w-[75px]`}>
                    전 화 번 호 :
                  </label>
                  <input
                    type="text"
                    name="phone_number"
                    className="border w-[100px] h-[24px] px-1"
                    value={form.phone_number}
                    onChange={phoneNumberMask}
                  />
                  <label className={`${tableTheme.color} ml-2 w-[75px]`}>
                    팩 스 번 호 :
                  </label>
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
                  <label className={`${tableTheme.color} w-[75px]`}>
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
            </div>

            {/* 오른쪽 섹션 (공급자 정보)는 JSX table로 그대로 변환) */}
            <div className={`w-[580px] border-l ${tableTheme.border}`}>
              <table
                className={`w-full ${tableTheme.table} border-collapse text-sm`}
              >
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
                    <td className={`text-center ${tableTheme.color}`}>
                      등록번호
                    </td>
                    <td colSpan="6" className="border-r-0">
                      <span className="text-black">312-86-30100</span>
                    </td>
                  </tr>

                  <tr>
                    <td className={`text-center ${tableTheme.color}`}>
                      상&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;호
                    </td>
                    <td colSpan="3">제이엠테크</td>
                    <td className={`text-center ${tableTheme.color}`}>
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
                    <td className={`text-center ${tableTheme.color}`}>
                      주&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;소
                    </td>
                    <td colSpan="5" className="border-r-0">
                      충청남도 천안시 서북구 두정공단1길 149-2 (두정동,
                      미라클(주)) 제이엠테크
                    </td>
                  </tr>

                  <tr>
                    <td className={`text-center ${tableTheme.color}`}>
                      업&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;태
                    </td>
                    <td colSpan="3">제조업</td>
                    <td className={`text-center ${tableTheme.color}`}>
                      종&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목
                    </td>
                    <td className="border-r-0">산업기계 설계 및 개발</td>
                  </tr>

                  <tr>
                    <td className={`text-center ${tableTheme.color}`}>
                      전 화 번 호
                    </td>
                    <td colSpan="3">041-483-1111</td>
                    <td className={`text-center ${tableTheme.color}`}>
                      팩 스 번 호
                    </td>
                    <td className="border-r-0">041-1111-1111</td>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        <div
          className={`relative flex items-center mx-2 !py-1 px-3 text-xs !border-x-2 !border-b-2 ${tableTheme.border} justify-start`}
        >
          <span className={`text-[15px] mr-1 ${tableTheme.color} font-bold`}>
            합&nbsp;계&nbsp;금&nbsp;액 :
          </span>

          <h2 className="font-bold">
            <input
              type="text"
              name="amount"
              className="ml-1 border w-[150px] h-[24px]"
              value={`₩ ${Number(
                parseInt(amount.supply) + parseInt(amount.tax)
              ).toLocaleString()}`}
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
                const hot = hotRefs[subTypeKorean];
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
                const hot = hotRefs[subTypeKorean];
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

        <div className={`border-2 ${tableTheme.border} mx-[9px]`}>
          <NormalSheetSection
            theme={subType === "MI" ? "blue" : "red"}
            sheets={sheets}
            vatType={form.vat_type}
            setAmount={setAmount}
            subType={subType}
          />

          {/* 하단 입력 테이블 */}
          <table
            className={`w-full ${tableTheme.table} border-t-2 ${tableTheme.border} text-black text-xs`}
          >
            <thead>
              <tr>
                <th
                  className={`border-t w-[100px] text-center ${tableTheme.backgroundColor} ${tableTheme.color}`}
                >
                  전미수잔액
                </th>
                <th className={`border-t w-[400px] ${tableTheme.color}`}></th>
                <th
                  className={`border-t ${tableTheme.backgroundColor} w-[80px] text-center ${tableTheme.color}`}
                >
                  합계
                </th>
                <th className="border-t" colSpan={6}>
                  {`(공급가액 ${parseInt(
                    amount.supply
                  ).toLocaleString()} + 세액 ${parseInt(
                    amount.tax
                  ).toLocaleString()}) = 총 합계 ${Number(
                    parseInt(amount.supply) + parseInt(amount.tax)
                  ).toLocaleString()} 원`}
                </th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td
                  className={`border text-center ${tableTheme.backgroundColor} ${tableTheme.color}`}
                >
                  비고
                </td>
                <td className="border">
                  <input
                    type="text"
                    name="etc_memo"
                    defaultValue={form.etc_memo}
                    className="text-black border w-full h-[24px] px-1"
                  />
                </td>
                <td
                  className={`border ${tableTheme.backgroundColor} w-[80px] text-center ${tableTheme.color}`}
                >
                  입금액
                </td>
                <td className="border"></td>

                <td
                  className={`border ${tableTheme.backgroundColor} w-[80px] text-center ${tableTheme.color}`}
                >
                  총미수잔액
                </td>
                <td className="border"></td>

                <td
                  className={`border ${tableTheme.backgroundColor} w-[80px] text-center ${tableTheme.color}`}
                >
                  인수자
                </td>
                <td className="border"></td>
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
          <button
            type="button"
            onClick={handleFormSubmit}
            className="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]"
          >
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

export default TranscationStatement;
