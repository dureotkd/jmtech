document.addEventListener("DOMContentLoaded", () => {});

function handle_login(event) {
  event.preventDefault();

  const user_id_ele = $("#user_id");
  const user_pw_ele = $("#user_pw");

  if (user_id_ele.val() == "") {
    Swal.fire({
      text: "아이디를 입력해주세요.",
      icon: "error",
      confirmButtonText: "닫기",
    });
    user_id_ele.focus();
    return;
  }

  if (user_pw_ele.val() == "") {
    Swal.fire({
      text: "비밀번호를 입력해주세요.",
      icon: "error",
      confirmButtonText: "닫기",
    });
    user_pw_ele.focus();
    return;
  }

  const auto_login_yn = $(".auto_login").is(":checked") ? "Y" : "N";

  $.ajax({
    type: "POST",
    async: true, // 프로그래스바 표시를 위해 동기로 보낸다.
    url: "/Login/login_proc",
    dataType: "json",
    data: {
      user_id: user_id_ele.val(),
      user_pw: user_pw_ele.val(),
      auto_login_yn: auto_login_yn,
    },
    success: function (res) {
      if (res.code == "success") {
        location.href = res.redirect_url;
      }
    },
    error: function (xhr, textStatus) {
      alert(xhr + "error = " + textStatus);
    },
  });
}
