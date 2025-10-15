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
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch); // executa a requisição e guarda a resposta
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode === 200) {
            $return = json_decode($response, true);
            $dados['servicos'] = $return;
        }

        $dados['titulo'] = "Agendamento";
        $this->carregarViews('agendamentos', $dados);
    }

    public function agendar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $veiculo = filter_input(INPUT_POST, 'selecionar_veiculo', FILTER_SANITIZE_NUMBER_INT);
            $id_servico = filter_input(INPUT_POST, 'tipo_servico', FILTER_SANITIZE_NUMBER_INT);
            $desc = filter_input(INPUT_POST, 'descrever_problema', FILTER_SANITIZE_SPECIAL_CHARS);
            $data_hora_br = $_POST['data_hora'] ?? null;

            // Verifica se os campos obrigatórios foram preenchidos
            if (!$veiculo || !$id_servico || !$desc || !$data_hora_br) {
                $_SESSION['erro'] = "Todos os campos obrigatórios devem ser preenchidos.";
                header("Location: " . URL_BASE . "index.php?url=agendamento");
                exit;
            }

            // CORREÇÃO: interpretar o formato do input datetime-local
            $dt = DateTime::createFromFormat('Y-m-d\TH:i', $data_hora_br);
            if (!$dt) {
                $_SESSION['erro'] = "Formato de data/hora inválido.";
                header("Location: " . URL_BASE . "index.php?url=agendamento");
                exit;
            }

            $data = $dt->format('Y-m-d');
            $hora = $dt->format('H:i:s');

            $dados = [
                'id_veiculo' => $veiculo,
                'id_servico' => $id_servico,
                'descricao_agendamento' => $desc,
                'data_agendada' => $data,
                'hora_agendada' => $hora
            ];

            $url = API_BASE . 'CriarAgendamento';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/x-www-form-urlencoded"
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $resultado = json_decode($response, true);

            if ($httpCode === 200 && isset($resultado['mensagem'])) {
                $_SESSION['sucesso'] = $resultado['mensagem'];
                header("Location: " . URL_BASE . "index.php?url=menu");
                exit;
            } else {
                $_SESSION['erro'] = $resultado['erro'] ?? "Erro ao realizar o agendamento.";
                header("Location: " . URL_BASE . "index.php?url=agendamento");
                exit;
            }
        } else {
            $_SESSION['erro'] = "Requisição inválida.";
            header("Location: " . URL_BASE . "index.php?url=agendamento");
            exit;
        }
    }
}
