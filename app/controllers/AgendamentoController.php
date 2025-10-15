<?php

class AgendamentoController extends Controller
{
    public function index()
    {
        $acao = filter_input(INPUT_GET, 'acao', FILTER_SANITIZE_SPECIAL_CHARS);
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        // Se for para avaliar E o ID do agendamento está na URL
        if ($acao === 'avaliar' && $id) {
            $this->avaliar($id);
            return;
        }
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

    public function avaliar($id)
    {
        $dados = array();

        if (!isset($_SESSION['token'])) {
            header("Location: " . URL_BASE . "index.php?url=login");
            exit;
        }

        $token = $_SESSION['token'];

        $payload = AuxiliarToken::validar($token);
        if (!$payload) {
            $_SESSION['erro'] = 'Sessão inválida. Faça login novamente.';
            header("Location:" . URL_BASE);
            exit;
        }

        $idCliente = $payload['id'];

        if (!$idCliente) {
            $_SESSION['erro'] = 'ID do cliente não encontrado.';
            header("Location:" . URL_BASE . "index.php?url=menu");
            exit;
        }

        $dados['id_cliente'] = $idCliente;
        $dados['id_agendamento'] = $id;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_SPECIAL_CHARS);

            if (empty($comentario)) {
                $_SESSION['erro'] = 'O campo comentário é obrigatório.';
                header("Location: " . URL_BASE . "index.php?url=agendamento&acao=avaliar&id=" . $id);
                exit;
            }

            $url = API_BASE . 'CriarComentario';

            $data = [
                'id_cliente' => $idCliente,
                'id_agendamento' => $id, // A API lê isso como id_orcamento
                'comentario' => $comentario
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . $token,
                "Content-Type: application/x-www-form-urlencoded"
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $curl_error = curl_error($ch);
                curl_close($ch);
                $_SESSION['erro'] = "Erro de rede (cURL) ao conectar à API: " . $curl_error;
                header("Location: " . URL_BASE . "index.php?url=agendamento&acao=avaliar&id=" . $id);
                exit;
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $return = json_decode($response, true);

            // Tratamento de Sucesso ou Falha da API
            if ($httpCode === 200 && ($return['success'] ?? true) === true) {
                $_SESSION['sucesso'] = $return['mensagem'] ?? "Avaliação enviada com sucesso! Agradecemos o seu feedback.";
                header("Location: " . URL_BASE . "index.php?url=historico");
                exit;
            } else {
                $errorMessage = $return['message'] ?? $return['erro'] ?? "Erro ao enviar avaliação (HTTP $httpCode).";

                // Tratamento de "Já Avaliado"
                if (stripos($errorMessage, 'comentário já existe') !== false || stripos($errorMessage, 'avaliado anteriormente') !== false || stripos($errorMessage, 'único') !== false) {
                    $_SESSION['erro'] = "Este serviço já foi avaliado.";
                    header("Location: " . URL_BASE . "index.php?url=historico");
                    exit;
                }

                $_SESSION['erro'] = $errorMessage;
                header("Location: " . URL_BASE . "index.php?url=agendamento&acao=avaliar&id=" . $id);
                exit;
            }
        }

        $dados['titulo'] = "Avaliar Serviço";
        $this->carregarViews('avaliar', $dados);
    }
}
