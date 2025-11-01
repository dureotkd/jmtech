import request from "../utils/request";

const estimateApi = {
  엑셀불러오기: async (formData) => {
    const res = await request.post("estimate_excel_load", formData);

    return res;
  },

  초기엑셀템플릿: async () => {
    const res = await request.get("load_excel_template");

    return res;
  },

  거래처목록: async () => {
    const res = await request.get("get_partner_list");
    return res;
  },
};

export default estimateApi;
