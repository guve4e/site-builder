<?php

require_once (LIBRARY_PATH . "/dao/IDO.php");
require_once (LIBRARY_PATH . "/user-session/SessionHelper.php");
require_once (HTTP_PATH . "/Http.php");

class User implements IDO
{
    /**
     * Get resource.
     * @param int $id
     * @return mixed
     */
    public function authenticate($id, stdClass $fields)
    {
        // Fake call to back end
        $authenticated = $fields->login_username == "root" && $fields->login_password == "pass";
        if ($authenticated) {
            $_SESSION['authenticated_user'] = true;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function get($id)
    {
        $r = new Http();
        $r->setWebService("webapi")
            ->setParameter($id)
            ->setMethod("GET")
            ->setMock();

        if (isset($_SESSION["authenticated_user"]))
            $r->setService("authenticated-user");
        else
            $r->setService("user");

        $user = $r->send();

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