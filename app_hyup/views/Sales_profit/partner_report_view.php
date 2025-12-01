<style>
    .litepicker {
        font-size: 14px;
        /* 기본값은 12~13px 정도 */
    }
</style>


<div class="p-4 bg-white font-sans text-sm text-gray-800">
    <div class="flex items-center !border-b !font-sans !border-gray-300 !pb-3 justify-between">
        <h1 class="!text-xl">
            매출처현황
        </h1>

        <div class="flex items-center gap-2 !text-xs">

            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button">
                    <button type="button" class="!px-2 py-1 !border-1 !border-gray-300 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu">
                            <path d="M4 5h16" />
                            <path d="M4 12h16" />
                            <path d="M4 19h16" />
                        </svg>
                    </button>

                </div>
                <ul
                    tabindex="-1"
                    class="!min-w-[210px] !border !border-gray-300 !bg-white !mt-2 items-center justify-center font-sans menu dropdown-content z-1 mt-4 w-52 shadow-sm">

                    <div class="w-full flex flex-col justify-start !text-xs">

                        <button onclick="open_popup_default('/setting/item/create','물품 등록',500,580);"
                            class="!text-left flex items-center gap-2 border-b-1 border-gray-300 !p-4 sm-hover" type="button">

                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download-icon lucide-download">
                                <path d="M12 15V3" />
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <path d="m7 10 5 5 5-5" />
                            </svg>

                            <span>
                                엑셀파일 다운로드
                            </span>
                        </button>

                        <button class="flex items-center gap-2 !text-left !p-4 sm-hover" onclick="show_excel_upload_modal();" type="button">

                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer-icon lucide-printer">
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" />
                                <rect x="6" y="14" width="12" height="8" rx="1" />
                            </svg>

                            <span>
                                매출처현황 인쇄
                            </span>
                        </button>
                    </div>
                </ul>
            </div>
        </div>

    </div>

    <form id="searchFrm">
        <input type="hidden" name="page" id="page" value="<?= $page ?>">
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full !my-6">

        <!-- 매출 현황 -->
        <div class="!border-1 !border-[#3598db] rounded-xl !p-5 bg-white shadow-sm">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-[#3598db] font-semibold">매출 현황</h3>

                <span class="!text-xs text-[#3598db] !border !border-gray-300 !px-2 !py-1 !rounded-full">
                    최근 3개월
                </span>
            </div>

            <div class="flex items-end justify-end text-[#3598db] gap-1 !mt-4">
                <p class=" text-right !text-xl font-bold text-[#3598db] amount">
                    <?= number_format($stat['sum_recent_3months']) ?>
                </p>
                <p class="!text-sm !mb-[2px]">원</p>
            </div>
        </div>

        <div class="!border-1 !border-[#3598db] rounded-xl !p-5 bg-white shadow-sm">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-[#3598db] font-semibold">매출 현황</h3>

                <span class="!text-xs text-[#3598db] !border !border-gray-300 !px-2 !py-1 !rounded-full">
                    최근 6개월
                </span>
            </div>

            <div class="flex items-end justify-end text-[#3598db] gap-1 !mt-4">
                <p class=" text-right !text-xl font-bold text-[#3598db] amount">
                    <?= number_format($stat['sum_recent_6months']) ?>
                </p>
                <p class="!text-sm !mb-[2px]">원</p>
            </div>
        </div>

        <div class="!border-1 !border-[#3598db] rounded-xl !p-5 bg-white shadow-sm">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-[#3598db] font-semibold">매출 현황</h3>

                <span class="!text-xs text-[#3598db] !border !border-gray-300 !px-2 !py-1 !rounded-full">
                    당해년도
                </span>
            </div>

            <div class="flex items-end justify-end text-[#3598db] gap-1 !mt-4">
                <p class=" text-right !text-xl font-bold text-[#3598db] amount">
                    <?= number_format($stat['sum_current_year']) ?>
                </p>
                <p class="!text-sm !mb-[2px]">원</p>
            </div>
        </div>

    </div>


    <!-- 필터 영역 -->
    <div class="flex items-center gap-2 mb-4 !text-sm">

        <!-- 테이블 -->
        <table class="w-full border border-gray-300">
            <thead>
                <tr class="bg-[#eeeeee]">
                    <th class="!text-black w-[250px]">거래처명</th>
                    <th class="!text-black w-[100px]">등록번호</th>
                    <th class="!text-right !text-black">최근 3개월 매출</th>
                    <th class="!text-right !text-black">당월 매출</th>
                    <th class="!text-right !text-black">전월 매출</th>
                    <th class="!text-right !text-black">전전월 매출</th>
                    <th class="!text-black">최근거래정보</th>
                </tr>
            </thead>
            <tbody>
                <?
                if (!empty($data)) :
                    foreach ($data as $row) :

                ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td data-label="거래처명">
                                <?= $row['partner_name'] ?>
                            </td>
                            <td data-label="등록번호">
                                <?= $row['company_num'] ?>
                            </td>
                            <td data-label="최근 3개월 매출" class="text-right">
                                <?= number_format($row['recent_3months_sales']) ?>
                            </td>
                            <td data-label="당월 매출" class="text-right">
                                <?= number_format($row['current_month_sales']) ?>
                            </td>
                            <td data-label="전월 매출" class="text-right">
                                <?= number_format($row['prev_month_sales']) ?>
                            </td>
                            <td data-label="전전월 매출" class="text-right">
                                <?= number_format($row['prev_prev_month_sales']) ?>
                            </td>
                            <td data-label="최근거래정보">
                                <?
                                if (!empty(explode(' ', $row['desc'])[1])) {
                                ?>
                                    <?= explode(' ', $row['desc'])[0] ?>
                                    <span class="text-red-600 font-semibold">
                                        +<?= explode(' ', $row['desc'])[1] ?>
                                    </span>
                                    <?= explode(' ', $row['desc'])[2] ?>
                                <?
                                }
                                ?>
                            </td>
                        </tr>
                    <? endforeach;
                else : ?>
                    <tr>
                        <td colspan=" 9" class="text-center py-4">등록된 매출처가 없습니다.</td>
                    </tr>
                <? endif; ?>

            </tbody>
        </table>
    </div>

    <div class="page_wrap">

        <?php if (!empty($page_data['is_prev'])): ?>
            <!-- 이전 버튼 -->
            <a href="javascript:go_page('<?php echo $page_data['start_page'] - 1; ?>')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                </svg>
            </a>
        <?php endif; ?>

        <?php foreach ($page_data['page_num'] as $page => $active): ?>
            <a href="javascript:go_page('<?php echo $page; ?>')" class="<?php echo $active; ?>"><?php echo $page; ?></a>
        <?php endforeach; ?>

        <?php if (!empty($page_data['is_next'])): ?>
            <!-- 다음 버튼 -->
            <a href="javascript:go_page('<?php echo $page_data['end_page'] + 1; ?>')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z" />
                </svg>
            </a>
        <?php endif; ?>

    </div>

    <dialog id="calendar_modal" class="modal">
        <div class="modal-box !text-xs relative max-w-3xl">

            <div class="absolute inset-0 modal-loading hidden">
                <div class="flex items-center justify-center w-full h-full bg-white/70">
                    <img class="w-16" src="/assets/app_hyup/images/loading.gif" alt="loading" />
                </div>
            </div>

            <form id="calendar_form" class="bg-white w-full border border-gray-300">
                <!-- 헤더 -->
                <div class="flex justify-between items-center !text-base !px-4 !py-2 bg-[#4b5563]">
                    <h2 class="text-white font-semibold">일자조회</h2>
                    <button type="button" class="text-gray-200" onclick="close_calendar_modal();">
                        ✕
                    </button>
                </div>

                <div class="border border-gray-300 !p-4 w-full !text-xs bg-white rounded text-sm">
                    <div class="flex items-center gap-2 !mb-2">
                        <span>기준연도</span>
                        <select id="yearSelect" class="border border-gray-300 rounded px-1 py-0.5">
                            <option value="2025" selected>2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>

                    <?php
                    // 1️⃣ 기본 기간
                    $group1 = ['오늘', '전일', '주간', '전주', '당월', '전월', '올해'];

                    // 2️⃣ 반기 / 분기 / 누적
                    $group2 = ['상반기', '하반기', '1/4분기', '2/4분기', '3/4분기', '4/4분기', '오늘까지'];

                    // 3️⃣ 월별
                    $group3 = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];
                    ?>

                    <div class="!space-y-1 !mb-4">
                        <!-- 1️⃣ 기본 기간 -->
                        <div class="grid grid-cols-7 gap-1 mb-1">
                            <?php foreach ($group1 as $label): ?>
                                <button type="button" class="date-btn sm-btn justify-center !m-0"><?= $label ?></button>
                            <?php endforeach; ?>
                        </div>

                        <!-- 2️⃣ 반기 / 분기 / 누적 -->
                        <div class="grid grid-cols-7 gap-1 mb-1">
                            <?php foreach ($group2 as $label): ?>
                                <button type="button" class="date-btn sm-btn justify-center !m-0"><?= $label ?></button>
                            <?php endforeach; ?>
                        </div>

                        <!-- 3️⃣ 월별 -->
                        <div class="grid grid-cols-12 gap-1">
                            <?php foreach ($group3 as $label): ?>
                                <button type="button" class="date-btn sm-btn justify-center !m-0"><?= $label ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>


                    <!-- 달력 2개 -->
                    <div class="flex justify-between items-center mb-3">
                        <input type="hidden" id="temp_start_date" class="border border-gray-300 rounded px-2 py-1 w-[150px]" placeholder="시작일">
                        <input type="hidden" id="temp_end_date" class="border border-gray-300 rounded px-2 py-1 w-[150px]" placeholder="종료일">
                    </div>

                </div>

                <!-- 하단 버튼 -->
                <div class="w-full !px-2 !text-[13px] flex justify-center items-center gap-1.5 font-sans font-300 !my-2">
                    <!-- 저장 후 인쇄 -->
                    <button
                        type="button"
                        onclick="handle_calendar_apply();"
                        class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
                        적용
                    </button>

                    <!-- 취소 -->
                    <button
                        type="button"
                        onclick="close_calendar_modal();"
                        class="px-2 py-1 bg-[#fff] text-gray-700 hover:bg-gray-100 border border-gray-300">
                        취소
                    </button>
                </div>
            </form>
        </div>
    </dialog>