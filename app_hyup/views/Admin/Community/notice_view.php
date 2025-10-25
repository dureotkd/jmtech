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
    <h2 class="!text-lg font-bold mb-6">공지사항</h2>

    <div class="overflow-x-auto !mt-8">

        <div class="w-full flex justify-between gap-2 !mb-2">
            <div class="">
                <button type="button" onclick="window.location.href = '/admin/community/notice/create'" class="btn btn-soft">공지사항 등록</button>
            </div>
        </div>
        <table class="table w-full border border-gray-200">
            <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                <tr>
                    <th class="w-[350px]">제목</th>
                    <th>내용</th>
                    <th class="w-[200px]">등록일</th>
                    <th class="w-[200px]">처리명령</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">

                <?
                if (!empty($notice_all)) {

                    foreach ($notice_all as $notice) {

                ?>
                        <tr class="border-b">
                            <td>
                                <?= $notice['title'] ?>
                            </td>
                            <td>
                                <?= $notice['content'] ?>
                            </td>
                            <td>
                                <?= explode(' ', $notice['created_at'])[0] ?>
                                <br />
                                <span class="text-xs text-gray-500">(<?= explode(' ', $notice['created_at'])[1] ?>)</span>
                            </td>
                            <td>
                                <button type="button" onclick="window.location.href = '/admin/community/notice/create?id=<?= $notice['id'] ?>'" class="btn !px-3 !py-2">
                                    수정
                                </button>

                                <button type="button" onclick="delete_notice(<?= $notice['id'] ?>);" class="btn !px-3 !py-2">
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
    function delete_notice(id) {

        if (confirm('정말로 notice를 삭제하시겠습니까?')) {

            $.ajax({
                type: "POST",
                url: "/admin/community/notice/delete_notice",
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