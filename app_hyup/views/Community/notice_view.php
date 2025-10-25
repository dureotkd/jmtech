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


    <div class="p-6 flex items-center justify-center flex-col">
        <table class="lg:table hidden w-full max-w-6xl mx-auto text-sm text-left border-t">
            <thead class="border-b">
                <tr class="font-normal">
                    <th class="w-[10%] py-3 px-4">No</th>
                    <th class="w-[70%] py-3 px-4">제목</th>
                    <th class="w-[20%] py-3 px-4 text-right">작성시간</th>
                </tr>
            </thead>
            <tbody>
                <?
                if (!empty($notices)) {
                    foreach ($notices as $notice) {
                ?>
                        <tr class="bg-sm-light" onclick="window.location.href = '/community/notice?id=<?= $notice['id'] ?>'">
                            <td class="py-3 px-4">📌</td>
                            <td class="py-3 px-4">
                                <a href="/community/notice?id=<?= $notice['id'] ?>" class="text-sm font-medium text-gray-800 hover:text-gray-600">
                                    <?= $notice['title'] ?>
                                </a>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <span class="text-sm text-gray-500">
                                    <?= date('Y-m-d', strtotime($notice['created_at'])) ?>
                                </span>
                            </td>
                        </tr>
                <?
                    }
                }
                ?>
            </tbody>
        </table>

        <?
        if (!empty($notices)) {
        ?>
            <!-- 모바일 리스트 (md 미만에서만 보임) -->
            <div class="lg:hidden w-full block !px-4 divide-y border-t border-[#d5c3b2] text-sm">
                <div class="!py-4 !space-y-1 !border-b-1 !border-gray-300">
                    <?
                    foreach ($notices as $notice) {
                    ?>
                        <p class="font-medium">
                            <a href="/community/notice?id=<?= $notice['id'] ?>" class="text-sm font-medium text-gray-800 hover:text-gray-600">
                                <?= $notice['title'] ?>
                            </a>
                        </p>
                        <p class="!text-sm text-gray-500 mt-1">
                            <?= date('Y-m-d', strtotime($notice['created_at'])) ?>
                        </p>
                    <?
                    }
                    ?>
                </div>
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

</div>