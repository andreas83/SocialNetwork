<?php


class User extends BaseApp
{

    public $id = "";
    public $name = "";
    public $mail = "";
    public $password = "";
    public $settings = "";
    public $created = "";

    public function getPrimary()
    {
        return "id";
    }

}
