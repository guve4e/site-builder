<?php

require_once (LIBRARY_PATH . "/dao/IDO.php");
require_once (LIBRARY_PATH . "/user-session/SessionHelper.php");
require_once (HTTP_PATH . "/Http.php");

class TestDataResource implements IDO
{
    /**
     * Get resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function get($id)
    {
        $obj = new StdClass;
        $obj->key = "value";
        return $obj;
    }

    /**
     * Create resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function create($id, stdClass $data = null): bool
    {
        return true;
    }

    /**
     * Update Resource.
     * @param int $id
     * @return mixed
     */
    public function update($id, stdClass $data = null): bool
    {
        return true;
    }

    /**
     * Delete Resource.
     * @param int $id
     * @return mixed
     */
    public function delete($id, stdClass $data = null): bool
    {
        return false;
    }
}