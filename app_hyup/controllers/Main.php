<?php

class main extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();


        $this->load->library([
            "layout",
            "/Service/user_service",
            "/Service/recipe_service",
            "/Service/product_service"
        ]);

        $this->load->model('/Page/service_model', null, 'service_model');
    }

    public function index()
    {

        $token = $this->input->get('token');
        $is_reset = false;

        if (!empty($token)) {

            $is_reset = $this->service_model->get_password_reset_token('one', [
                "token = '{$token}'",
                "expires_at < NOW()",
                "status = 1"
            ]);
        }

        $login_user = $this->user_service->getLoginUser();

        $banner = $this->service_model->get_banner('row', [
            "id = 1",
        ]);
        $products = $this->product_service->all(10);
        $recipes = $this->recipe_service->all(10);

        $social_type = $login_user['social_type'] ?? '';
        $총판코드기입여부 = $social_type == 'service' ? 'N' : 'Y';

        if (!empty($login_user)) {

            if (!empty($login_user['store_code'])) {
                $총판코드기입여부 = 'N';
            }

            $store_code_view = $_COOKIE['store_code_view'] ?? 'Y';

            if ($store_code_view == 'N') {

                $총판코드기입여부 = 'N';
            }
        } else {

            $총판코드기입여부 = 'N';
        }

        /**
         * Array
(
    [layout_data] => Array
        (
            [top_menu_code] => base
            [sub_menu_code] => banner
        )

    [products] => Array
        (
            [0] => Array
                (
                    [id] => 4
                    [name] => 마이노멀 저당 아이스바
                    [description] => 리얼 과육이 들어간 저당 아이스바 2종!

입 안 가득 퍼지는 상큼함으로 무더위 건강하게 함께 날려요.
올해 우리집 여름 간식은 마이노멀 저당 아이스바로 해결

✔️ 당류 오직 1g! 칼로리 25kcal!
✔️ 딸기 / 청사과 2가지 맛으로 입안 가득 느껴지는 상큼함
✔️ 진짜 과육이 들어가서 더욱 아삭하고 새콤달콤하게
✔️ 비타민 C 1일 기준치 100% 함유
                    [price] => 50000
                    [stock] => 0
                    [category_id] => 
                    [image_url] => https://mosihealth.test/assets/app_hyup/uploads/products/6861315f42c2a.png
                    [created_at] => 2025-06-29 21:18:48
                    [detail_image_urls] => https://mosihealth.test/assets/app_hyup/uploads/products/68612f2899307.png,https://mosihealth.test/assets/app_hyup/uploads/products/68612f28996f4.png
                    [recipe_ids] => 
                    [detail_image_urls2] => https://mosihealth.test/assets/app_hyup/uploads/products/68612f2899aa9.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f2899e0a.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f289a0fb.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f289a491.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f289a7d2.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f289aaef.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f289ae93.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f289b220.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f28a0ed3.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f28a12b4.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f28a1698.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f28a19e3.jpg
                    [ori_price] => 50000
                )

        )

    [recipes] => Array
        (
            [0] => Array
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
                )

        )

    [login_user] => Array
        (
        )

    [token] => 
    [is_reset] => 
)
         */

        $view_data =  [
            'layout_data'           => $this->layout_config(),
            'products'              => $products,
            'recipes'               => $recipes,
            'login_user'            => $login_user,
            'token'                 => $token,
            'is_reset'              => $is_reset,
            'banner'                => $banner,
            '총판코드기입여부'       => $총판코드기입여부,
        ];

        $this->layout->view('main_view', $view_data);
    }

    public function init_store_code()
    {

        $res_array = [
            'ok' => true,
            'msg' => '총판코드가 저장되었습니다.',
        ];

        $store_code = $this->input->post('store_code');

        try {

            $this->user_service->initStoreCode($store_code);
        } catch (Exception $e) {

            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
        exit;
    }

    private function layout_config()
    {

        $this->layout->setLayout("layout/template");
        $this->layout->setCss([]);
        $this->layout->setScript([]);
        $this->layout->setHeader(false);
        $this->output->enable_profiler(false);
        return [
            'top_menu_code'    => 'sales',
            'sub_menu_code'    => 'estimate',
        ];
    }
}
