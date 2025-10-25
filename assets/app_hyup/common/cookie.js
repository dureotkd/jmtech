/**
 * 쿠키 설정
 * @param {string} name - 쿠키 이름
 * @param {string} value - 저장할 값
 * @param {number} days - 만료일 (일 수)
 * @param {string} path - 적용 경로 (기본 '/')
 */
function setCookie(name, value, days = 7, path = "/") {
  const expires = new Date();
  expires.setDate(expires.getDate() + days);
  document.cookie = `${encodeURIComponent(name)}=${encodeURIComponent(
    value
  )}; expires=${expires.toUTCString()}; path=${path}`;
}

/**
 * 쿠키 가져오기
 * @param {string} name - 쿠키 이름
 * @returns {string|null} - 쿠키 값 또는 null
 */
function getCookie(name) {
  const nameEQ = encodeURIComponent(name) + "=";
  const cookies = document.cookie.split("; ");
  for (let c of cookies) {
    if (c.startsWith(nameEQ)) {
      return decodeURIComponent(c.substring(nameEQ.length));
    }
  }
  return null;
}

/**
 * 쿠키 삭제
 * @param {string} name - 쿠키 이름
 * @param {string} path - 삭제 경로
 */
function deleteCookie(name) {
  document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:01 GMT;";
}

//bluemint.tistory.com/6 [BLUEMINT:티스토리]

/**
 * 모든 쿠키 조회
 * @returns {Object} - 쿠키 객체 { key: value }
 */
출처: https: function getAllCookies() {
  const cookies = document.cookie.split("; ");
  const result = {};
  cookies.forEach((c) => {
    const [key, value] = c.split("=");
    if (key && value)
      result[decodeURIComponent(key)] = decodeURIComponent(value);
  });
  return result;
}

/**
 * 쿠키 존재 여부 확인
 * @param {string} name
 * @returns {boolean}
 */
function hasCookie(name) {
  return getCookie(name) !== null;
}
