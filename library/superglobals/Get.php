<?php

require_once ("ISuperglobal.php");

class Get implements ISuperglobal
{
    public static function getValue($key)
    {
        $get = [];
        foreach ($_GET as $key => $value) {
            $get[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
        }
        return $get;
    }
}