import React from "react";
import {
  Page,
  Text,
  View,
  Document,
  StyleSheet,
  PDFViewer,
  Font,
  Image,
} from "@react-pdf/renderer";
import { ESTIMATE_SUB_TYPE } from "../../constants";
import request from "../utils/request";

// 폰트 등록
Font.register({
  family: "NotoSansKR",
  fonts: [
    { src: "/fonts/NotoSansKR-Regular.ttf" },
    { src: "/fonts/NotoSansKR-Bold.ttf", fontWeight: "bold" },
  ],
});

const getStyles = (subType) => {
  const styles = StyleSheet.create({
    page: {
      fontFamily: "NotoSansKR",
      padding: 20,
      fontSize: 9,
    },

    // 제목 이미지 영역
    titleWrap: {
      width: "100%",
      alignItems: "center",
      justifyContent: "space-between",
      flexDirection: "row",
      gap: 10,
      marginBottom: 10,
    },

    // 좌/우 전체 영역
    row: {
      flexDirection: "row",
      width: "100%",
    },

    cell: {
      paddingLeft: 2,
      flexGrow: 1,
      justifyContent: "center",
    },

    leftBox: {
      width: "50%",
      paddingRight: 10,
    },

    rightBox: {
      width: "50%",
    },

    fieldRow: {
      flexDirection: "row",
      marginBottom: 3,
    },

    label: {
      // width: 0,
      color: subType === "MI" ? "#1E40AF" : "#ff0000",
    },

    value: {
      marginLeft: 4,
    },

    sumBox: {
      flexDirection: "row",
      justifyContent: "space-between",
      borderWidth: 2,
      borderColor: subType === "MI" ? "#1E40AF" : "#ff0000",
      fontWeight: "semibold",
      fontSize: 12,
      paddingLeft: 4,
      paddingRight: 4,
    },

    // 공급자 테이블
    table: {
      width: "100%",
      borderStyle: "solid",
      borderCollapse: "collapse",
    },

    tableRow: {
      flexDirection: "row",
    },

    cellCenter: {
      textAlign: "center",
    },

    stampWrap: {
      flexDirection: "row",
      alignItems: "center",
      position: "relative",
    },

    stampImg: {
      width: 50,
      height: 50,
      position: "absolute",
      left: 40,
      top: -10,
    },

    headerCell: {
      backgroundColor: subType === "MI" ? "#e5f0ff" : "#ffe5e5",
      color: subType === "MI" ? "#1E40AF" : "#ff0000",
      borderColor: subType === "MI" ? "blue" : "#e11d48",
      textAlign: "center",
      borderRightWidth: 1,
      borderBottomWidth: 1,
    },

    rightAlign: {
      textAlign: "right",
      paddingRight: 2,
    },

    leftAlign: {
      textAlign: "left",
      paddingLeft: 2,
    },

    centerAlign: {
      textAlign: "center",
    },

    itemCell: {
      borderRightWidth: 1,
      borderColor: subType === "MI" ? "blue" : "#e11d48",
    },

    bottomHeader: {
      backgroundColor: subType === "MI" ? "#e5f0ff" : "#ffe5e5",
      color: subType === "MI" ? "#1E40AF" : "#ff0000",
      textAlign: "center",
    },
  });

  return styles;
};

const COL_WIDTHS = {
  date: 100, // 월일
  item: 300, // 품목
  qty: 100, // 수량
  unit: 120, // 단가
  supply: 140, // 공급가액
  tax: 140, // 세액
  memo: 120, // 비고
};

const items = [
  {
    date: "2025-01-02",
    item: "상품A",
    qty: "10",
    unit_price: "50,000",
    supply_amount: "500,000",
    tax_amount: "50,000",
    memo: "비고1",
  },
];

export default function PdfDocument({ id, subType }) {
  const [data, setData] = React.useState({});
  React.useEffect(() => {
    (async () => {
      const res = await request.get("get_statement_detail", {
        params: {
          id: id,
        },
      });

      setData(res?.data || {});
    })();
  }, []);

  return (
    <PDFViewer width="100%" height="850">
      <Document>
        {Array.isArray(data) === false ? (
          <PdfPage subType={subType} data={data} />
        ) : (
          data.map((item, index) => (
            <PdfPage key={index} subType={subType} data={item} />
          ))
        )}
      </Document>
    </PDFViewer>
  );
}

const PdfPage = ({ subType, data }) => {
  const styles = getStyles(subType);

  return (
    <Page size="A4" style={styles.page}>
      {/* ───────────────────── 제목 이미지 ───────────────────── */}
      <View style={styles.titleWrap}>
        <Image
          style={{ width: 100 }}
          src="http://jmtech.test/assets/app_hyup/images/logo.png"
        />
        <Image
          style={{ width: 120 }}
          src={`http://jmtech.test/assets/app_hyup/images/${
            subType === "MC" ? "매출" : "매입"
          } 거래명세표.png`}
        />
        <View style={{ width: 120, opacity: 0 }} />
      </View>

      <View style={styles.row}>
        {/* ───────────────────── 왼쪽 박스 ───────────────────── */}
        <View style={styles.leftBox}>
          {/* 일Vi자 / 등록번호 */}
          <View style={styles.fieldRow}>
            <View style={{ flexDirection: "row" }}>
              <Text
                style={{
                  ...styles.label,
                }}
              >
                일&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;자
                :
              </Text>
              <Text style={styles.value}>{data.estimate_date}</Text>
            </View>

            <View style={{ flexDirection: "row", marginLeft: 20 }}>
              <Text
                style={{
                  ...styles.label,
                  marginLeft: 10,
                }}
              >
                등&nbsp;&nbsp;록&nbsp;&nbsp;번&nbsp;&nbsp;호 :
              </Text>
              <Text style={styles.value}>{data.partner_company_num}</Text>
            </View>
          </View>

          {/* 거래처 */}
          <View style={styles.fieldRow}>
            <Text style={styles.label}>
              거&nbsp;&nbsp;&nbsp;&nbsp;래&nbsp;&nbsp;&nbsp;&nbsp;처 :
            </Text>
            <Text style={styles.value}>{data.partner_name}</Text>
          </View>

          {/* 주소 */}
          <View style={styles.fieldRow}>
            <Text style={styles.label}>
              주&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;소
              :
            </Text>
            <Text style={styles.value}>{data.partner_address}</Text>
          </View>

          {/* 전화 / 팩스 */}
          <View style={styles.fieldRow}>
            <View style={{ flexDirection: "row" }}>
              <Text style={styles.label}>전&nbsp;화&nbsp;번&nbsp;호 :</Text>
              <Text style={[styles.value, { width: 62 }]}>
                {data.phone_number}
              </Text>
            </View>

            <View style={{ flexDirection: "row", marginLeft: 15 }}>
              <Text style={styles.label}>
                팩&nbsp;&nbsp;스&nbsp;&nbsp;번&nbsp;&nbsp;호 :
              </Text>
              <Text style={styles.value}>{data.fax_number}</Text>
            </View>
          </View>

          {/* 합계금액 */}
          <View style={styles.sumBox}>
            <Text style={{ color: subType === "MI" ? "#1E40AF" : "#ff0000" }}>
              합계금액 :
            </Text>
            <Text>
              {data?.amount ? Number(data.amount).toLocaleString() : ""}
            </Text>
          </View>
        </View>

        {/* ───────────────────── 오른쪽 박스 ───────────────────── */}
        <View style={[styles.rightBox]}>
          <View
            style={{
              borderWidth: 2,
              borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
            }}
          >
            {/* 등록번호 */}
            <View
              style={[
                styles.tableRow,
                {
                  borderBottomWidth: 1,
                  borderBottomColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                },
              ]}
            >
              <View
                style={[
                  styles.cell,
                  {
                    width: "20%",
                    color: subType === "MI" ? "#1E40AF" : "#ff0000",
                  },
                ]}
              >
                <Text
                  style={[
                    styles.cellCenter,
                    {
                      borderRightWidth: 1,
                      borderRightColor:
                        subType === "MI" ? "#1E40AF" : "#e11d48",
                    },
                  ]}
                >
                  등록번호
                </Text>
              </View>
              <View style={[styles.cell, { width: "80%" }]}>
                <Text>312-86-30100</Text>
              </View>
            </View>

            {/* 상호 / 성명 */}
            <View
              style={[
                styles.tableRow,
                {
                  borderBottomWidth: 1,
                  borderBottomColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                },
              ]}
            >
              <View
                style={[
                  styles.cell,
                  {
                    width: "20%",
                    color: subType === "MI" ? "#1E40AF" : "#ff0000",
                  },
                ]}
              >
                <Text
                  style={[
                    styles.cellCenter,
                    {
                      borderRightWidth: 1,
                      borderRightColor:
                        subType === "MI" ? "#1E40AF" : "#e11d48",
                    },
                  ]}
                >
                  상 호
                </Text>
              </View>
              <View style={[styles.cell, { width: "40%" }]}>
                <Text>제이엠테크</Text>
              </View>

              <View
                style={[
                  styles.cell,
                  {
                    width: "20%",
                    color: subType === "MI" ? "#1E40AF" : "#ff0000",
                  },
                ]}
              >
                <Text
                  style={[
                    styles.cellCenter,
                    {
                      borderLeftWidth: 1,
                      borderRightWidth: 1,
                      borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                    },
                  ]}
                >
                  성 명
                </Text>
              </View>

              <View style={[styles.cell, { width: "20%" }]}>
                <View style={styles.stampWrap}>
                  <Text>전용준</Text>
                  <Text
                    style={{
                      fontSize: 8,
                      marginLeft: 4,
                      color: subType === "MI" ? "#1E40AF" : "#ff0000",
                    }}
                  >
                    (인)
                  </Text>
                </View>
              </View>
            </View>

            {/* 주소 */}
            <View style={styles.tableRow}>
              <View
                style={[
                  styles.cell,
                  {
                    width: "20%",
                    borderBottomWidth: 1,
                    color: subType === "MI" ? "#1E40AF" : "#ff0000",
                    borderBottomColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                  },
                ]}
              >
                <Text style={[styles.cellCenter]}>주소</Text>
              </View>

              <View
                style={[
                  styles.cell,
                  {
                    width: "82%",
                    borderLeftWidth: 1,
                    borderBottomWidth: 1,
                    borderBottomColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                    borderLeftColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                  },
                ]}
              >
                <Text>
                  충청남도 천안시 서북구 두정공단1길 149-2 (두 정동, 미라클(주))
                  제이엠테크
                </Text>
              </View>
            </View>

            {/* 업태 / 종목 */}
            <View style={styles.tableRow}>
              <View
                style={[
                  styles.cell,
                  {
                    width: "27.5%",
                    borderBottomWidth: 1,
                    borderRightWidth: 1,
                    color: subType === "MI" ? "#1E40AF" : "#ff0000",
                    borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                  },
                ]}
              >
                <Text style={styles.cellCenter}>업태</Text>
              </View>
              <View
                style={[
                  styles.cell,
                  {
                    width: "40%",
                    borderBottomWidth: 1,
                    borderRightWidth: 1,
                    borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                  },
                ]}
              >
                <Text>제조업</Text>
              </View>

              <View
                style={[
                  styles.cell,
                  {
                    width: "20%",
                    borderBottomWidth: 1,
                    color: subType === "MI" ? "#1E40AF" : "#ff0000",
                    borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                    borderRightWidth: 1,
                  },
                ]}
              >
                <Text style={styles.cellCenter}>종목</Text>
              </View>
              <View
                style={[
                  styles.cell,
                  {
                    width: "50%",
                    borderBottomWidth: 1,
                    borderBottomColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                  },
                ]}
              >
                <Text>산업기계 설계 및 개발</Text>
              </View>
            </View>

            {/* 전화 / 팩스 */}
            <View style={styles.tableRow}>
              <View
                style={[
                  styles.cell,
                  {
                    width: "27.5%",
                    borderRightWidth: 1,
                    color: subType === "MI" ? "#1E40AF" : "#ff0000",
                    borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                  },
                ]}
              >
                <Text style={styles.cellCenter}>전화번호</Text>
              </View>
              <View
                style={[
                  styles.cell,
                  {
                    width: "40%",
                    borderRightWidth: 1,
                    borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                  },
                ]}
              >
                <Text>041-483-1111</Text>
              </View>

              <View
                style={[
                  styles.cell,
                  {
                    width: "20%",
                    borderRightWidth: 1,
                    color: subType === "MI" ? "#1E40AF" : "#ff0000",
                    borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                  },
                ]}
              >
                <Text style={styles.cellCenter}>팩스번호</Text>
              </View>
              <View
                style={[
                  styles.cell,
                  {
                    width: "50%",
                    borderBottomColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                  },
                ]}
              >
                <Text>041-1111-1111</Text>
              </View>
            </View>
          </View>
        </View>
      </View>

      {/* ───────────────────── 하단 합계 테이블 ───────────────────── */}
      <View
        style={{
          borderWidth: 2,
          borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
          marginTop: 5,
        }}
      >
        <View>
          {/* ───────────────────── 상단 아이템 테이블 ───────────────────── */}
          <View style={styles.table}>
            {/* Header */}
            <View style={[styles.row]}>
              <Text
                style={[
                  styles.cell,
                  styles.headerCell,
                  styles.centerAlign,
                  { width: COL_WIDTHS.date },
                ]}
              >
                월일
              </Text>
              <Text
                style={[
                  styles.cell,
                  styles.headerCell,
                  { width: COL_WIDTHS.item },
                ]}
              >
                품목
              </Text>
              <Text
                style={[
                  styles.cell,
                  styles.headerCell,
                  { width: COL_WIDTHS.qty },
                ]}
              >
                수량
              </Text>
              <Text
                style={[
                  styles.cell,
                  styles.headerCell,
                  { width: COL_WIDTHS.unit },
                ]}
              >
                단가
              </Text>
              <Text
                style={[
                  styles.cell,
                  styles.headerCell,
                  { width: COL_WIDTHS.supply },
                ]}
              >
                공급가액
              </Text>
              <Text
                style={[
                  styles.cell,
                  styles.headerCell,
                  { width: COL_WIDTHS.tax },
                ]}
              >
                세액
              </Text>
              <Text
                style={[
                  styles.cell,
                  styles.headerCell,
                  { width: COL_WIDTHS.memo, borderRightWidth: 0 },
                ]}
              >
                비고
              </Text>
            </View>

            {/* Items */}
            {data?.sheets?.map((row, idx) => (
              <View
                key={idx}
                style={[
                  styles.row,
                  {
                    borderBottomWidth: 1,
                    borderBottomColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                  },
                ]}
              >
                <Text
                  style={[
                    styles.itemCell,
                    styles.centerAlign,
                    { width: COL_WIDTHS.date },
                  ]}
                >
                  {row[0]}
                </Text>

                <Text
                  style={[
                    styles.itemCell,
                    styles.leftAlign,
                    { width: COL_WIDTHS.item },
                  ]}
                >
                  {row[1]}
                </Text>

                <Text
                  style={[
                    styles.itemCell,
                    styles.rightAlign,
                    { width: COL_WIDTHS.qty },
                  ]}
                >
                  {row[3]}
                </Text>

                <Text
                  style={[
                    styles.itemCell,
                    styles.rightAlign,
                    { width: COL_WIDTHS.unit },
                  ]}
                >
                  {row[4].toLocaleString()}
                </Text>

                <Text
                  style={[
                    styles.itemCell,
                    styles.rightAlign,
                    { width: COL_WIDTHS.supply },
                  ]}
                >
                  {row[5].toLocaleString()}
                </Text>

                <Text
                  style={[
                    styles.itemCell,
                    styles.rightAlign,
                    { width: COL_WIDTHS.tax },
                  ]}
                >
                  {row[6].toLocaleString()}
                </Text>

                <Text
                  style={[
                    styles.itemCell,
                    { width: COL_WIDTHS.memo, borderRightWidth: 0 },
                  ]}
                ></Text>
              </View>
            ))}
          </View>
        </View>
        <View>
          {/* 전미수잔액 라인 */}
          <View
            style={[
              styles.row,
              {
                borderBottomWidth: 1,
                borderBottomColor: subType === "MI" ? "#1E40AF" : "#e11d48",
              },
            ]}
          >
            <Text
              style={[
                styles.bottomHeader,
                {
                  width: 140,
                  borderRightWidth: 1,
                  borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                },
              ]}
            >
              전미수잔액
            </Text>

            <Text
              style={[
                styles.bottomCell,
                {
                  width: 260,
                  borderRightWidth: 1,
                  borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                },
              ]}
            ></Text>

            <Text
              style={[
                styles.bottomCell,
                styles.bottomHeader,
                {
                  width: 220,
                  borderRightWidth: 1,
                  borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                },
              ]}
            >
              합계
            </Text>

            <Text
              style={[
                styles.bottomCell,
                styles.rightAlign,
                {
                  width: COL_WIDTHS.supply,
                  borderRightWidth: 1,
                  borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                },
              ]}
            >
              {data?.supply_amount
                ? Number(data.supply_amount).toLocaleString()
                : ""}
            </Text>

            <Text
              style={[
                styles.bottomCell,
                styles.rightAlign,
                {
                  width: COL_WIDTHS.tax,
                  borderRightWidth: 1,
                  borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                },
              ]}
            >
              {data?.tax_amount ? Number(data.tax_amount).toLocaleString() : ""}
            </Text>

            <Text
              style={[
                styles.bottomCell,
                styles.rightAlign,
                { width: COL_WIDTHS.memo },
              ]}
            ></Text>
          </View>
        </View>
        <View>
          {/* 총합계 라인 */}
          <View style={styles.row}>
            <Text
              style={[
                styles.bottomCell,
                styles.bottomHeader,
                {
                  width: 140,
                  borderRightWidth: 1,
                  borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                },
              ]}
            >
              총합계
            </Text>

            <Text
              style={[
                styles.bottomCell,
                styles.rightAlign,
                {
                  width: 130,
                  borderRightWidth: 1,
                  borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                },
              ]}
            >
              913,000
            </Text>

            <Text
              style={[
                styles.bottomCell,
                styles.bottomHeader,
                {
                  width: 130,
                  borderRightWidth: 1,
                  borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                },
              ]}
            >
              입금액
            </Text>

            <Text
              style={[
                styles.bottomCell,
                styles.rightAlign,
                {
                  width: 110,
                  borderRightWidth: 1,
                  borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                },
              ]}
            ></Text>

            <Text
              style={[
                styles.bottomCell,
                styles.centerAlign,
                {
                  width: 110,
                  borderRightWidth: 1,
                  borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                  backgroundColor: subType === "MI" ? "#e5f0ff" : "#ffe5e5",
                  color: subType === "MI" ? "#1E40AF" : "#ff0000",
                },
              ]}
            >
              총미수잔액
            </Text>
            <Text
              style={[
                styles.bottomCell,
                styles.rightAlign,
                {
                  width: COL_WIDTHS.supply,
                  borderRightWidth: 1,
                  borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                },
              ]}
            ></Text>
            <Text
              style={[
                styles.bottomCell,
                styles.centerAlign,

                {
                  width: COL_WIDTHS.tax,
                  borderRightWidth: 1,
                  borderColor: subType === "MI" ? "#1E40AF" : "#e11d48",
                  backgroundColor: subType === "MI" ? "#e5f0ff" : "#ffe5e5",
                  color: subType === "MI" ? "#1E40AF" : "#ff0000",
                },
              ]}
            >
              인수자
            </Text>
            <Text
              style={[
                styles.bottomCell,
                styles.rightAlign,
                { width: COL_WIDTHS.memo },
              ]}
            ></Text>
          </View>
        </View>
      </View>
    </Page>
  );
};
