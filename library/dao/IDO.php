<?php

interface IDO
{
    /**
     * Get resource.
     * @param int $id
     * @return mixed
     */
    public function get($id);

    /**
     * Create resource.
     * @param int $id
     * @return bool
     */
    public function create($id, object $data): bool;

    /**
     * Update Resource.
     * @param int $id
     * @return bool
     */
    public function update($id, object $data): bool;

    /**
     * Delete Resource.
     * @param int $id
     * @return bool
     */
    public function delete($id, object $data): bool;
}