<?php

require_once (LIBRARY_PATH . "/dao/IDO.php");

class Product implements IDO
{

    /**
     * Get resource.
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        return json_decode(json_encode([
            "product_count"=> 3,
            "products" => [
                [
                    "name" => "product1",
                    "price" => 12.4
                ],
                [
                    "name" => "product2",
                    "price" => 4.56
                ],
                [
                    "name" => "product3",
                    "price" => 15.4
                ]
            ]
        ]));
    }

    /**
     * Create resource.
     * @param int $id
     * @return mixed
     */
    public function create($id)
    {
        // TODO: Implement create() method.
    }

    /**
     * Update Resource.
     * @param int $id
     * @return mixed
     */
    public function update($id)
    {
        // TODO: Implement update() method.
    }

    /**
     * Delete Resource.
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}