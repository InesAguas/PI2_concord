<?php

namespace App\Classes;

use \PDO;
use App\Classes\User;

class GereUsers {

    public function __construct($link) {
        $this->pdo = $link;
    }


    public function selecionarUser($username) {
        $st = $this->pdo->prepare("SELECT * FROM users WHERE u_username = ? ;");
        $st->bindParam(1,$username);
        $st->execute();

        $st->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        
        return $st->fetchAll();
    }

    public function selecionarUserID($uid) {
        $st = $this->pdo->prepare("SELECT * FROM users WHERE u_id = ? ;");
        $st->bindParam(1,$uid);
        $st->execute();

        $st->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        
        return $st->fetchAll();
    }

    public function inserirUser($name, $username, $pass, $avatar, $token) {

        $st = $this->pdo->prepare("INSERT INTO users (u_name, u_username, u_pass, u_avatar, u_token) VALUES (?, ?, ?, ?, ?) ;");

        $pass = password_hash($pass, PASSWORD_DEFAULT);

        $st->bindParam(1, $name);
        $st->bindParam(2, $username);
        $st->bindParam(3, $pass);
        $st->bindParam(4, $avatar);
        $st->bindParam(5, $token);

        $st->execute();

        return ($st->rowCount() == 1 ? true : false);
    }

    public function updateToken($token, $uid) {
        $st = $this->pdo->prepare("UPDATE users SET u_token = ? WHERE u_id = ? ;");

        $st->bindParam(1, $token);
        $st->bindParam(2, $uid);

        $st->execute();
    }

    public function selecionaUserToken($token) {
        $st = $this->pdo->prepare("SELECT * FROM users WHERE u_token = ? ;");
        $st->bindParam(1,$token);
        $st->execute();

        $st->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);

        return $st->fetchAll();
    }

    public function editarUser($name, $username, $pass, $avatar, $uid) {
        $st = $this->pdo->prepare("UPDATE users SET u_name = ?, u_username = ?, u_pass = ?, u_avatar = ? WHERE u_id = ? ;");

        $pass = password_hash($pass, PASSWORD_DEFAULT);
        
        $st->bindParam(1, $name);
        $st->bindParam(2, $username);
        $st->bindParam(3, $pass);
        $st->bindParam(4, $avatar);
        $st->bindParam(5, $uid);

        $st->execute();

        return ($st->rowCount() == 1 ? true : false);
    }


}