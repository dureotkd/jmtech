import React, { useMemo } from "react";
import Select, { components } from "react-select";

const CompanyAutocomplete = ({
  options, // 원본 데이터: 예: [{ value:…, label:…, company_num:…, ceo_name:… }, …]
  onChange, // 선택 시 호출되는 함수
  maxResults = 30, // 최대 표시 개수
  placeholder = "회사명/대표/계정번호 검색...",
}) => {
  const filterOptions = (inputValue) => {
    const term = inputValue.trim().toLowerCase();
    if (!term) return [];

    return options
      .filter(
        (item) =>
          item.label.toLowerCase().includes(term) ||
          item.company_num.toLowerCase().includes(term) ||
          item.ceo_name.toLowerCase().includes(term)
      )
      .slice(0, maxResults);
  };

  // 하이라이트 렌더링 위한 커스텀 컴포넌트
  const HighlightedOption = (props) => {
    const { data, innerRef, innerProps } = props;
    const inputValue = props.selectProps.inputValue || "";
    const term = inputValue.trim().toLowerCase();

    const highlight = (text) => {
      if (!term) return text;
      const regex = new RegExp(
        `(${term.replace(/[.*+?^${}()|[\]\\]/g, "\\$&")})`,
        "gi"
      );
      return text.split(regex).map((part, i) =>
        regex.test(part) ? (
          <span key={i} className="highlight">
            {part}
          </span>
        ) : (
          part
        )
      );
    };

    return (
      <div ref={innerRef} {...innerProps} className="select-option-item">
        <div className="item-name">{highlight(data.label)}</div>
        <div className="item-person">{highlight(data.ceo_name)}</div>
        <div className="item-account">{highlight(data.company_num)}</div>
      </div>
    );
  };

  return (
    <Select
      options={options}
      filterOption={() => true} // 기본 필터 끄고 커스텀 필터 사용
      onInputChange={() => {}} // 입력 변수로만 필터 작동
      onChange={onChange}
      placeholder={placeholder}
      components={{ Option: HighlightedOption }}
      styles={{
        option: (base) => ({ ...base, padding: "8px 12px" }),
      }}
      // Async 필터링을 위해 loadOptions처럼 사용
      menuIsOpen={undefined}
      styles={{
        control: (base, state) => ({
          ...base,
          borderColor: state.isFocused ? "#4b8edc" : "#ccc",
          boxShadow: "none",
          "&:hover": { borderColor: "#4b8edc" },
          minHeight: "36px",
        }),
        option: (base, state) => ({
          ...base,
          backgroundColor: state.isFocused
            ? "#bdbdbd"
            : state.isSelected
            ? "#4b8edc"
            : "transparent",
          color: state.isSelected ? "#fff" : "#111",
          padding: "6px 10px",
          fontSize: "13px",
          cursor: "pointer",
        }),
        placeholder: (base) => ({
          ...base,
          color: "#999",
          fontSize: "13px",
        }),
      }}
      // 실제 구현에선 loadOptions 혹은 options 상태 관리 필요
    />
  );
};

export default CompanyAutocomplete;
