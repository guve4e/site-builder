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
            ->setService("product")
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
    public function create($id, object $data)
    {
        // TODO: Implement create() method.
    }

    /**
     * Update Resource.
     * @param int $id
     * @return mixed
     */
    public function update($id, object $data)
    {
        // set options
        $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        $path = MOCK_RESOURCES_PATH . "/" . "product.json";
        $fp = fopen($path, 'w');
        fwrite($fp, json_encode($data, $options));
        fclose($fp);

        return true;
    }

    /**
     * Delete Resource.
     * @param int $id
     * @return mixed
     */
    public function delete($id, object $data)
    {
        // TODO: Implement delete() method.
    }
}