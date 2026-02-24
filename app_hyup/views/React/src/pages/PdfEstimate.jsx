import React from "react";
import {
  Page,
  Text,
  View,
  Document,
  StyleSheet,
  Font,
  Image,
  PDFViewer,
} from "@react-pdf/renderer";
import request, { STATIC_URL } from "../utils/request";
import { ENVIRONMENT, VAT_TYPE } from "../../constants";
import { numberToKorean } from "../utils/util";

// * localhost일 경우 public 폴더에 있는 폰트 파일을 사용
// * production일 경우 assets 폴더에 있는 폰트 파일을 사용
Font.register({
  family: "NotoSansKR",
  fonts: [
    {
      src:
        ENVIRONMENT === "development"
          ? "/fonts/NotoSansKR-Regular.ttf"
          : "/assets/app_hyup/fonts/NotoSansKR-Regular.ttf",
    },
    {
      src:
        ENVIRONMENT === "development"
          ? "/fonts/NotoSansKR-Bold.ttf"
          : "/assets/app_hyup/fonts/NotoSansKR-Bold.ttf",
      fontWeight: "bold",
    },
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
      color: "#000",
    },

    value: {
      marginLeft: 4,
    },

    sumBox: {
      flexDirection: "row",
      justifyContent: "space-between",
      borderWidth: 2,
      borderColor: "#000",
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
      backgroundColor: "#d9d9d9",
      color: "#000",
      borderColor: "#000",
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
      borderColor: "#000",
    },

    bottomHeader: {
      backgroundColor: "#d9d9d9",
      color: "#000",
      textAlign: "center",
    },
  });

  return styles;
};

const COL_WIDTHS = {
  no: 60, // 순번
  item: 340, // 도면번호/품명
  material: 100, // 소재
  qty: 100, // 수량
  unitMeasure: 100, // 단위
  unit: 120, // 단가
  supply: 140, // 공급가액
  tax: 140, // 세액
  memo: 120, // 비고
};

export default function PdfDocument({ id, subType }) {
  const [data, setData] = React.useState({});
  const [loading, setLoading] = React.useState(true);

  React.useEffect(() => {
    (async () => {
      const res = await request.get("get_estimate_detail", {
        params: {
          id: id,
        },
      });

      setData(res?.data || {});
      setLoading(false);
    })();
  }, [id]);

  const MyDocument = () => (
    <Document>
      {Array.isArray(data) === false ? (
        <PdfPage subType={subType} data={data} />
      ) : (
        data.map((item, index) => (
          <PdfPage key={index} subType={subType} data={item} />
        ))
      )}
    </Document>
  );

  const fileName = `${subType === "MC" ? "매출" : "매입"}_거래명세표_${
    new Date().toISOString().split("T")[0]
  }.pdf`;

  return (
    <div
      style={{
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        height: "100vh",
        fontSize: "18px",
      }}
    >
      {loading ? (
        <div>데이터 로딩 중...</div>
      ) : (
        // <PDFDownloadLink document={<MyDocument />} fileName={fileName}>
        //   {({ blob, url, loading, error }) => {
        //     if (loading) {
        //       return <div>PDF 생성 중...</div>;
        //     }
        //     if (error) {
        //       return (
        //         <div style={{ color: "red" }}>
        //           PDF 생성 오류: {error.message}
        //         </div>
        //       );
        //     }
        //     if (url) {
        //       setTimeout(() => {
        //         const link = document.createElement("a");
        //         link.href = url;
        //         link.download = fileName;
        //         link.click();
        //         setTimeout(() => {
        //           window.history.back();
        //         }, 500);
        //       }, 100);
        //     }
        //     return <div>PDF 다운로드 중...</div>;
        //   }}
        // </PDFDownloadLink>
        <PDFViewer style={{ width: "100%", height: "100%" }}>
          <MyDocument />
        </PDFViewer>
      )}
    </div>
  );
}

const title = {
  G: "견적서",
  S: "수주서",
  B: "발주서",
};

const PdfPage = ({ subType, data }) => {
  const styles = getStyles(subType);

  return (
    <Page size="A4" style={styles.page}>
      {/* ───────────────────── 제목 이미지 ───────────────────── */}
      <View style={styles.titleWrap}>
        <Image
          style={{ width: 100 }}
          src={`${STATIC_URL}/assets/app_hyup/images/logo.png`}
        />
        <Image
          style={{ width: 120 }}
          src={`${STATIC_URL}/assets/app_hyup/images/${title[subType]}.png`}
        />
        <View style={{ width: 120, opacity: 0 }} />
      </View>

      <View
        style={[
          styles.row,
          {
            borderLeftWidth: 2,
            borderRightWidth: 2,
            borderTopWidth: 2,
            borderBottomWidth: 1,
            borderColor: "#000",
          },
        ]}
      >
        {/* ───────────────────── 왼쪽 박스 ───────────────────── */}
        <View style={styles.leftBox}>
          {/* 일Vi자 / 등록번호 */}
          <View
            style={[
              styles.fieldRow,
              {
                flexDirection: "column",
                justifyContent: "center",
                alignItems: "center",
                padding: "10px 20px",
              },
            ]}
          >
            <Text
              style={{
                fontSize: 12,
                borderBottomWidth: 1,
                borderBottomColor: "#000",
                textAlign: "center",
              }}
            >
              {data?.partner_name} 귀하
            </Text>
          </View>

          <View style={{ padding: "0px 10px" }}>
            <View style={{ flexDirection: "row" }}>
              <Text
                style={{
                  ...styles.label,
                  textAlign: "left",
                }}
              >
                수주일자 :
              </Text>
              <Text style={styles.value}>{data.estimate_date}</Text>
            </View>
            <Text
              style={{
                ...styles.label,
                textAlign: "left",
                marginTop: 6,
                fontWeight: "bold",
              }}
            >
              아래와 같이 수주합니다.
            </Text>
          </View>

          {/* 합계금액 */}
          {/* <View style={styles.sumBox}>
            <Text style={{ color: "#000" }}>합계금액 :</Text>
            <Text>
              {data?.amount ? Number(data.amount).toLocaleString() : ""}
            </Text>
          </View> */}
        </View>

        {/* ───────────────────── 오른쪽 박스 ───────────────────── */}
        <View
          style={{
            padding: "0px 6px",
            backgroundColor: "#d9d9d9",
            borderLeftWidth: 1,
            borderRightWidth: 1,
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            borderLeftColor: "#000",
            borderRightColor: "#000",
            fontSize: 11,
            lineHeight: 1.7,
          }}
        >
          <Text>공</Text>
          <Text>급</Text>
          <Text>자</Text>
        </View>
        <View style={[styles.rightBox]}>
          <View>
            {/* 등록번호 */}
            <View
              style={[
                styles.tableRow,
                {
                  borderBottomWidth: 1,
                  borderBottomColor: "#000",
                },
              ]}
            >
              <View
                style={[
                  styles.cell,
                  {
                    width: "20%",
                    color: "#000",
                  },
                ]}
              >
                <Text
                  style={[
                    styles.cellCenter,
                    {
                      borderRightWidth: 1,
                      borderRightColor: "#000",
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
                  borderBottomColor: "#000",
                },
              ]}
            >
              <View
                style={[
                  styles.cell,
                  {
                    width: "20%",
                    color: "#000",
                  },
                ]}
              >
                <Text
                  style={[
                    styles.cellCenter,
                    {
                      borderRightWidth: 1,
                      borderRightColor: "#000",
                    },
                  ]}
                >
                  상&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;호
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
                    color: "#000",
                  },
                ]}
              >
                <Text
                  style={[
                    styles.cellCenter,
                    {
                      borderLeftWidth: 1,
                      borderRightWidth: 1,
                      borderColor: "#000",
                    },
                  ]}
                >
                  성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명
                </Text>
              </View>

              <View style={[styles.cell, { width: "20%" }]}>
                <View style={styles.stampWrap}>
                  <Text>전용준</Text>
                  <Text
                    style={{
                      fontSize: 8,
                      marginLeft: 4,
                      color: "#000",
                    }}
                  >
                    (인)
                  </Text>
                  <Image
                    style={{
                      width: 25,
                      height: 25,
                      position: "absolute",
                      right: 0,
                    }}
                    src={`${STATIC_URL}/assets/app_hyup/images/stamp.png`}
                  />
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
                    color: "#000",
                    borderBottomColor: "#000",
                  },
                ]}
              >
                <Text style={[styles.cellCenter]}>
                  주&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;소
                </Text>
              </View>

              <View
                style={[
                  styles.cell,
                  {
                    width: "82%",
                    borderLeftWidth: 1,
                    borderBottomWidth: 1,
                    borderBottomColor: "#000",
                    borderLeftColor: "#000",
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
                    color: "#000",
                    borderColor: "#000",
                  },
                ]}
              >
                <Text style={styles.cellCenter}>
                  업&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;태
                </Text>
              </View>
              <View
                style={[
                  styles.cell,
                  {
                    width: "40%",
                    borderBottomWidth: 1,
                    borderRightWidth: 1,
                    borderColor: "#000",
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
                    color: "#000",
                    borderColor: "#000",
                    borderRightWidth: 1,
                  },
                ]}
              >
                <Text style={styles.cellCenter}>
                  종&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목
                </Text>
              </View>
              <View
                style={[
                  styles.cell,
                  {
                    width: "50%",
                    borderBottomWidth: 1,
                    borderBottomColor: "#000",
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
                    color: "#000",
                    borderColor: "#000",
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
                    borderColor: "#000",
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
                    color: "#000",
                    borderColor: "#000",
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
                    borderBottomColor: "#000",
                  },
                ]}
              >
                <Text>041-1111-1111</Text>
              </View>
            </View>
          </View>
        </View>
      </View>

      <View
        style={{
          borderLeftWidth: 2,
          borderRightWidth: 2,
          borderBottomWidth: 2,
          padding: "4px 10px",
          borderColor: "#000",
        }}
      >
        <Text>
          합계금액 : 일금&nbsp;
          <Text style={{ fontWeight: "semibold" }}>
            {numberToKorean(data?.supply_amount)} ￦
            {Number(data?.supply_amount).toLocaleString()}원 (
            {VAT_TYPE[data?.vat_type]})
          </Text>
        </Text>
      </View>

      {/* ───────────────────── 하단 합계 테이블 ───────────────────── */}
      <View
        style={{
          borderWidth: 2,
          borderColor: "#000",
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
                  { width: COL_WIDTHS.no },
                ]}
              >
                순번
              </Text>
              <Text
                style={[
                  styles.cell,
                  styles.headerCell,
                  { width: COL_WIDTHS.item },
                ]}
              >
                도면번호/품명
              </Text>
              <Text
                style={[
                  styles.cell,
                  styles.headerCell,
                  { width: COL_WIDTHS.material },
                ]}
              >
                소재
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
                  { width: COL_WIDTHS.unitMeasure },
                ]}
              >
                단위
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
                금액
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
                    borderBottomColor: "#000",
                  },
                ]}
              >
                <Text
                  style={[
                    styles.itemCell,
                    styles.centerAlign,
                    { width: COL_WIDTHS.no },
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
                    { width: COL_WIDTHS.material },
                  ]}
                >
                  {row[2]}
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
                    { width: COL_WIDTHS.unitMeasure },
                  ]}
                >
                  {row[4]}
                </Text>

                <Text
                  style={[
                    styles.itemCell,
                    styles.rightAlign,
                    { width: COL_WIDTHS.unit },
                  ]}
                >
                  {row[5].toLocaleString()}
                </Text>

                <Text
                  style={[
                    styles.itemCell,
                    styles.rightAlign,
                    { width: COL_WIDTHS.supply },
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
                borderBottomColor: "#000",
              },
            ]}
          >
            <Text
              style={[
                styles.bottomCell,
                {
                  width:
                    COL_WIDTHS.no +
                    COL_WIDTHS.item +
                    COL_WIDTHS.material +
                    COL_WIDTHS.qty,
                  borderRightWidth: 1,
                  borderColor: "#000",
                },
              ]}
            ></Text>

            <Text
              style={[
                styles.bottomCell,
                styles.bottomHeader,
                {
                  width: COL_WIDTHS.unitMeasure + COL_WIDTHS.unit,
                  borderRightWidth: 1,
                  borderColor: "#000",
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
                  borderColor: "#000",
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
                { width: COL_WIDTHS.memo },
              ]}
            ></Text>
          </View>
        </View>
      </View>
    </Page>
  );
};
