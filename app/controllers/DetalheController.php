<?php

class DetalheController extends Controller
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

        $idServicos = $_GET['id'];
        $url = API_BASE . "ListarServicosId/{$idServicos}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}",
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);
        $servicos = json_decode($response, true);

        $dados = [];
        $dados['titulo'] = "Detalhe do Serviço";

        if (empty($servicos)) {
            $dados['servicos'] = [];
            $dados['mensagem'] = "Serviço não encontrado";
        } else {
            $dados['servicos'] = $servicos;
            $dados['mensagem'] = null;
        }

        $this->carregarViews('detalhe_servicos', $dados);
    }
}
