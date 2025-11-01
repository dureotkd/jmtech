import ky from "ky";

const isLocal =
  typeof window !== "undefined" &&
  (window.location.hostname === "localhost" ||
    window.location.hostname === "127.0.0.1");

const BACKEND_URL = isLocal
  ? "https://jmtech.test/api"
  : "https://api.infoverse.club/api";

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
      console.log("🚀 Debug: ~ error:", error);
    }
  },

  post: async (url, body) => {
    try {
      const res = await base.post(url, { body: body }).json();
      return res;
    } catch (error) {
      console.log("🚀 Debug: ~ error:", error);
    }
  },

  put: async (url, body, opts = {}) => {
    return base.put(url, { json: body, ...opts }).json();
  },

  delete: async (url, opts = {}) => {
    return base.delete(url, { ...opts }).json();
  },
};

export { BACKEND_URL };
export default request;
