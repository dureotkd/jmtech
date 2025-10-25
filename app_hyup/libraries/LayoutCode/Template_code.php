<?php

class template_code
{

    public function __construct()
    {
        $this->obj = &get_instance();

        // 레이아웃 코드 모델 로드
        $this->obj->load->library('/Service/user_service');

        $this->obj->load->model('template_code_model');
        $this->obj->load->model('/Page/service_model');
    }

    public function getUseData($aside = false)
    {
        @session_start();


        $login_user = $this->obj->user_service->getLoginUser();
        $cart_data = !empty($login_user) ? json_decode(get_cookie('cart_' . $login_user['id']), true) : null;

        // ^ 정지회원
        if (!empty($login_user) && $login_user['status'] == 'S') {

            echo "해당 계정은 정지회원입니다. 관리자에게 문의해주세요.";
            exit;
        }

        $order_status_group = [
            'paid' => 0,
            'shipped' => 0,
            'completed' => 0,
            'canceled' => 0,
        ];

        $order_status_group_db = !empty($login_user) ? $this->obj->service_model->exec_sql(
            'all',
            "SELECT
                COUNT(*) as cnt,
                status
            FROM
                mosihealth.order_item
            WHERE
                user_id = '{$login_user['id']}'
            GROUP BY
                status"
        ) : [];

        if (!empty($order_status_group_db)) {

            foreach ($order_status_group_db as $row) {
                $order_status_group[$row['status']] = $row['cnt'];
            }
        }

        $menus = $this->get_menus();

        $datas =  [
            'login_user' => $login_user,
            'menus' => $menus,
        ];

        return $datas;
    }

    private function get_menus()
    {

        $menus = [
            'sales'    => [
                'name'      => '판매관리',
                'sub'             => [
                    'sales'          => [
                        'name'            => '판매관리',
                        'path'            => '/point/withdraw_req',
                        'auth_level'    => [11],
                        'sub'             => [
                            'report'          => [
                                'name'            => '매출(거래명세표)',
                                'path'            => '/sales/report',
                                'auth_level'    => [11]
                            ],
                            'estimate'          => [
                                'name'            => '견적서',
                                'path'            => '/sales/estimate',
                                'auth_level'    => [11]
                            ],
                            'order'          => [
                                'name'            => '수주서',
                                'path'            => '/sales/order',
                                'auth_level'    => [11]
                            ],
                        ]
                    ],
                    'sales_document'          => [
                        'name'            => '매출증빙',
                        'path'            => '/sales_document/tax_bill',
                        'auth_level'    => [11],
                        'sub'             => [
                            'tax_bill'          => [
                                'name'            => '매출세금계산서(현영/기타)',
                                'path'            => '/sales_document/tax_bill',
                                'auth_level'    => [11]
                            ],
                        ]
                    ],
                    'sales_profit'          => [
                        'name'            => '매출/손익현황',
                        'path'            => '/sales_profit/report',
                        'auth_level'    => [11],
                        'sub'             => [
                            'partner_report'          => [
                                'name'            => '매출처현황',
                                'path'            => '/sales_profit/partner_report',
                                'auth_level'    => [11]
                            ],
                            'sales_report'          => [
                                'name'            => '매출보고서',
                                'path'            => '/sales_profit/sales_report',
                                'auth_level'    => [11]
                            ],
                            'profit_report'          => [
                                'name'            => '손익보고서',
                                'path'            => '/sales_profit/profit_report',
                                'auth_level'    => [11]
                            ],
                        ]
                    ],
                ],
                // 'sub'     =>  [
                //     'report'    => [
                //         'name'            => '매출(거래명세표)',
                //         'path'            => '/sales/report',

                //     ],
                //     'estimate'        => [
                //         'name'            => '견적서',
                //         'path'            => '/sales/estimate',
                //     ],
                //     'order'        => [
                //         'name'            => '수주서',
                //         'path'            => '/sales/order',
                //     ],
                // ],
                'auth_level'    => [11, 10, 2, 3]
            ],
            'purchase'    => [
                'name'    => '구매관리',
                'sub'        => [
                    'report'              => [
                        'name'            => '매입(거래명세표)',
                        'path'            => '/purchase/report',
                    ],
                    'order' => [
                        'name'            => '발주서',
                        'path'            => '/purchase/order',
                        'sub'             => []
                    ],

                    'withdraw' => [
                        'name'            => '매입증빙',
                        'path'            => '/point/withdraw_list',
                        'sub'             => [
                            'withdraw_req'          => [
                                'name'            => '출금신청',
                                'path'            => '/point/withdraw_req',
                                'auth_level'    => [11]
                            ],
                            'withdraw_suc'          => [
                                'name'            => '출금완료',
                                'path'            => '/point/withdraw_suc',
                                'auth_level'    => [11]
                            ],
                            'withdraw_hold'          => [
                                'name'            => '출금보류',
                                'path'            => '/point/withdraw_hold',
                                'auth_level'    => [11]
                            ],
                        ]
                    ],
                    'cash_receipt_list'       => [
                        'name'            => '현금영수증 신청내역',
                        'path'            => '/point/cash_receipt_list',
                        'auth_level'    => [11]
                    ],
                    'credit_list'           => [
                        'name'            => '전체 충전내역',
                        'path'            => '/point/credit_list',
                        'auth_level'    => [11]
                    ],
                ],
                'auth_level'    => [11, 10]
            ],

            'setting'    => [
                'name'    => '공지사항',
                'sub'     =>  [
                    'list'        => [
                        'name'            => '공지사항관리',
                        'path'            => '/notice/notice_list',
                    ],
                    'faq_list'        => [
                        'name'            => 'FAQ관리',
                        'path'            => '/notice/faq_list',
                    ],
                ],
            ],

            'contract'    => [
                'name'    => '공지사항',
                'sub'     =>  [
                    'list'        => [
                        'name'            => '공지사항관리',
                        'path'            => '/notice/notice_list',
                    ],
                    'faq_list'        => [
                        'name'            => 'FAQ관리',
                        'path'            => '/notice/faq_list',
                    ],
                ],
            ],
        ];

        return $menus;
    }
}
