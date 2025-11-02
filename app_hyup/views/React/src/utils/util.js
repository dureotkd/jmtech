const wait = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

const deepClone = (obj) => {
  return JSON.parse(JSON.stringify(obj));
};

const empty = (value) => {
  if (value === null || value === undefined) return true;
  if (typeof value === "string" && value.trim() === "") return true;
  if (Array.isArray(value) && value.length === 0) return true;
  if (typeof value === "object" && Object.keys(value).length === 0) return true;
  return false;
};

const serializeForm = (formData) => {
  const obj = {};
  for (const [key, value] of formData.entries()) {
    obj[key] = value;
  }
  return obj;
};

const numberToKorean = (number) => {
  number = parseInt(number, 10);
  if (number === 0) return "영원정";

  const unit1 = ["", "만", "억", "조", "경"];
  const unit2 = ["", "십", "백", "천"];
  const numChar = ["", "일", "이", "삼", "사", "오", "육", "칠", "팔", "구"];

  let result = "";
  let pos = 0;

  while (number > 0) {
    const part = number % 10000;
    number = Math.floor(number / 10000);

    let subResult = "";
    const digits = part.toString().padStart(4, "0");

    for (let i = 0; i < 4; i++) {
      const n = parseInt(digits[i]);
      if (n > 0) {
        subResult += numChar[n] + unit2[3 - i];
      }
    }

    if (subResult !== "") {
      result = subResult + unit1[pos] + result;
    }

    pos++;
  }

  return result + " 원정";
};

export { wait, deepClone, empty, serializeForm, numberToKorean };
