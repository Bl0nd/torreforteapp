<?php

class ServicosController extends Controller
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

        $url = API_BASE . "ListarServicos";

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

        $dados = array();
        $dados['titulo'] = "Servicos";

        if (empty($servicos)) {
            $dados['servicos'] = [];
            $dados['mensagem'] = "Nenhum serviço encontrado";
        } else {
            $dados['servicos'] = $servicos;
            $dados['mensagem'] = null;
        }

        $this->carregarViews('servicos', $dados);
    }

}
