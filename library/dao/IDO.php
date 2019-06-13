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
     * @param stdClass|null $data
     * @return bool
     */
    public function create($id, stdClass $data = null): bool;

    /**
     * Update Resource.
     * @param int $id
     * @param stdClass|null $data
     * @return bool
     */
    public function update($id, stdClass $data = null): bool;

    /**
     * Delete Resource.
     * @param int $id
     * @param stdClass|null $data
     * @return bool
     */
    public function delete($id, stdClass $data = null): bool;
}