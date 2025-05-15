<?php
require_once __DIR__ . '/../services/CandidatoService.php';

class CandidatoController {
    public static function index () {
        $candidatos = CandidatoService::getCandidatos();
        header('Content-Type: application/json'); 
        echo json_encode($candidatos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
?>