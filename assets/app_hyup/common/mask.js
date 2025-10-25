function phoneNumberMask(el) {
  let number = el.value.replace(/[^0-9]/g, ""); // 숫자만 추출

  if (number.length < 4) {
    el.value = number;
  } else if (number.length < 7) {
    el.value = number.slice(0, 3) + "-" + number.slice(3);
  } else if (number.length < 11) {
    el.value =
      number.slice(0, 3) + "-" + number.slice(3, 6) + "-" + number.slice(6);
  } else {
    el.value =
      number.slice(0, 3) + "-" + number.slice(3, 7) + "-" + number.slice(7, 11);
  }
}
