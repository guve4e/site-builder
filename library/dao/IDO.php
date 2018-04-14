<?php
/**
 * Created by PhpStorm.
 * User: guve4
 * Date: 4/14/2018
 * Time: 4:43 PM
 */

interface IDO
{
    /**
     * Get resource.
     * @param int $id
     * @return mixed
     */
    public function get(int $id);

    /**
     * Create resource.
     * @param int $id
     * @return mixed
     */
    public function create(int $id);

    /**
     * Update Resource.
     * @param int $id
     * @return mixed
     */
    public function update(int $id);

    /**
     * Delete Resource.
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}