<?php

namespace App\Classes;

use \PDO;

class GereMessages {

    public function __construct($link) {
        $this->pdo = $link;
    }

    public function inserirMessage($message, $rid, $uid) {
        $st = $this->pdo->prepare("INSERT INTO messages (m_date, m_message, r_id, u_id) VALUES (NOW(), ?, ?, ?) ;");

        $st->bindParam(1, $message);
        $st->bindParam(2, $rid);
        $st->bindParam(3, $uid);

        $st->execute();

        return ($st->rowCount() == 1 ? true : false);
    }

    public function getRoomMessages($rid) {
        
        $st = $this->pdo->prepare("SELECT * FROM messages WHERE r_id = ? ;");

        $st->bindParam(1, $rid);

        $st->execute();

        $st->setFetchMode(PDO::FETCH_OBJ);
        
        return $st->fetchAll();
    }

    public function deleteMessage($mid) {
        $st = $this->pdo->prepare("UPDATE messages SET m_active = 0 WHERE m_id = ? ;");

        $st->bindParam(1, $mid);

        $st->execute();
    }

    public function mensagemExiste($rid, $mid, $uid) {

        $st = $this->pdo->prepare("SELECT * FROM messages WHERE r_id = ? AND m_id = ? AND u_id = ?;");

        $st->bindParam(1, $rid);
        $st->bindParam(2, $mid);
        $st->bindParam(3, $uid);

        $st->execute();

        return ($st->rowCount() == 1 ? true : false);
    }


}