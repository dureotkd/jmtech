import ky from "ky";

const isLocal =
  (typeof window !== "undefined" &&
    (window.location.hostname === "localhost" ||
      window.location.hostname === "127.0.0.1")) ||
  window.location.hostname.includes("jmtech.test");

const BACKEND_URL = isLocal
  ? "https://jmtech.test/api"
  : "https://api.infoverse.club/api";

const STATIC_URL = isLocal
  ? "https://jmtech.test"
  : "https://api.infoverse.club";

const base = ky.create({
  prefixUrl: BACKEND_URL,
  credentials: "include",
});

const request = {
  get: async (url, { params } = {}) => {
    try {
      const searchParams = params ? new URLSearchParams(params).toString() : "";
      const fullUrl = searchParams ? `${url}?${searchParams}` : url;
      return await base.get(fullUrl).json();
    } catch (error) {
      throw new Error(error);
    }
  },

  post: async (url, body) => {
    try {
      const res = await base.post(url, { body: body, timeout: false }).json();
      return res;
    } catch (error) {
      throw new Error(error);
    }
  },
};

export { BACKEND_URL, STATIC_URL };
export default request;
