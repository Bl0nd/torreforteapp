<?php

class EditarCarroController extends Controller
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

        $dados = array();
        $dados['titulo'] = "veiculos";
        //  var_dump($veiculos);
        if (empty($veiculos)) {
            $dados['veiculos'] = [];
            $dados['mensagem'] = "Nenhum veiculo encontrado";
        } else {
            $idVeiculo = $_GET['id'] ?? null;

            if ($idVeiculo) {
                foreach ($veiculos as $veiculo) {
                    if ($veiculo['id_veiculo'] == $idVeiculo) {
                        $dados['veiculos'] = [$veiculo];
                        break;
                    }
                }
            } else {
                $dados['veiculos'] = $veiculos;
            }
            $this->carregarViews('editar_carro', $dados);
        }
    }

    public function salvar()
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

        $idVeiculo = $_POST['id_veiculo'] ?? null;

        if (!$idVeiculo) {
            $_SESSION['erro'] = "ID do veículo não informado.";
            header("Location: " . URL_BASE . "index.php?url=editar_carro");
            exit;
        }

        $dados = [
            "modelo_veiculo" => $_POST['modelo_veiculo'] ?? '',
            "placa_veiculo" => $_POST['placa_veiculo'] ?? '',
            "cor_original_veiculo" => $_POST['cor_original_veiculo'] ?? '',
            "primeiro_dono" => $_POST['primeiro_dono'] ?? '',
        ];

        $url = API_BASE . "atualizarCarro/" . $idVeiculo;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}",
            "Content-Type: application/x-www-form-urlencoded"
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $resultado = json_decode($response, true);

        if ($httpCode === 200) {
            $_SESSION['sucesso'] = $resultado['mensagem'] ?? "Veículo atualizado com sucesso!";
            header("Location: " . URL_BASE . "index.php?url=veiculo");
            exit;
        } else {
            $_SESSION['erro'] = $resultado['erro'] ?? "Erro ao atualizar o veículo";
            header("Location: " . URL_BASE . "index.php?url=editarcarro");
            exit;
        }
    }
}
