<?php

require_once (LIBRARY_PATH . "/dao/IDO.php");
require_once (HTTP_PATH . "/Http.php");

class Product implements IDO
{

    /**
     * Get resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function get($id)
    {
        $r = new Http();
        $res = $r->setWebService("webapi")
            ->setService("cart")
            ->setParameter($id)
            ->setMethod("GET")
            ->setMock()
            ->send();

        return $res;
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