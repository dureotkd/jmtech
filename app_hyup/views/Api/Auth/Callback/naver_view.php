<script src="https://static.nid.naver.com/js/naverLogin_implicit-1.0.3.js"></script>

<script>
    var naver_id_login = new naver_id_login("6xiP0YNxyY0KGkGeb9cc", "<?= NAVER_CALLBACK_URL ?>");

    // 토큰 확인
    console.log("Access Token:", naver_id_login.oauthParams.access_token);

    // 사용자 프로필 조회
    naver_id_login.get_naver_userprofile("naverSignInCallback()");

    async function naverSignInCallback() {
        const email = naver_id_login.getProfileData('email');
        const nickname = naver_id_login.getProfileData('nickname');
        const name = naver_id_login.getProfileData('name');
        const profile_image_url = naver_id_login.getProfileData('profile_image');

        $.ajax({
            type: "POST",
            url: "/login/social_login",
            data: {
                social_type: 'naver',
                name: name,
                email: email,
                profile_image_url: profile_image_url
            },
            async: false,
            dataType: "json",
            success: function({
                ok
            }) {
                if (ok) {
                    window.location.href = '/';
                }
            },
            error: function(xhr, status, error) {
                console.error("Error during AJAX request:", error);
            }
        });

    }
</script>