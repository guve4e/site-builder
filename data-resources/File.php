<?php
require_once (LIBRARY_PATH . "/dao/IDO.php");

class File implements IDO
{
    /**
     * Get resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function get($id)
    {

    }

    /**
     * Create resource.
     * @param int $id
     * @return mixed
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

    public function add($id, stdClass $data): bool
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
        // TODO: Implement delete() method.
    }
}