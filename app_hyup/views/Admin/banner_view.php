<style>
    label {
        width: 160px;
    }
</style>

<div class="">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold mb-6">배너관리</h2>

        <button onclick="handle_banner(event);" type="button" class="btn btn-sm btn-primary">배너 저장</button>
    </div>

    <!-- Upload -->
    <div class="!my-2">
        <p class="!text-sm text-gray-500 mb-2">
            권장 사이즈 : 1920 x 650 (16:9 비율)<br>
            배너는 최대 3장까지 업로드 가능합니다.<br>
        </p>
    </div>

    <!-- 파일 업로드 영역 -->
    <div class="relative !my-2">
        <!-- 숨겨진 파일 입력 -->
        <input type="file" class="file_uploads hidden" id="file_1" accept="image/*" multiple>
        <!-- 클릭 가능한 UI -->
        <div
            class="border border-dashed rounded flex items-center justify-center w-full h-24 cursor-pointer my-2 bg-gray-100"
            onclick="document.getElementById('file_1').click()">
            <span class="!text-2xl text-gray-400">+</span>
        </div>
    </div>
</div>


<div class="gap-2 flex flex-wrap flex-col  overflow-y-auto">
    <?

    $banner_images = $banner_row['image_url'] ?? '';
    $banner_images = explode(',', $banner_images);

    for ($i = 1; $i <= 3; $i++) {
    ?>
        <div id="previewContainer_<?= $i ?>" class="preview-container">

            <?
            if (!empty($banner_images[$i - 1])) {

                $sub_image = $banner_images[$i - 1];
            ?>
                <div class="image-preview relative inline-block mr-2 mb-2 overflow-hidden rounded-md border">
                    <img src="<?= $sub_image ?>" />
                    <button type="button" onclick="remove_image_db(event, <?= $i ?>);" class="remove-btn absolute top-1 right-1 bg-opacity-80 rounded-full bg-[#4b4f53] w-[30px] h-[30px] p-2 flex items-center justify-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x !text-[#fff] font-semibold">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>
            <?
            }
            ?>
        </div>
    <?
    }
    ?>
</div>

<script>
    let imageFiles = []; // 실제 전송할 이미지 저장소

    $("#file_1").on("change", function() {
        const files = Array.from(this.files);
        const maxCount = 3;

        if (imageFiles.length + files.length > maxCount) {
            alert("최대 3장까지 업로드 가능합니다.");
            return;
        }

        files.forEach((file) => {
            imageFiles.push(file); // ✅ 여기 저장
            const reader = new FileReader();

            reader.onload = function(e) {

                const preview = $(`
        <div class="image-preview2 relative inline-block mr-2 mb-2 overflow-hidden rounded-md border">
              <img src="${e.target.result}" class="w-full h-full object-cover" />
              <button type="button" class="remove-btn absolute top-1 right-1 bg-opacity-80 rounded-full bg-[#4b4f53] w-[24px] h-[24px] p-1 flex items-center justify-center text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 6 6 18"></path>
                  <path d="m6 6 12 12"></path>
                </svg>
              </button>
            </div>
      `);

                preview.find(".remove-btn").on("click", function() {
                    const index = preview.index(); // 위치로 판단하거나, 다른 방식으로 식별
                    imageFiles.splice(index, 1); // ✅ 배열에서도 제거
                    preview.remove();
                });

                for (let i = 1; i <= maxCount; i++) {
                    const container = $(`#previewContainer_${i}`);
                    if (container.children().length === 0) {
                        container.append(preview);
                        break;
                    }
                }
            };

            reader.readAsDataURL(file);
        });

        this.value = ""; // 초기화 OK — imageFiles에 따로 저장했으므로
    });

    function handle_banner(e) {

        e.preventDefault();

        if (imageFiles.length === 0) {
            alert("배너 이미지를 업로드해주세요.");
            return;
        }

        const formData = new FormData();
        imageFiles.forEach((file, index) => {
            formData.append(`banner_image[${index}]`, file);
        });

        $.ajax({
            type: "POST",
            url: "/admin/banner/save_banner",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    alert(response.message);
                    location.reload(); // 페이지 새로고침
                } else {
                    alert("오류: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX 오류:", status, error);
                alert("배너 저장 중 오류가 발생했습니다.");
            }
        });
    }

    function remeov_image(e) {
        const target = $(e.target).closest('.image-preview');
        target.remove(); // 미리보기 이미지 제거
    }

    function remove_image_db(e, imageId) {

        if (!confirm("정말로 이 배너 이미지를 삭제하시겠습니까?")) {
            return;
        }

        $.ajax({
            type: "POST",
            url: "/admin/banner/remove_image",
            data: {
                imageId: imageId
            },
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    alert(response.message);
                    location.reload(); // 페이지 새로고침
                } else {
                    alert("오류: " + response.message);
                }
            }
        });

    }

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