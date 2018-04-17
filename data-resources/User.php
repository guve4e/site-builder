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
    public function create($id, stdClass $data): bool
    {
        // TODO: Implement create() method.
    }

    /**
     * Update Resource.
     * @param int $id
     * @return mixed
     */
    public function update($id, stdClass $data): bool
    {
        // TODO: Implement update() method.
    }

    /**
     * Delete Resource.
     * @param int $id
     * @return mixed
     */
    public function delete($id, stdClass $data): bool
    {
        // TODO: Implement delete() method.
    }
}