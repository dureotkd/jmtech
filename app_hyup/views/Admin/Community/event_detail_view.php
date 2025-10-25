<style>
    th {
        font-weight: normal;
        width: 106px;
        padding: 7px 5px 6px 0;
        text-align: left;
        vertical-align: top;
    }

    td {
        padding: 7px 6px 8px 0;
        vertical-align: middle;
    }

    .cc input {
        width: 45px;
        height: 30px;
        line-height: 28px;
        margin-left: -1px;
        padding: 0;
        border: 1px solid #e5e5e5;
        text-align: center;
    }

    .cc button {
        width: 30px;
        height: 30px;
        border: 1px solid #e5e5e5;
        box-sizing: border-box;
        overflow: hidden;
        white-space: nowrap;
        text-indent: 150%;
        color: transparent;
        font-size: 1px;
        line-height: 1px;
    }

    .cc button:before {
        content: '';
        position: absolute;
        left: 10px;
        top: 50%;
        width: 9px;
        height: 1px;
        background: #000;
    }


    .cc button:after {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        width: 1px;
        height: 9px;
        margin: -4px 0 0 0;
        background: #000;
    }
</style>

<div class="font-sans text-sm text-gray-800">
    <div class="!mb-4 flex gap-2">
        <button class="btn" onclick="history.back();">뒤로가기</button>
    </div>

    <div class="lg:flex-row lg:gap-12 gap-4 flex-col flex w-full h-full">


        <!-- 상품 상세 정보 -->
        <div class="lg:w-1/2 lg:!px-0 !px-4 w-full flex flex-col justify-between">

            <div class="!space-y-4">

                <!-- 상단 제목 -->
                <h2 class="lg:!border-t-2 lg:!pt-4 lg:!mb-9 !border-t-0 !border-gray-100 !text-2xl !text-gray-800">
                    <?= $event_row['title'] ?>
                </h2>

                <div class="">
                    <?= nl2br($event_row['content']) ?>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>


<script>
    $(window).on("scroll", function() {
        const sections = $("section[id]"); // id가 있는 section을 모두 가져옴
        const scrollPosition = $(window).scrollTop() + 60; // 여유 여백 조정

        let currentId = "";

        sections.each(function() {
            const sectionTop = $(this).offset().top;
            if (scrollPosition >= sectionTop) {
                currentId = $(this).attr("id");
            }
        });

        console.log(currentId)

        // 메뉴 active 클래스 처리
        if (currentId) {
            $(".menus .active-link").removeClass("active-link");
            $(`[data-target="${currentId}"]`).addClass("active-link");
        }
    });


    function down_quan(event) {
        const quan = $(event.target).siblings('input')
        let count = parseInt(quan.val());

        if (count <= 1) {
            return; // 최소 수량이 1이므로 감소하지 않음
        }

        count--;
        quan.val(count);
    }

    function up_quan(event) {
        const quan = $(event.target).siblings('input')
        let count = parseInt(quan.val());
        count++;
        quan.val(count);
    }

    function go_payment_view() {

        fadeOutButton('/product/order?product_id=1&quantity=1&option_id=2');
    }
</script>