<?php


class User extends BaseApp
{

    public $id = "";
    public $name = "";
    public $mail = "";
    public $password = "";
    public $settings = "";
    public $api_key = "";
    public $auth_cookie = "";
    public $created = "";

    public function getPrimary()
    {
        return "id";
    }

}
