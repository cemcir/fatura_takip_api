<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: application/json');
require_once "database.php";

$method = $_SERVER['REQUEST_METHOD'];

$base_path = '/fatura_takip_api';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace($base_path, '', $path);
$path = trim($path, '/');


$segments = explode('/', $path);

try {
    $sonuc = ['status' => 400,'msg'=>'Bilinmeyen Hata'];
    // GET /faturalar/listele
    switch ($method) {
        case 'GET':
            switch ($segments[0]) {
                case 'faturalar':
                    switch ($segments[1]) {
                        case 'listele':
                            $faturalar = Database::getInstance()->select('faturalar');
                            if ($faturalar) {
                                $sonuc = ['status' => 200, 'data' => $faturalar];
                            }
                            else {
                                $sonuc = ['status' => 404, 'data' => []];
                            }
                            break;

                        default:
                            $sonuc = ['status' => 400, 'message' => 'Geçersiz İstek'];
                            break;
                    }
                    break;

                default:
                    $sonuc = ['status' => 400, 'message' => 'Geçersiz Endpoint'];
                    break;
            }
            break;

        case 'POST':
            switch ($segments[0]) {
                case 'faturalar':
                    switch ($segments[1]) {  // POST faturalar/ekle
                        case 'ekle':
                            $fatura = json_decode(file_get_contents('php://input'), true);

                            if (!isset($fatura['fatura_no']) || empty($fatura['fatura_no'])) {
                                $sonuc = ['status' => 400, 'msg' => 'Fatura No Zorunludur'];
                            }
                            else if (!isset($fatura['tutar']) || empty($fatura['tutar'])) {
                                $sonuc = ['status' => 400, 'msg' => 'Tutar Zorunludur'];
                            }
                            else if (!isset($fatura['adres']) || empty($fatura['adres'])) {
                                $sonuc = ['status' => 400, 'msg' => 'Adres Zorunludur'];
                            }
                            else if (!isset($fatura['birim']) || empty($fatura['birim'])) {
                                $sonuc = ['status' => 400, 'msg' => 'Birim Zorunludur'];
                            }
                            else if ($fatura['tutar'] < 0) {
                                $sonuc = ['status' => 400, 'msg' => 'Tutar 0 dan Küçük Olamaz'];
                            }
                            else if (!is_numeric($fatura['tutar'])) {
                                $sonuc = ['status' => 400, 'msg' => 'Tutar Geçerli Bir Sayı Olmalıdır'];
                            }
                            else if(Database::getInstance()->selectOne('faturalar',['fatura_no' => $fatura['fatura_no']])) {
                                $sonuc = ['status' => 400, 'msg' => 'Fatura No Zaten Kayıtlı'];
                            }
                            else {
                                try {
                                    Database::getInstance()->insert('faturalar', $fatura);
                                    $sonuc = ['status' => 400, 'msg' => 'Fatura Kaydı Başarıyla Tamamlandı'];
                                }
                                catch (\Exception $e) {
                                    $sonuc = ['status' => 400, 'msg' => 'Fatura Eklenirken Hata Oluştu'];
                                }
                            }
                            break;

                        case 'sil': // POST faturalar/sil
                            $fatura = json_decode(file_get_contents('php://input'), true);

                            if(!isset($fatura['faturaId']) && empty($fatura['faturaId'])) {
                                $sonuc = ['status' => 400, 'msg' => 'FaturaId Zorunludur'];
                            }
                            try {
                                if(Database::getInstance()->selectOne('faturalar',['id' => $fatura['faturaId']])) {
                                    Database::getInstance()->delete('faturalar',['id'=>$fatura['faturaId']]);
                                    $sonuc = ['status' => 400,'msg' =>'Fatura Kaydı Başarıyla Silindi'];
                                }
                                else {
                                    $sonuc = ['status' => 404, 'msg' => 'Fatura Kaydı Bulunamadı'];
                                }
                            }
                            catch (\Exception $e) {
                                $sonuc = ['status'=>400,'msg'=>'Fatura Silinirken Hata Oluştu'];
                            }
                            break;
                    }
                    break;
            }
            break;

        default:
            $sonuc = ['status' => 405, 'message' => 'Geçersiz HTTP metodu'];
            break;
    }

    echo json_encode($sonuc, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}


//
