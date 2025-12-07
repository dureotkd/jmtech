import request from "../utils/request";

const purchaseApi = {
  거래내역불러오기: async (company_name) => {
    const res = await request.get("get_trade_history", {
      params: { company_name },
    });

    return res;
  },
  저장된엑셀템플릿: async (params) => {
    const res = await request.get("load_saved_excel_template", { params });

    return res;
  },

  엑셀불러오기: async (formData) => {
    const res = await request.post("estimate_excel_load", formData);

    return res;
  },

  초기엑셀템플릿: async (subType) => {
    const res = await request.get("load_excel_template_v2", {
      params: { sub_type: subType },
    });

    return res;
  },

  거래처목록: async () => {
    const res = await request.get("get_partner_list");
    return res;
  },

  명세표저장: async (formData) => {
    const res = await request.post("save_statement", formData);

    return res;
  },
};

export default purchaseApi;
