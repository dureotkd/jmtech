<?php

class recipe extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");

        $this->load->library("/Service/user_service");
        $this->load->library("/Service/recipe_service");

        $this->load->model('/Page/service_model');
    }

    public function index()
    {
        $recipe_id = $this->input->get('id');

        if ($recipe_id) {

            $this->recipe_detail_view($recipe_id);
        } else {

            $this->recipe_list_view();
        }
    }

    private function recipe_list_view()
    {
        $recipes = $this->recipe_service->all(10);

        $view_data = [
            'layout_data' => $this->layout_config(),
            'recipes' => $recipes,
        ];

        $this->layout->view('recipe_list_view', $view_data);
    }

    private function recipe_detail_view($recipe_id)
    {
        if (empty($recipe_id)) {
            show_404();
        }

        /**
         * Array
(
    [id] => 6
    [product_id] => 0
    [title] => 토마토마리네이드
    [image_url] => https://mosihealth.test/assets/app_hyup/uploads/reciepe/68613f76042d9.jpg
    [created_at] => 2025-06-29 22:28:22
    [updated_at] => 2025-06-29 22:28:23
    [content] => <p>새콤달콤 여름 반찬</p><h2><strong style="color: rgb(178, 132, 65);">저당 토마토 마리네이드</strong></h2><p><br></p><p>난이도&nbsp;&nbsp;&nbsp;&nbsp;★☆☆☆☆</p><p>소요시간&nbsp;&nbsp;25분</p><p>사용제품&nbsp;&nbsp;마이노멀 액상 알룰로스, 마이노멀 저당 매실청</p><p><br></p><p>향긋하고 달콤한데 가뿐하기까지!</p><p>당류, 칼로리 걱정 없는 알룰로스, 저당 매실청으로 달콤하고 건강한 저당 토마토 마리네이드를 만들어보세요.</p><p><br></p><h2><strong style="color: rgb(178, 132, 65);">재료</strong></h2><p><br></p><p>방울토마토 500g,</p><p>마이노멀 저당 매실청 300ml, 마이노멀 액상 알룰로스 1T</p><p><br></p><p><br></p><h2><strong style="color: rgb(178, 132, 65);">레시피</strong></h2><p><br></p><p><img src="https://cdn.imweb.me/upload/S20200629b683866f4b1fb/0120d4bf6d5c5.jpg"></p><p><br></p><p>1.</p><p>방울토마토는 깨끗이 씻어 十 모양으로 칼집을 냅니다.&nbsp;</p><p><br></p><p><br></p><p><img src="https://cdn.imweb.me/upload/S20200629b683866f4b1fb/34f895dd25cc0.jpg"></p><p><br></p><p>2.</p><p>끓는 물에 방울토마토를 한 번 데쳐주세요. (30초 내외) 껍질이 살짝 분리되는 게 보이면 건져요.&nbsp;</p><p><br></p><p><br></p><p><img src="https://cdn.imweb.me/upload/S20200629b683866f4b1fb/3b476eb009dc2.jpg"></p><p><br></p><p>3.</p><p>데친 토마토를 차가운 얼음물에 담그면 껍질이 잘 벗겨져요.&nbsp;</p><p><br></p><p><br></p><p><img src="https://cdn.imweb.me/upload/S20200629b683866f4b1fb/76833f5e0100e.jpg"></p><p><br></p><p>4.</p><p>껍질을 벗긴 토마토를 볼에 넣고 마이노멀 매실청 300ml 넣어 잘 섞어주세요.</p><p><br></p><p>&nbsp;</p><p><img src="https://cdn.imweb.me/upload/S20200629b683866f4b1fb/f87f021735cee.jpg"></p><p><br></p><p>5.</p><p>열탕 소독한 유리병에 담으면 완성!&nbsp;</p><p>실온에서 반나절 두었다가 냉장고에서 하루 정도 숙성해서 드세요.</p><p><br></p><p><br></p><p><img src="https://cdn.imweb.me/upload/S20200629b683866f4b1fb/f20e338147720.jpg"></p><p><br></p><p><br></p><p>맛있게 드세요!</p>
    [level] => 5
    [cooking_time] => 30
    [product] => 
)
         */
        $recipe = $this->recipe_service->get($recipe_id);

        $view_data = [
            'layout_data' => $this->layout_config(
                [
                    'title' => $recipe['title'] . ' | 제이엠테크',
                ]
            ),
            'recipe' => $recipe,
            'recipe_id' => $recipe_id,
        ];

        $this->layout->view('recipe_view', $view_data);
    }

    private function layout_config($params = [])
    {
        $title = $params['title'] ?? '레시피 | 제이엠테크';
        $description = $params['description'] ?? '제이엠테크 레시피 페이지입니다. 다양한 레시피를 확인하고 따라할 수 있습니다.';

        $this->layout->setLayout("layout/template");
        $this->layout->setTitle($title);
        $this->layout->setDescription($description);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'banner',
        ];
    }
}
