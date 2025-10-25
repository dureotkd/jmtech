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

<div class="bg-white">
    <div class="!border-b !pb-4 !mb-4 !space-y-4">
        <h2 class="!text-lg font-semibold">
            <div class="breadcrumbs text-sm">
                <div class="text-sm text-gray-700 space-x-1">
                    <span>부본사-1</span>
                    <span class="text-gray-400 !mx-1"> &gt; </span>
                    <span class="font-medium text-black">신성민</span>
                </div>

            </div>

        </h2>

        <div class="grid grid-cols-2 gap-4 mt-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">아이디</label>
                <input type="text" value="chaejun97" class="input input-bordered w-full mt-1" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">이름</label>
                <input type="text" value="김재준" class="input input-bordered w-full mt-1" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">연락처</label>
                <input type="text" value="010-9031-1997" class="input input-bordered w-full mt-1" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">주소</label>
                <input type="text" value="@(수신동의: 동의)" class="input input-bordered w-full mt-1" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">포인트</label>
                <input type="text" value="2025-06-26 13:37:39" class="input input-bordered w-full mt-1" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">메모</label>
                <textarea class="textarea h-24" placeholder="Bio"></textarea>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button class="btn btn-soft">부본사 이동</button>
            <button class="btn btn-soft">매장 해제</button>
            <button class="btn btn-soft">계정 삭제</button>
        </div>
    </div>

    <div class="!space-y-2">
        <h2 class="!text-lg font-bold">포인트 이력</h2>

        <form action="" class="!mt-2 flex items-center gap-2 bg-white justify-between">
            <div class="flex gap-2">
                총 포인트<span class="font-semibold">5,000,000원</span>
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
                        <th>아이디</th>
                        <th>유형</th>
                        <th>금액</th>
                        <th>구분</th>
                        <th>잔액</th>
                        <th>처리일시</th>
                        <th>처리명령</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    <!-- row 1 -->
                    <tr class="border-b">
                        <td>1</td>
                        <td>
                            dureotkd123
                        </td>
                        <td>
                            적립 / 사용 / 관리자 조정
                        </td>
                        <td>
                            5,000,000원
                        </td>
                        <td>
                            비고 및 설명
                        </td>
                        <td>
                            상품주문
                            <a href="#" class="!text-blue-600 !hover:underline">
                                (주문번호 ASIKDWOK219)
                            </a>
                        </td>
                        <td>
                            2025-06-26<br /><span class="text-xs text-gray-500">(13:37:39)</span>
                        </td>
                        <td>
                            <div class="flex flex-col gap-2">
                                <button onclick="" class="btn">마일리지 회수</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>