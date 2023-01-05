<?php

namespace App\Classes;

use \PDO;

class GereRooms {

    public function __construct($link) {
        $this->pdo = $link;
    }

    public function listarRooms() {
        $st = $this->pdo->query("SELECT * FROM rooms;");

        $st->setFetchMode(PDO::FETCH_OBJ);
        
        return $st->fetchAll();
    }
    
    public function inserirRoom($name, $uid) {
        $st = $this->pdo->prepare("INSERT INTO rooms (r_name, u_id) VALUES (?, ?) ;");

        $st->bindParam(1, $name);
        $st->bindParam(2, $uid);

        $st->execute();

        return ($st->rowCount() == 1 ? true : false);
    }

    public function roomExiste($rid) {
        $st = $this->pdo->prepare("SELECT * FROM rooms WHERE r_id = ? ;");

        $st->bindParam(1, $rid);

        $st->execute();

        return ($st->rowCount() == 1 ? true : false);
    }

}