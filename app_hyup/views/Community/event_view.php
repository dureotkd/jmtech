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

    <!-- 상품 1 -->

    <?
    if (!empty($events)) {
    ?>
        <div class="lg:!px-0 !px-4 !mt-6 grid grid-cols-2 md:grid-cols-3 lg:gap-6 gap-2 !mx-auto">

            <?
            foreach ($events as $event) {
            ?>
                <div onclick="fadeOutButton('/community/event?id=<?= $event['id'] ?>')" class="">
                    <div class="realtive cursor-pointer relative group bg-white shadow-md rounded-md overflow-hidden">
                        <img
                            src="<?= $event['image_url'] ?>"
                            alt="<?= $event['title'] ?>"
                            class="lg:h-84 h-52 w-full object-cover transition-transform duration-300 transform hover:scale-115" />
                    </div>

                    <div class="text-left !py-4 rounded">
                        <div class="!text-md text-center text-gray-800 !mb-1">
                            <?= $event['title'] ?>
                        </div>
                    </div>
                </div>
            <?
            }
            ?>
        </div>

    <?
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