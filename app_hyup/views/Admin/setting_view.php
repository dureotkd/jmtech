<style>
    label {
        width: 160px;
    }
</style>

<div class="">
    <h2 class="text-2xl font-bold mb-6">기본설정</h2>

    <form onsubmit="handle_site_meta(event);" class="!mt-4 grid grid-cols-1 gap-6">
        <div class="form-control">
            <label class="label"><span class="label-text">사이트 이름</span></label>
            <input type="text" name="meta_title" class="input input-bordered" value="<?= $site_meta_row['meta_title'] ?>" />
        </div>

        <div class="form-control">
            <label class="label"><span class="label-text">사이트 설명</span></label>
            <textarea class="textarea input input-bordered" name="meta_description" placeholder="Bio"><?= $site_meta_row['meta_description'] ?></textarea>
        </div>

        <div class="form-control">
            <label class="label"><span class="label-text">사이트 키워드</span></label>
            <textarea class="textarea input input-bordered" name="meta_keywords" placeholder="Bio"><?= $site_meta_row['meta_keywords'] ?></textarea>
        </div>

        <div class="form-control">
            <label class="label"><span class="label-text">계좌번호</span></label>
            <input type="text" name="account" class="input input-bordered" value="<?= $site_meta_row['account'] ?>" />
        </div>

        <div class="form-control">
            <label class="label"><span class="label-text">본사 적립금</span></label>
            <input type="number" name="head_point" class="input input-bordered" value="<?= $site_meta_row['head_point'] ?>" />
        </div>

        <div class="form-control">
            <label class="label"><span class="label-text">부본사 적립금</span></label>
            <input type="number" name="agent_point" class="input input-bordered" value="<?= $site_meta_row['agent_point'] ?>" />
        </div>

        <div class="form-control">
            <label class="label"><span class="label-text">매장 적립금</span></label>
            <input type="number" name="store_point" class="input input-bordered" value="<?= $site_meta_row['store_point'] ?>" />
        </div>

        <div class="form-control">
            <button type="submit" class="btn btn-primary">저장</button>
        </div>
    </form>
</div>

<script>
    function handle_site_meta(e) {
        e.preventDefault();

        const target = $(e.target);
        const serial = target.serialize();

        $.ajax({
            type: "POST",
            url: "/admin/setting/save_site_meta",
            data: serial,
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    alert(response.message);
                } else {
                    alert("오류: " + response.message);
                }
            }
        });
    }
</script>