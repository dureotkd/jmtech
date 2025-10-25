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
    <h2 class="!text-xl font-bold">총판코드 관리</h2>

    <!-- name of each tab group should be unique -->
    <div class="tabs tabs-lift">
        <input type="radio" name="my_tabs_3" value="branch" onchange="changeTab(event)" class="tab" aria-label="부본사" <?= $tab == 'branch' ? 'checked' : '' ?> />
        <div class="tab-content bg-base-100 border-base-300 p-6">
            <div class="flex gap-2 items-center !mt-6">
                <select name="agent_number" id="agent_number" class="select w-fit">
                    <?
                    foreach ($group_store_data as $code => $name) {
                    ?>
                        <option value="<?= $code ?>"><?= $name ?></option>
                    <?
                    }
                    ?>
                </select>
                로 총판코드를
                <input type="number" name="count" class="input !w-[50px]" value="3" min="3" max="6" placeholder="개수" />
                개 생성합니다.
                <button type="button" onclick="make_store_code(event);" value="1" class="btn btn-soft">총판코드 생성</button>
            </div>
            <div class="flex flex-col gap-8 !mt-8">
                <?
                if (!empty($store_code_all_data)) {

                    foreach ($store_code_all_data as $key_name => $store_codes) {

                        $agent_info = explode('_', $key_name);
                        $agent_number = $agent_info[0];
                        $agent_name = $agent_info[1] ?? 'Unknown';

                ?>
                        <div class="flex flex-col gap-4 !border-2 !border-gray-300 !p-4">
                            <h2 class="!text-xl font-bold col-span-6">
                                <a class="!text-orange-500" href="/admin/agent?id=<?= $agent_number ?>">
                                    부본사 - <?= $agent_number ?> (<?= $agent_name ?>)
                                </a>
                                > 매장 <?= count($store_codes) ?>개
                            </h2>

                            <div class="flex flex-wrap gap-4  max-h-64 p-2">
                                <?php foreach ($store_codes as $store_code): ?>
                                    <div class="flex items-center gap-2 border rounded p-2">
                                        <input value="<?= $store_code['code'] ?>" class="input input-primary !w-[120px]" />
                                        <?
                                        if (!empty($store_code['user_id'])) {
                                        ?>
                                            <a href="/admin/agent?user_id=<?= $store_code['user_id'] ?>" class="text-xs text-gray-500">
                                                => <?= $store_code['user_name'] ?>
                                            </a>
                                        <?
                                        }
                                        ?>
                                        <button type="button" onclick="handle_store_code_text(event , <?= $store_code['id'] ?>);" class="btn btn-soft !w-fit">
                                            수정
                                        </button>
                                        <button type="button" onclick="delete_store_code(<?= $store_code['id'] ?>);" class="btn btn-soft !w-fit">
                                            삭제
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>


                    <?
                    }
                    ?>

                <?
                }
                ?>
            </div>
        </div>

        <input type="radio" name="my_tabs_3" value="store" onchange="changeTab(event)" class="tab" aria-label="매장" <?= $tab == 'store' ? 'checked' : '' ?> />
        <div class="tab-content bg-base-100 border-base-300 p-6">
            <div class="flex flex-col gap-8 !mt-8">
                <?
                if (!empty($store_user_data)) {

                    foreach ($store_user_data as $agent_number => $row) {

                        $customer_user_all = $row['customer_user_all'] ?? [];

                        if (!empty($customer_user_all)) {
                        }

                ?>
                        <div class="flex flex-col gap-4 !border-2 !border-gray-300 !p-4">
                            <h2 class="!text-xl font-bold col-span-6">
                                <a href="/admin/agent?id=<?= $row['id'] ?>" class="!text-blue-500">
                                    매장 - <?= $agent_number ?> (<?= $row['name'] ?>)
                                </a>
                                > 고객 <?= count($customer_user_all) ?>개
                            </h2>

                            <div class="flex flex-wrap gap-4  max-h-64 p-2">
                                <?php foreach ($customer_user_all as $customer_user): ?>
                                    <div class="flex items-center gap-2 border rounded p-2">
                                        <input value="<?= $customer_user['store_code'] ?>" class="input input-primary !w-[120px]" />
                                        <?
                                        if (!empty($customer_user['user_id'])) {
                                        ?>
                                            <a href="/admin/agent?user_id=<?= $customer_user['user_id'] ?>" class="text-xs text-gray-500">
                                                => <?= $customer_user['name'] ?>
                                            </a>

                                        <?
                                        }
                                        ?>
                                        <button type="button" onclick="update_customer(event,'<?= $customer_user['user_id'] ?>');" class="btn btn-soft !w-fit">
                                            수정
                                        </button>
                                        <button type="button" onclick="delete_customer('<?= $customer_user['user_id'] ?>');" class="btn btn-soft !w-fit">
                                            삭제
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>


                    <?
                    }
                    ?>

                <?
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    function changeTab(e) {
        const target = $(e.target);

        // query  ?tab=branch or ?tab=store
        const tabValue = target.val();
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tabValue);
        window.history.pushState({}, '', url.toString());
    }

    function update_customer(event, user_id) {
        const target = $(event.target);
        const input = target.closest("div").find("input");
        const store_code = input.val();

        // Update customer logic here

        $.ajax({
            type: "POST",
            url: "/admin/store_code/update_customer_code",
            data: {
                store_code: store_code,
                user_id: user_id
            },
            dataType: "json",
            success: function(response) {
                alert(response.msg);
                if (response.ok) {
                    location.reload();
                }
            }
        });
    }

    function delete_customer(user_id) {

        if (!confirm("정말로 이 고객코드를 삭제하시겠습니까?")) {
            return;
        }

        $.ajax({
            type: "POST",
            url: "/admin/store_code/delete_customer_code",
            data: {
                user_id: user_id
            },
            dataType: "json",
            success: function(response) {
                alert(response.msg);
                if (response.ok) {
                    location.reload();
                }
            }
        });
    }

    function handle_store_code_text(e, id) {
        const target = $(e.target);
        const input = target.closest("div").find("input");

        $.ajax({
            type: "POST",
            url: "/admin/store_code/update_store_code",
            data: {
                store_code: input.val(),
                id: id
            },
            dataType: "json",
            success: function(response) {
                alert(response.msg);
            }
        });

    }

    function delete_store_code(id) {

        if (!confirm("정말로 이 총판코드를 삭제하시겠습니까?")) {
            return;
        }

        $.ajax({
            type: "POST",
            url: "/admin/store_code/delete_store_code",
            data: {
                id: id
            },
            dataType: "json",
            success: function(response) {
                alert(response.msg);
                if (response.ok) {
                    location.reload();
                }
            }
        });
    }

    function make_store_code(e) {
        $.ajax({
            type: "POST",
            url: "/admin/store_code/make_store_code",
            data: {
                agent_number: $("#agent_number").val(),
                count: $("input[name='count']").val()
            },
            dataType: "json",
            success: function(response) {

                if (response.ok) {
                    alert("총판코드가 생성되었습니다.");
                    location.reload();
                } else {
                    alert("오류: " + response.message);
                }
            }
        });
    }
</script>