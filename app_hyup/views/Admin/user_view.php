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
    <h2 class="text-2xl font-bold mb-6">매장관리</h2>

    <form action="" class="!mt-8 flex items-center gap-2 bg-white justify-between">
        <div class="flex gap-2">
            <select class="select !w-fit">
                <option disabled selected>부본사</option>
                <option>Crimson</option>
                <option>Amber</option>
                <option>Velvet</option>
            </select>

            <select class="select !w-fit">
                <option disabled selected>매장</option>
                <option>Crimson</option>
                <option>Amber</option>
                <option>Velvet</option>
            </select>
        </div>

        <div class="flex gap-2">
            <select class="select !w-fit">
                <option disabled selected>전체</option>
                <option>Crimson</option>
                <option>Amber</option>
                <option>Velvet</option>
            </select>

            <input type="text" placeholder="Type here" class="input" />
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="table w-full border border-gray-200">
            <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                <tr>
                    <th>#</th>
                    <th>관리그룹</th>
                    <th>매장명</th>
                    <th>매장 정보</th>
                    <th>마일리지</th>
                    <th>총판코드</th>
                    <th>등록일시</th>
                    <th>처리명령</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <tr>
                    <td>6</td>
                    <td>칠렉스</td>
                    <td class="font-semibold">칠렉스24 한강점</td>
                    <td>
                        <div class="space-y-1">
                            <div>사업자 등록번호: <span class="badge badge-outline text-blue-600 border-blue-400">510-86-03401</span></div>
                            <div>주소: <span class="badge badge-outline text-blue-600 border-blue-400">서울 강남구 언주로151길 19</span></div>
                            <div>대표자명: 이소왕</div>
                        </div>
                    </td>
                    <td class="text-right text-gray-800">0원</td>
                    <td>2025-05-12<br /><span class="text-xs text-gray-500">(17:33:30)</span></td>
                    <td>1234567890</td>
                    <td>
                        <div class="flex flex-col gap-2">
                            <button onclick="window.location.href = '/admin/user?id=1'" class="btn">상세</button>
                            <button class="btn">부본사 이동</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


</div>