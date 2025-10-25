<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<meta
		name="viewport"
		content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

	<title>제이엠테크 | 무설탕 건강 감미료</title>

	<meta name="keywords" content="이눌린, 스위틀린, 무설탕 감미료, 혈당관리, 장 건강, 프리바이오틱스, 식이섬유, 건강 습관, 다이어트 설탕 대체품">
	<meta name="description" content="당 걱정 없이 달콤하게! 프리바이오틱스와 식이섬유가 함유된 스위틀린 이눌린 액상 스위트린으로 건강한 단맛을 즐기세요.">
	<meta property="og:title" content="스위틀린 이눌린 액상 스위트린 - 당 걱정 없는 건강한 단맛">
	<meta property="og:description" content="장 건강, 혈당 관리, 무설탕! 달콤하게 챙기는 식이섬유 스위틀린 스위트린. 건강을 위한 새로운 습관.">
	<meta property="og:image" content="/assets/app_hyup/images/logo.jpg">
	<meta property="og:url" content="https://mosihealth.com">

	<link data-n-head="1" rel="icon" type="image/x-icon" href="/assets/app_hyup/images/favicon.ico">

	<!-- tailwind CSS -->
	<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

	<!-- pretendard CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretendard@1.3.8/dist/web/variable/pretendardvariable-dynamic-subset.css" />

	<!-- Swiper CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

	<?
	$datetime = time();
	?>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
	<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

	<link rel="stylesheet" href="/assets/app_hyup/common/reset.css" />
	<link rel="stylesheet" href="/assets/app_hyup/common/base.css?v=<?= $datetime ?>" />


	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-migrate-3.5.2.js" integrity="sha256-ThFcNr/v1xKVt5cmolJIauUHvtXFOwwqiTP7IbgP8EU=" crossorigin="anonymous"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tippy.js/6.3.7/tippy-bundle.umd.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- Swiper JS -->
	<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>

	<script src="/assets/app_hyup/common/ajaxsetup.js"></script>
	<script src="/assets/app_hyup/common/header.js"></script>

	<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>


	<!-- AOS JS -->
	<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

</head>

<body class="relative w-full min-h-screen flex flex-col items-center justify-center bg-gray-50">
	<div id="page-progress-bar"></div>

	<!-- <div class="loader"></div> -->

	<div class="flex flex-col items-center justify-center">
		<!-- Heroicon: Exclamation Triangle -->
		<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info text-gray-500">
			<circle cx="12" cy="12" r="10" />
			<path d="M12 16v-4" />
			<path d="M12 8h.01" />
		</svg>

		<h1 class="!text-2xl font-extrabold text-gray-800 !my-4">PAGE NOT FOUND 404</h1>
		<p class="!text-lg text-gray-500 !mb-2">페이지를 찾을 수 없습니다.</p>
		<p class="!text-lg text-gray-500 !mb-6 text-center">요청하신 페이지가 존재하지 않거나<br /> 이동되었을 수 있습니다.</p>
		<button onclick="fadeOutButton('/')" type="button" class="btn-primary-sm !px-6 !py-3 rounded transition">
			메인으로 돌아가기
		</button>
	</div>

	<script>
		AOS.init();

		tippy('#myButton1', {
			placement: 'bottom',
			arrow: false,
			content: "검색",
		});
		tippy('#myButton2', {
			placement: 'bottom',
			arrow: false,
			content: "마이페이지",
		});
		tippy('#myButton3', {
			placement: 'bottom',
			arrow: false,
			content: "장바구니",
		});

		function handle_aside() {
			const drawer = $('#my-drawer');
			drawer.click();
		}

		function close_top_banner(event) {
			const $banner = $(event.currentTarget).closest("#top_banner");

			$banner
				.css("overflow", "hidden")
				.animate({
						height: 0,
						paddingTop: 0,
						paddingBottom: 0,
						marginTop: 0,
						marginBottom: 0,
						opacity: 0,
					},
					300,
					function() {
						setCookie("top_banner_closed", 1, 7); // 쿠키 설정 (7일 동안 유지)
						$(this).remove(); // 필요 없으면 생략 가능
					}
				);
		}
	</script>
	<!-- 호출 -->
	<script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
	<script src="/assets/app_hyup/common/cookie.js"></script>
	<script src="/assets/app_hyup/common/mask.js"></script>
	<script src="/assets/app_hyup/common/common.js"></script>
	<script src="/assets/app_hyup/common/channel.js"></script>
	<script src="/assets/app_hyup/common/transition.js"></script>

</body>

</html>