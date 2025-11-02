<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<meta
		name="viewport"
		content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

	<title>NOT 404 | 제이엠테크</title>

	<link rel="apple-touch-icon" sizes="57x57" href="/assets/app_hyup/images/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/assets/app_hyup/images/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/assets/app_hyup/images/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/assets/app_hyup/images/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/assets/app_hyup/images/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/assets/app_hyup/images/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/assets/app_hyup/images/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/assets/app_hyup/images/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/app_hyup/images/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="/assets/app_hyup/images/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/app_hyup/images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/assets/app_hyup/images/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/app_hyup/images/favicon/favicon-16x16.png">
	<link rel="manifest" href="/assets/app_hyup/pwa/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/assets/app_hyup/images/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#2a4c8f">

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
		<button onclick="window.location.href = '/'" type="button" class="btn-primary-sm !px-6 !py-3 rounded transition">
			메인으로 돌아가기
		</button>
	</div>

</body>

</html>