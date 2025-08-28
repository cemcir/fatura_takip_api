<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use controller\faturaController;

header('Content-Type: application/json');

require_once "controller/faturaController.php";

$controller = new faturaController();

// İstek türünü al
$method = $_SERVER['REQUEST_METHOD'];

// Base path (localhost altındaki klasör)
$base_path = '/fatura_takip_api';

// URL yolunu al ve base path'i çıkar
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace($base_path, '', $path);
$path = trim($path, '/');

// Segmentlere ayır
$segments = explode('/', $path);

try {
    // GET /fatura/listele
    if ($method === 'GET' && isset($segments[0], $segments[1]) && $segments[0] === 'faturalar' && $segments[1] === 'listele') {
        $sonuc = $controller->Listele();
        echo json_encode($sonuc,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    // POST /fatura/ekle
    else if ($method === 'POST' && isset($segments[0], $segments[1]) && $segments[0] === 'faturalar' && $segments[1] === 'ekle') {
        $input = json_decode(file_get_contents('php://input'), true);

        $sonuc= $controller->Ekle($input);
        echo json_encode($sonuc,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        // POST /fatura/sil
    }
    else if ($method === 'POST' && isset($segments[0], $segments[1]) && $segments[0] === 'faturalar' && $segments[1] === 'sil') {
        $input = json_decode(file_get_contents('php://input'), true);

        $sonuc = $controller->Sil($input);
        echo json_encode($sonuc,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    }
    else {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Endpoint bulunamadı'
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
