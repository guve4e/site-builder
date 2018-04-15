<?php

require_once (LIBRARY_PATH . "/dao/IDO.php");

class User implements IDO
{
    /**
     * Get resource.
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        $user = new StdClass;
        $user->id = 1001;
        $user->product_count = 12;
        return $user;
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