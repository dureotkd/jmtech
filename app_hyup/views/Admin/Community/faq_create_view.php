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
<link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css">


<div class="!space-y-2">
    <h2 class="!text-lg font-bold mb-6">FAQ 등록</h2>

    <form method="post" enctype="multipart/form-data" class="!space-y-6 !mt-12">

        <input type="hidden" name="id" value="<?= @$faq_row['id'] ?? '' ?>" />

        <div>
            <label class="label font-semibold">질문</label>
            <input type="text" name="question" value="<?= @$faq_row['question'] ?>" class="input input-bordered w-full" placeholder="회원 가입 / 등급 혜택 안내" />
        </div>

        <div class="flex flex-col gap-1">
            <label class="label font-semibold">카테고리</label>
            <?
            $FAQ_CATEGORY_VO = unserialize(FAQ_CATEGORY);
            ?>

            <select name="category" class="select">
                <?
                foreach ($FAQ_CATEGORY_VO as $key => $value) {
                ?>
                    <option value="<?= $key ?>" <?= (@$faq_row['category'] == $key) ? 'selected' : '' ?>><?= $value ?></option>
                <?
                }
                ?>
            </select>
        </div>

        <div>
            <label class="label font-semibold">답변</label>
            <input type="hidden" name="content" value="<?= $faq_row['answer'] ?? '' ?>" />
            <div id="editor" class="!mt-2">

            </div>
        </div>

        <!-- 등록 버튼 -->
        <div class="text-center pt-4">
            <button type="submit" class="btn btn-primary px-8">등록</button>
        </div>
    </form>
</div>

</div>

<script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>

<!-- CSS & JS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>


<script>
    const quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'], // 굵게, 기울임 등
                [{
                    color: []
                }, {
                    background: []
                }], // 글자색, 배경색
                [{
                    header: [1, 2, 3, false]
                }], // 제목 크기
                [{
                    list: 'ordered'
                }, {
                    list: 'bullet'
                }], // 리스트
                ['link', 'image'], // 링크, 이미지
                ['clean'] // 초기화
            ]
        },
        placeholder: '내용을 입력하세요...'
    });

    quill.clipboard.dangerouslyPasteHTML(<?= json_encode($faq_row['answer'] ?? '') ?>);


    // ✅ 1. 내용 변경 시 hidden input에 HTML 저장
    quill.on('text-change', function() {
        document.querySelector("input[name='content']").value = quill.root.innerHTML;
    });

    $('form').on('submit', function(e) {
        e.preventDefault(); // 기본 폼 제출 막기

        const form = $(this)[0];
        const formData = new FormData(form);

        $.ajax({
            url: '/admin/community/faq/create_faq', // 실제 처리할 PHP 경로
            type: 'POST',
            data: formData,
            contentType: false, // 중요!
            processData: false, // 중요!
            dataType: 'json',
            success: function(response) {

                alert(response.msg);
                window.location.href = '/admin/community/faq';

            },
            error: function(xhr, status, error) {
                alert('에러 발생: ' + error);
            }
        });
    });

    function handle_rating(e) {

        alert(e.target.value);
    }

    function handle_update_modal(id) {
        // 여기서 id를 사용하여 상품 정보를 불러오고, 모달에 데이터를 채워넣는 로직을 구현해야 합니다.

        my_modal_1.show();
    }
</script>