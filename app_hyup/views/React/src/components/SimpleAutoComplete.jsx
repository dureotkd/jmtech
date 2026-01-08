import React, { useState } from "react";
import Autosuggest from "react-autosuggest";

export default function SimpleAutocomplete({
  defaultValue = "",
  data,
  name,
  onChange,
}) {
  const [value, setValue] = useState("");
  const [suggestions, setSuggestions] = useState([]);

  const getSuggestions = (input) => {
    const term = input?.trim()?.toLowerCase();
    if (!term) return [];
    const filtered = data.filter(
      (c) =>
        c.company_name.toLowerCase().includes(term) ||
        c.ceo_name.toLowerCase().includes(term) ||
        c.company_num.toLowerCase().includes(term)
    );

    // 즐겨찾기된 거래처를 맨 위로 정렬
    const sorted = filtered.sort((a, b) => {
      const aBookmark = (a.bookmark_yn || "N") === "Y" ? 1 : 0;
      const bBookmark = (b.bookmark_yn || "N") === "Y" ? 1 : 0;
      return bBookmark - aBookmark; // 즐겨찾기가 있는 것이 위로
    });

    return sorted.slice(0, 30);
  };

  React.useEffect(() => {
    setValue(defaultValue);
  }, [defaultValue]);

  return (
    <Autosuggest
      className="w-[400px]"
      suggestions={suggestions}
      onSuggestionsFetchRequested={({ value }) =>
        setSuggestions(getSuggestions(value))
      }
      onSuggestionsClearRequested={() => setSuggestions([])}
      getSuggestionValue={(s) => s.company_name}
      onSuggestionSelected={(_, { suggestion }) => {
        setValue(suggestion.company_name);
        onChange(suggestion.id);
      }}
      renderSuggestion={(s, { query }) => {
        const highlight = (text) => {
          const safeQuery = escapeRegExp(query); // 🔒 안전하게 변환
          const regex = new RegExp(safeQuery, "gi");

          return text.replace(
            regex,
            (match) => `<span class='highlight'>${match}</span>`
          );
        };

        function escapeRegExp(string) {
          return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
        }
        const isBookmarked = (s.bookmark_yn || "N") === "Y";
        return (
          <div className="flex justify-between items-center px-2 py-1 text-xs cursor-pointer hover:bg-gray-50">
            <div className="flex items-center gap-1" style={{ width: "200px" }}>
              {isBookmarked && (
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="14"
                  height="14"
                  viewBox="0 0 24 24"
                  fill="#fbbf24"
                  stroke="#fbbf24"
                  strokeWidth="2"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  className="flex-shrink-0"
                >
                  <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                </svg>
              )}
              <div
                dangerouslySetInnerHTML={{ __html: highlight(s.company_name) }}
              />
            </div>
            <div
              style={{ width: "60px" }}
              dangerouslySetInnerHTML={{ __html: highlight(s.ceo_name) }}
            />
            <div
              style={{ width: "100px" }}
              dangerouslySetInnerHTML={{ __html: highlight(s.company_num) }}
            />
          </div>
        );
      }}
      inputProps={{
        placeholder: "회사명, 대표, 사업자번호 검색",
        value,
        onChange: (_, { newValue }) => {
          setValue(newValue);
        },
        className: "border w-full min-w-[249px] text-xs h-[24px] px-1", // ✅ 기존 input 스타일 그대로
        name: name,
      }}
    />
  );
}
