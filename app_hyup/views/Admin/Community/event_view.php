<style>
    table th,
    table td {
        border: 1px solid #d1d5db;
        /* Tailwind의 gray-300 */
        padding: 0.75rem;
        /* Tailwind의 p-3 정도 */
        text-align: left;
        vertical-align: middle;
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


<div class="!space-y-2">
    <h2 class="!text-lg font-bold mb-6">이벤트</h2>

    <div class="overflow-x-auto !mt-8">

        <div class="w-full flex justify-between gap-2 !mb-2">
            <div class="">
                <button type="button" onclick="window.location.href = '/admin/community/event/create'" class="btn btn-soft">이벤트 등록</button>
            </div>
        </div>
        <table class="table w-full border border-gray-200">
            <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                <tr>
                    <th class="w-[350px]">메인 이미지</th>
                    <th>제목</th>
                    <th class="w-[200px]">등록일</th>
                    <th class="w-[200px]">처리명령</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">

                <?
                if (!empty($event_all)) {

                    foreach ($event_all as $event) {

                ?>
                        <tr class="border-b">
                            <td>
                                <img src="<?= $event['image_url'] ?>" alt="레시피 이미지" class="object-cover rounded">
                            </td>
                            <td>
                                <a href="/admin/community/event?id=<?= $event['id'] ?>" class="!text-blue-600 hover:underline">
                                    <?= $event['title'] ?>
                                </a>
                            </td>
                            <td>
                                <?= explode(' ', $event['created_at'])[0] ?>
                                <br />
                                <span class="text-xs text-gray-500">(<?= explode(' ', $event['created_at'])[1] ?>)</span>
                            </td>
                            <td>
                                <button type="button" onclick="delete_event(<?= $event['id'] ?>);" class="btn !px-3 !py-2">
                                    삭제
                                </button>
                            </td>
                        </tr>
                    <?
                    }
                    ?>
                <?
                }
                ?>

            </tbody>
        </table>
    </div>

</div>

<script>
    function delete_event(id) {

        if (confirm('정말로 이벤트를 삭제하시겠습니까?')) {

            $.ajax({
                type: "POST",
                url: "/admin/community/event/delete_event",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.ok) {
                        alert(response.msg);
                        window.location.reload();
                    } else {
                        alert(response.msg);
                    }
                }
            });
        }
    }
</script>