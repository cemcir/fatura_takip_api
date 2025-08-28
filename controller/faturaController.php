<?php

namespace controller;
use model\faturaModel;

require_once "model/faturaModel.php";

class faturaController
{
    private $faturaModel;
    public function __construct() {
        $this->faturaModel = new faturaModel(); // PDO zaten modelde
    }

    public function Listele()
    {
        $faturalar = $this->faturaModel->Listele();
        if($faturalar) {
            return ['status'=>200,'data'=>$faturalar];
        }
        return ['status'=>404,'data'=>[]];
    }

    public function Ekle($fatura)
    {
        if (!isset($fatura['fatura_no']) || empty($fatura['fatura_no'])) {
            return ['status' => 400, 'msg' => 'fatura no zorunludur'];
        }
        else if (!isset($fatura['tutar']) || empty($fatura['tutar'])) {
            return ['status' => 400, 'msg' => 'tutar zorunludur'];
        }
        else if (!isset($fatura['adres']) || empty($fatura['adres'])) {
            return ['status' => 400, 'msg' => 'adres zorunludur'];
        }
        else if (!isset($fatura['birim']) || empty($fatura['birim'])) {
            return ['status' => 400, 'msg' => 'birim zorunludur'];
        }
        else if($fatura['tutar']<0) {
            return ['status'=>400,'msg'=>'tutar 0 dan küçük olamaz'];
        }
        else if($this->faturaModel->Getir($fatura['fatura_no'])){
            return ['status'=>400,'msg'=>'fatura no zaten kayıtlı'];
        }

        try {
            $this->faturaModel->Ekle($fatura);
            return ['status' => 201, 'msg' => 'Fatura başarıyla eklendi'];
        } catch (\Exception $exception) {
            return ['status' => 400, 'msg' => "Fatura Eklenirken Hata Oluştu"];
        }
    }

    public function Sil($faturaId)
    {
        try {
            if($this->faturaModel->FaturaBul($faturaId)) {
                $this->faturaModel->Sil($faturaId);
                return ['status' => 204, 'msg' => 'Fatura silindi'];
            }
            return ['status'=>404,'mag'=>'Fatura Kaydı Bulunamadı'];
        }
        catch (\Exception $exception) {
            return ['status' => 400, 'msg' => $exception->getMessage()];
        }
    }

}