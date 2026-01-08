import React from "react";

import Loading from "./Loading";

import estimateApi from "../apis/estimateApi";
import { useExcelStore } from "../store/useExcelStore";
import { deepClone } from "../utils/util";

export default function ExcelImportModal({
  sheets = [],
  setSheets,
  setAmount,
}) {
  const [loading, setLoading] = React.useState(false);

  const [fileName, setFileName] = React.useState("");
  const fileInputRef = React.useRef(null);

  const { getHotRef } = useExcelStore((state) => state);

  // * 엑셀 파일 선택 처리
  const handleChangeExcelFile = (e) => {
    const file = e.target.files?.[0];
    setFileName(file ? file.name : "");
  };

  // * 모달 닫기
  const onClose = () => {
    const modal = document.getElementById("my_modal_1");
    setFileName("");
    fileInputRef.current.value = "";
    modal.close();
  };

  // * 엑셀 불러오기 처리

  /**
   * 
   * @param {Array
(
    [ok] => 1
    [msg] => 
    [data] => Array
        (
            [0] => Array
                (
                    [도번] => s
                    [재질] => al
                    [가로] => 1219
                    [세로] => 2438
                    [두께] => 3
                    [홀수] => 1.0
                    [탭] => 2.0
                    [절곡] => 3.0
                    [길이] => 4.0
                    [후] => 5.0
                    [용접] => 
                    [연마] => 
                    [기타] => 1
                    [수량] => 2
                    [비고] => 
                )

        )

)} e 
   * @returns 
   */
  const handleExcelForm = async (e) => {
    e.preventDefault();
    document.querySelector("#select-vat").value = "N"; // 부가세 별도 초기화

    const file = fileInputRef.current?.files?.[0];
    console.log("🚀 Debug: ~ handleExcelForm ~ file:", file);

    const sheetName = e.target.sheet_select.value;

    if (!file) {
      alert("엑셀 파일을 선택해주세요.");
      return;
    }

    setLoading(true);
    const formData = new FormData();
    formData.append("excel_file", file);
    formData.append("sheet_name", sheetName);

    try {
      // ^ 엑셀 불러오기 API 호출
      const res = await estimateApi.엑셀불러오기(formData);

      if (!res?.ok) {
        alert(res?.msg);
        return;
      }

      const data = res.data;

      const merged = [...data];
      const hotRefs = getHotRef();
      const activeHotRef = hotRefs[sheetName];

      const cloneSheets = deepClone(sheets);

      cloneSheets[0].data = cloneSheets[0].data.map((item, index) => {
        const 매칭ITEM = data[index];

        if (!매칭ITEM) {
          return item;
        }

        return item.map((value, key) => {
          if (key == 0) {
            value = 매칭ITEM["도번"];
          }

          if (key == 1) {
            value = 매칭ITEM["재질"];
          }

          if (key == 2) {
            value = 매칭ITEM["가로"];
          }

          if (key == 3) {
            value = 매칭ITEM["세로"];
          }

          if (key == 4) {
            value = 매칭ITEM["두께"];
          }

          if (key == 5) {
            value = 매칭ITEM["홀수"];
          }

          if (key == 6) {
            value = 매칭ITEM["탭"];
          }

          if (key == 7) {
            value = 매칭ITEM["절곡"];
          }

          if (key == 8) {
            value = 매칭ITEM["길이"];
          }

          if (key == 9) {
            value = 매칭ITEM["후"];
          }

          if (key == 10) {
            value = 매칭ITEM["수량"];
          }

          if (key == 18) {
            value = 매칭ITEM["용접"];
          }

          if (key == 19) {
            value = 매칭ITEM["연마"];
          }

          if (key == 21) {
            value = 매칭ITEM["기타"];
          }

          if (key == 27) {
            value = 매칭ITEM["비고"];
          }

          return value;
        });
      });

      // let options = {
      //   height: "auto",
      // };

      // if (merged.length > 20) {
      //   options.height = 500;
      // }

      activeHotRef.loadData([]); // 기존 데이터 초기화

      // // ^ 공급가액 + 세액 합계 계산
      // const amount = resData.reduce(
      //   (acc, row) => acc + (row[4] || 0) + (row[5] || 0),
      //   0
      // );
      // console.log(amount);
      // setAmount(amount);

      // ^ 시트 데이터 업데이트
      setSheets(cloneSheets);
      // setSheets((prevSheets) =>
      //   prevSheets.map((sheet) =>
      //     sheet.name === sheetName ? { ...sheet, ...options } : sheet
      //   )
      // );

      onClose();
    } catch (error) {
      console.log("🚀 Debug: ~ handleExcelForm ~ error:", error);
      alert("엑셀 파일의 양식이 올바르지 않습니다. 다시 확인해주세요.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <dialog id="my_modal_1" className="modal">
      <div className="modal-box text-xs w-[400px] relative">
        {/* ✅ 로딩 오버레이 */}
        {loading && <Loading />}

        <form
          id="excel_form"
          onSubmit={handleExcelForm}
          className="bg-white w-full border border-gray-300"
        >
          {/* 헤더 */}
          <div className="flex justify-between items-center text-base px-4 py-2 bg-[#4b5563]">
            <h2 className="text-white font-semibold">엑셀 불러오기</h2>
            <button type="button" className="text-gray-200" onClick={onClose}>
              ✕
            </button>
          </div>

          {/* 본문 */}
          <div className="p-5 space-y-4">
            {/* 서식 다운로드 */}
            <div
              className="flex justify-end text-sm text-gray-700 items-center"
              onClick={async () => {
                setLoading(true);
                await estimateApi.견적서일괄등록품목양식다운로드();
                setLoading(false);
              }}
            >
              <a href="#" className="flex items-center text-xs hover:underline">
                견적서 품목양식
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="14"
                  height="14"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="2"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  className="ml-1"
                >
                  <path d="M12 15V3" />
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                  <path d="m7 10 5 5 5-5" />
                </svg>
              </a>
            </div>

            {/* 파일 선택 */}
            <div className="flex items-center">
              <label className="block text-sm font-semibold w-[70px] mb-1">
                파일선택
              </label>
              <div className="flex w-[300px]">
                <input
                  type="text"
                  value={fileName}
                  placeholder="파일을 선택하세요"
                  readOnly
                  className="flex-1 border border-gray-300 px-2 py-1.5"
                />
                <input
                  ref={fileInputRef}
                  id="excelFileInput"
                  type="file"
                  accept=".xls,.xlsx"
                  className="hidden"
                  onChange={handleChangeExcelFile}
                />
                <button
                  type="button"
                  className="bg-gray-200 border border-l-0 border-gray-300 px-3 hover:bg-gray-300"
                  onClick={() => fileInputRef.current?.click()}
                >
                  파일열기
                </button>
              </div>
            </div>

            {/* 시트 선택 */}
            <div className="flex items-center">
              <label className="block text-sm font-semibold w-[70px] mb-1">
                시트선택
              </label>
              <select
                name="sheet_select"
                className="sheet_select w-[300px] border border-gray-300 px-2 py-1"
              >
                {sheets.map((sheet) => (
                  <option key={sheet.name} value={sheet.name}>
                    {sheet.name}
                  </option>
                ))}
              </select>
            </div>
          </div>

          {/* 하단 버튼 */}
          <div className="w-full px-2 text-[13px] flex justify-center items-center gap-1.5 font-sans my-2">
            <button
              type="submit"
              disabled={loading}
              className="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]"
            >
              불러오기
            </button>
            <button
              type="button"
              onClick={onClose}
              className="px-2 py-1 bg-white text-gray-700 hover:bg-gray-100 border border-gray-300"
            >
              취소
            </button>
          </div>
        </form>
      </div>
    </dialog>
  );
}
