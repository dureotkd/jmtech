import request from "../utils/request";

const estimateApi = {
  저장된엑셀템플릿: async (params) => {
    const res = await request.get("load_saved_excel_template", { params });

    return res;
  },

  엑셀불러오기: async (formData) => {
    const res = await request.post("estimate_excel_load", formData);

    return res;
  },

  견적서초기엑셀템플릿: async (params = {}) => {
    const res = await request.get("load_excel_template", { params });

    return res;
  },

  초기엑셀템플릿: async (subType) => {
    const res = await request.get("load_excel_template_v3", {
      params: { sub_type: subType },
    });

    return res;
  },

  거래처목록: async () => {
    const res = await request.get("get_partner_list");
    return res;
  },

  품목자동완성: async (params) => {
    const res = await request.get("get_estimate_item_suggestions", { params });
    return res;
  },

  견적저장: async (formData) => {
    const res = await request.post("save_estimate", formData);

    return res;
  },

  견적서일괄등록품목양식다운로드: async (subType) => {
    const res = await request.getBlob("download_bulk_estimate_item_template", {
      responseType: "blob",
      params: {
        sub_type: subType,
      },
    });

    console.log(res);

    // Blob 생성
    const blob = new Blob([res], {
      type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    });

    // 다운로드 처리
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "일괄등록_양식.xls";
    a.click();

    return res;
  },
};

export default estimateApi;
