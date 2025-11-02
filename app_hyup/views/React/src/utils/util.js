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

export { wait, deepClone, empty, serializeForm };
