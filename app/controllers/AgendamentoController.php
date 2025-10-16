<?php

class AgendamentoController extends Controller
{
    public function index()
    {
        $dados = array();

        if (!isset($_SESSION['token'])) {
            header("Location: " . URL_BASE . "index.php?url=login");
            exit;
        }

        $token = $_SESSION['token'];
        // $idCliente = $_SESSION['id_cliente']; // Pega o ID que está logado na sessão

        $payload = AuxiliarToken::validar($token);
        if (!$payload) {
            echo 'Token inválido ou expirado. Faça login novamente';
            header("Location:" . URL_BASE);
            exit;
        }

        $idCliente = $payload['id'];

        if (!$idCliente) {
            echo 'ID do aluno não encontrado';
            exit;
        }

        $url = API_BASE . 'clienteVeiculos/' . $idCliente;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $token,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch); // executa a requisição e guarda a resposta
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode === 200) {
            $return = json_decode($response, true);
            $dados['veiculos'] = $return;
        }

        $url = API_BASE . 'ListarServicos';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);

        $dados['servicos'] = $response;

        $dados['titulo'] = "Agendamento";
        $this->carregarViews('agendamentos', $dados);
    }

    public function agendar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $veiculo = filter_input(INPUT_POST, 'selecionar_veiculo', FILTER_SANITIZE_NUMBER_INT);
            $servico = filter_input(INPUT_POST, 'tipo_servico', FILTER_SANITIZE_NUMBER_INT);
            $desc = filter_input(INPUT_POST, 'descrever_problema', FILTER_SANITIZE_SPECIAL_CHARS);
            $data_hora_input = filter_input(INPUT_POST, 'data_hora', FILTER_SANITIZE_STRING); // ex: 2025-10-27T14:00

            // Ajusta o formato enviado pelo datetime-local
            $data_hora_input = str_replace("T", " ", $data_hora_input);

            try {
                $dt = new DateTime($data_hora_input);
            } catch (Exception $e) {
                $_SESSION['erro'] = "Data/hora inválida!";
                header("Location:" . URL_BASE . "index.php?url=menu");
                exit;
            }

            // Cria o array de dados com apenas um campo data_hora completo
            $dados = [
                'id_veiculo' => $veiculo,
                'tipo_servico' => $servico,
                'descricao_agendamento' => $desc,
                'data_agendada' => $dt->format('Y-m-d H:i:s')
            ];

            $url = API_BASE . 'CriarAgendamento';
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($dados),
                CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"]
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            // var_dump($response);
            // exit;

            $resultado = json_decode($response, true);

            if ($httpCode === 200) {
                $_SESSION['sucesso'] = $resultado['mensagem'] ?? "Agendamento feito com sucesso!";
            } else {
                $_SESSION['erro'] = $resultado['erro'] ?? "Ocorreu um erro ao realizar o agendamento";
            }

            header("Location:" . URL_BASE . "index.php?url=menu");
            exit;
        }
    }
}