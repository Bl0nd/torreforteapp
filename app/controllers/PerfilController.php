<?php

class PerfilController extends Controller
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
        $idCliente = $payload['id'];
        $url = API_BASE . "cliente/{$idCliente}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 


        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}",
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);
        $clientes = json_decode($response, true);

        $dados = array();
        $dados['titulo'] = "Clientes";

        if (empty($clientes)) {
            $dados['cliente'] = [];
            $dados['mensagem'] = "Nenhum cliente encontrado";
        } else {
            $dados['cliente'] = $clientes;
            $dados['mensagem'] = null;
        }

        $this->carregarViews('perfil', $dados);
    }
}