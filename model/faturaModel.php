<?php

namespace model;
use PDO;

require_once "connection.php";
class faturaModel
{
    private $pdo;
    function __construct()
    {
        $this->pdo = getPDO();
    }

    public function Listele()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM  faturalar");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function Ekle($fatura)
    {
        $stmt= $this->pdo->prepare("INSERT INTO faturalar(fatura_no,tutar,adres,birim) VALUES(:fatura_no,:tutar,:adres,:birim) ");
        $stmt->bindParam(':fatura_no', $fatura["fatura_no"]);
        $stmt->bindParam(':tutar', $fatura["tutar"]);
        $stmt->bindParam(':adres', $fatura["adres"]);
        $stmt->bindParam(':birim', $fatura["birim"]);

        return $stmt->execute();
    }

    public function Sil($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM faturalar WHERE id = :id");
        $stmt->bindParam(':id', $id,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function Getir($faturaNo)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM faturalar WHERE fatura_no = :fatura_no");
        $stmt->bindParam(':fatura_no', $faturaNo,PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function FaturaBul($faturaId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM faturalar WHERE id = :faturaId");
        $stmt->bindParam(':faturaId', $faturaId,PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}