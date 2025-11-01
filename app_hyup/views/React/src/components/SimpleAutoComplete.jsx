import React, { useState } from "react";
import Autosuggest from "react-autosuggest";

export default function SimpleAutocomplete({ data }) {
  const [value, setValue] = useState("");
  const [suggestions, setSuggestions] = useState([]);

  const getSuggestions = (input) => {
    const term = input.trim().toLowerCase();
    if (!term) return [];
    return data
      .filter(
        (c) =>
          c.company_name.toLowerCase().includes(term) ||
          c.ceo_name.toLowerCase().includes(term) ||
          c.company_num.toLowerCase().includes(term)
      )
      .slice(0, 30);
  };

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
        console.log("선택:", suggestion);
      }}
      renderSuggestion={(s, { query }) => {
        const highlight = (text) =>
          text.replace(
            new RegExp(query, "gi"),
            (match) => `<span class='highlight'>${match}</span>`
          );
        return (
          <div
            className="flex justify-between px-2 py-1 text-sm"
            dangerouslySetInnerHTML={{
              __html: `
                <div style="width:200px;">${highlight(s.company_name)}</div>
                <div style="width:60px;">${highlight(s.ceo_name)}</div>
                <div style="width:100px;">${highlight(s.company_num)}</div>
              `,
            }}
          />
        );
      }}
      inputProps={{
        placeholder: "회사명, 대표, 사업자번호 검색",
        value,
        onChange: (_, { newValue }) => setValue(newValue),
        className: "border w-full min-w-[249px] text-xs h-[24px] px-1", // ✅ 기존 input 스타일 그대로
        name: "faxNumber",
      }}
    />
  );
}
