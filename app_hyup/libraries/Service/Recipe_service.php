<?php
class Recipe_service
{
    protected $obj;

    public function __construct()
    {
        $this->obj = &get_instance();
        $this->obj->load->library([
            "ajax",
            "/Service/user_service",
            "/Service/product_service",
            "/Service/product_discount_service"
        ]);

        $this->obj->load->model("/Page/service_model");
    }

    # 상품 단건 조회
    public function get($recipe_id)
    {

        $recipe = $this->obj->service_model->get_recipe('row', [
            "id = '{$recipe_id}'"
        ]);
        $recipe['product'] = $this->obj->product_service->get($recipe['product_id']);

        return $recipe;
    }

    # 전체 상품 목록 조회
    public function all($limit = 10)
    {

        $recipeies = $this->obj->service_model->get_recipe('all', [
            1
        ], $limit);

        if (!empty($recipeies)) {


            foreach ($recipeies as $recipe) {

                $recipe['product'][] = $this->obj->product_service->get($recipe['product_id']);
            }
        }

        return $recipeies;
    }
}
