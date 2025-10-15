<?php

class HistoricoController extends Controller
{
    public function index()
    {
        
        if (!isset($_SESSION['token'])) {
            header("Location: " . URL_BASE . "index.php?url=login");
            exit;
        }
        $token = $_SESSION['token'];

        $payload = AuxiliarToken::validar($token);
        if (!$payload) {
            session_destroy();
            header("Location: " . URL_BASE . "index.php?url=login");
            exit;
        }

        $idVeiculo = $payload['id'];
        $url = API_BASE . "getHistorico/{$idVeiculo}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);
        $historico = json_decode($response, true);

        $dados = [];
        $dados['titulo'] = "Historico";
        if (!isset($historico)) {
            $dados['historico'] = [];
            $dados['mensagem'] = "Nenhum historico encontrado";
        } else {
            $dados['historicos'] = $historico['Histórico'];
            $dados['mensagem'] = null;
        }

        $this->carregarViews('historico_cliente', $dados);
    }
}