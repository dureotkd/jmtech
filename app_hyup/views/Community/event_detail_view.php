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
            <a href="/community/event" class="<?= strstr($current_path, '/community/event') ? 'color-sm' : '' ?>">Event</a>
        </li>
        <li>
            <a href="/community/notice" class="<?= strstr($current_path, '/community/notice') ? 'color-sm' : '' ?>">공지사항</a>
        </li>
        <li>
            <a href="/community/faq" class="<?= strstr($current_path, '/community/faq') ? 'color-sm' : '' ?>">FAQ</a>
        </li>
    </ul>

    <div class="lg:!px-0 !px-4 !mt-6 grid grid-cols-2 md:grid-cols-3 lg:gap-6 gap-2 !mx-auto">


    </div>

</div>