<section class="lg:!px-0 !px-6">
    <h2 class="!text-lg font-bold !my-4">포인트 이력</h2>

    <div class="">
        <div class="w-full mx-auto rounded-lg border border-gray-200">

            <!-- 내용 -->
            <div class="">
                <!-- 테이블 헤더 -->
                <div class="grid grid-cols-4 md:grid-cols-5 !py-2 !px-1 !border-t !border-gray-600 font-semibold bg-white text-center">
                    <div class="col-span-1">상태</div>
                    <div class="lg:block hidden col-span-1">거래번호</div>
                    <div class="col-span-1">금액</div>
                    <div class="col-span-1">잔고</div>
                    <div class="col-span-1">등록일</div>
                </div>

                <!-- Q&A 항목 -->
                <div class="divide-y !py-2 !px-1 !space-y-4 !border-y !border-gray-200 divide-gray-200">
                    <?
                    if (!empty($point_logs)) {
                        $point_type_vo = unserialize(POINT_TYPE);
                        $POINT_TYPE_CLASS = unserialize(POINT_TYPE_CLASS);

                        foreach ($point_logs as $point_log) {
                    ?>
                            <div class="!text-sm grid grid-cols-4 md:grid-cols-5 py-3 px-4 text-center bg-white items-center">
                                <div class="<?= $POINT_TYPE_CLASS[$point_log['point_type']] ?? 'text-gray-500' ?>">
                                    <?= $point_type_vo[$point_log['point_type']] ?? '' ?>
                                </div>
                                <div class="lg:block hidden col-span-1">
                                    <?= $point_log['number'] ?>
                                </div>
                                <div class="col-span-1 flex items-center gap-1 justify-center text-sm">
                                    <?= number_format($point_log['amount']) ?>원
                                </div>
                                <div class="col-span-1 ">
                                    <?= number_format($point_log['balance']) ?>원
                                </div>
                                <div class="col-span-1  ">
                                    <div class=" text-xs">
                                        <?= timeAgo($point_log['created_at']) ?>
                                    </div>
                                </div>
                            </div>
                        <?
                        }
                    } else {
                        ?>
                        <div class="!text-sm text-gray-500 text-center py-4">
                            포인트 이력이 없습니다.
                        </div>
                    <?
                    }
                    ?>
                </div>

            </div>

        </div>

    </div>
</section>