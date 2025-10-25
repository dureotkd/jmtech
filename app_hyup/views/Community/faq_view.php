<?
$current_path = $_SERVER['REQUEST_URI'];

?>

<style>
    table th,
    table td {
        border: 1px solid #d1d5db;
        /* Tailwind의 gray-300 */
        padding: 0.75rem;
        /* Tailwind의 p-3 정도 */
        text-align: left;
        vertical-align: middle;
        cursor: pointer;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    table thead {
        background-color: #f9fafb;
        /* gray-50 */
    }
</style>

<div class="flex flex-col !gap-12 relative w-full bg-cover bg-center">
    <img class="lg:h-[calc(100vh-482px)] h-[170px] w-full object-cover"
        src="https://cdn.imweb.me/thumbnail/20230131/425ea3b668cb6.png" class="" />


    <ul class="flex justify-center !text-xl  items-center playfair_display gap-8 text-sm font-normal">
        <li>
            <a href="/community/event" class="<?= $current_path === '/community/event' ? 'color-sm' : '' ?>">Event</a>
        </li>
        <li>
            <a href="/community/notice" class="<?= $current_path === '/community/notice' ? 'color-sm' : '' ?>">공지사항</a>
        </li>
        <li>
            <a href="/community/faq" class="<?= $current_path === '/community/faq' ? 'color-sm' : '' ?>">FAQ</a>
        </li>
    </ul>

    <div class="w-full justify-center items-center mx-auto">
        <div class="max-w-3xl !mx-auto p-6 bg-white !px-4">

            <?
            if (!empty($faqs)) {

                $FAQ_CATEGORY = unserialize(FAQ_CATEGORY);

                foreach ($faqs as $faq) {
            ?>
                    <div class="accordion-item !border-b !border-gray-300">
                        <button class="accordion-header flex justify-between items-center w-full text-left py-2">
                            <div class="!space-y-2">
                                <p class="color-sm !w-[100px]">
                                    <?= $FAQ_CATEGORY[$faq['category']] ?>
                                </p>
                                <span>
                                    <?= $faq['question'] ?>
                                </span>
                            </div>
                            <span class="accordion-icon">▾</span>
                        </button>
                        <div class="accordion-content overflow-hidden max-h-0 transition-all duration-300 !mb-2 ease-in-out px-4 text-sm">
                            <p class="leading-6"><?= $faq['answer'] ?></p>
                        </div>
                    </div>
                <?
                }
            } else {
                ?>
                <div class="lg:!py-20 !py-8 flex flex-col items-center justify-center text-center text-gray-500">
                    <h2 class="!text-lg font-semibold !mb-2">아직 등록된 내용이 없습니다.</h2>
                    <p class="!text-sm text-gray-400">새로운 정보를 곧 업데이트할 예정입니다. 조금만 기다려 주세요!</p>
                </div>
            <?
            }
            ?>

        </div>
    </div>
</div>

<script>
    $(function() {
        $(".accordion-header").on("click", function() {
            const $item = $(this).closest(".accordion-item");
            const $content = $item.find(".accordion-content");

            if ($content.hasClass("open")) {
                $content.removeClass("open").animate({
                    maxHeight: 0
                }, 100);
                $item.find(".accordion-icon").text("▾");
            } else {
                $(".accordion-content.open").removeClass("open").animate({
                    maxHeight: 0
                }, 100);
                $(".accordion-icon").text("▾");

                $content.addClass("open").css("maxHeight", $content.prop("scrollHeight")).animate({
                        maxHeight: $content.prop("scrollHeight")
                    },
                    100
                );
                $item.find(".accordion-icon").text("▴");
            }
        });
    });
</script>