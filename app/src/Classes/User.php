<?php

namespace App\Classes;

class User {

    private int $u_id;
    private string $u_name;
    private string $u_username;
    private string $u_pass;
    private string $u_avatar;
    private string $u_token;


    public function __construct(int $u_id = -1, string $u_name = "", string $u_username = "", int $u_pass = -1, string $u_avatar = "", string $u_token = "") {
        $this->u_id = $u_id;
        $this->u_name = $u_name;
        $this->u_username = $u_username;
        $this->u_pass = $u_pass;
        $this->u_avatar = $u_avatar;
        $this->u_token = $u_token;
    }


    public function getId() {
        return $this->u_id;
    }

    public function getName() {
        return $this->u_name;
    }

    public function getUsername() {
        return $this->u_username;
    }

    public function getPass() {
        return $this->u_pass;
    }

    public function getAvatar() {
        return $this->u_avatar;
    }

    public function getToken() {
        return $this->u_token;
    }

    public function setName($name) {
        $this->u_name = $name;
    }

    public function setUsername($username) {
        $this->u_username = $username;
    }

    public function setPass($pass) {
        $this->u_pass = $pass;
    }

    public function setAvatar($avatar) {
        $this->u_avatar = $avatar;
    }


}