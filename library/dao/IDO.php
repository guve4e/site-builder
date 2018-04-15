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
     * @return mixed
     */
    public function create($id);

    /**
     * Update Resource.
     * @param int $id
     * @return mixed
     */
    public function update($id);

    /**
     * Delete Resource.
     * @param int $id
     * @return mixed
     */
    public function delete($id);
}