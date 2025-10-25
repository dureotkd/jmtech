<?php

class admin_code
{

    public function __construct()
    {
        $this->obj = &get_instance();

        $this->obj->load->model('/Page/service_model');
    }

    public function getUseData($data = null)
    {
        @session_start();
        $mid = isset($_SESSION['mid']) ? $_SESSION['mid'] : null;

        $manager         = !empty($mid) ? $this->obj->service_model->get_manager('row', [
            "id = '{$mid}'"
        ]) : [];

        $menus              = $this->init_menus();

        return [
            'menus'         => $menus,
            'manager'       => $manager
        ];
    }

    public function init_menus()
    {
        $today_start = date('Y-m-d') . " 00:00:00";
        $today_end = date('Y-m-d') . " 23:59:59";

        // 오늘 가입한 회원수
        $today_join_cnt = $this->obj->service_model->get_user('one', [
            "created_at >= '" . $today_start . "'",
            "created_at <= '" . $today_end . "'"
        ]);

        // 오늘 주문수
        $today_order_item_cnt = $this->obj->service_model->get_order_item('one', [
            "ordered_at >= '" . $today_start . "'",
            "ordered_at <= '" . $today_end . "'"
        ]);

        // 리뷰와 QNA 수
        $today_review_cnt = $this->obj->service_model->get_review('one', [
            "created_at >= '" . $today_start . "'",
            "created_at <= '" . $today_end . "'"
        ]);

        // 답변대기 수
        $product_qna_cnt = $this->obj->service_model->get_product_qna('one', [
            "status = '답변대기'"
        ]);
        $qna_review_cnt = $today_review_cnt + $product_qna_cnt;

        $point_request_cnt = $this->obj->service_model->get_point_request('one', [
            "status = 'pending'"
        ]);

        $menus = [
            'base'    => [
                'sub'     =>  [
                    'setting'    => [
                        'name'            => '기본설정',
                        'path'            => '/admin/setting',
                    ],
                    'banner'     => [
                        'name'            => '배너관리',
                        'path'            => '/admin/banner',
                    ],
                    'store_code'     => [
                        'name'            => '총판코드 관리',
                        'path'            => '/admin/store_code',
                    ],
                    'agent'     => [
                        'name'            => "회원관리 ({$today_join_cnt})",
                        'path'            => '/admin/agent',
                    ],
                    'point'     => [
                        'name'            => "포인트관리 ({$point_request_cnt})",
                        'path'            => '/admin/point',
                    ],
                    // 'user'     => [
                    //     'name'            => '매장관리',
                    //     'path'            => '/admin/user',
                    // ],
                    'product'        => [
                        'name'            => '상품관리',
                        'path'            => '/admin/product',
                    ],
                    'recipe'        => [
                        'name'            => '레시피관리',
                        'path'            => '/admin/recipe',
                    ],
                    'order'       => [
                        'name'            => "주문관리 ({$today_order_item_cnt})",
                        'path'            => '/admin/order',
                    ],
                    'review'        => [
                        'name'            => "리뷰&QNA 관리 ({$qna_review_cnt})",
                        'path'            => '/admin/review',
                    ],
                    'community'    => [
                        'name'    => '커뮤니티 관리',
                        'path'    => '/admin/community/event',
                        'sub'        => [
                            'event'              => [
                                'name'            => '이벤트',
                                'path'            => '/admin/community/event',
                            ],
                            'faq'              => [
                                'name'            => 'FAQ',
                                'path'            => '/admin/community/faq',
                            ],
                            'notice'              => [
                                'name'            => '공지사항',
                                'path'            => '/admin/community/notice',
                            ],
                            // 'benefit'              => [
                            //     'name'            => '혜택',
                            //     'path'            => '/admin/community/benefit',
                            // ],
                        ],
                    ],
                ],
            ],
        ];

        return $menus;
    }
}
