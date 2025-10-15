<?php

class VeiculoController extends Controller
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
        $url = API_BASE . "clienteVeiculos/{$idCliente}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}",
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $veiculos = json_decode($response, true);

        $dados = [];
        $dados['titulo'] = "Meus Veículos";

        if (!isset($veiculos)) {
            $dados['veiculos'] = [];
            $dados['mensagem'] = "Nenhum veículo encontrado";
        } else {
            $dados['veiculos'] = $veiculos;
            $dados['mensagem'] = null;
        }

        $this->carregarViews('carros_cliente', $dados);
    }
}
