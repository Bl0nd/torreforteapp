<?php

class AvaliarController extends Controller
{
    public function index()
    {
        // 1. Verificação de Autenticação e Token (MANTIDO)
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

        $id_cliente = $payload['id_cliente'] ?? $payload['id'] ?? null;
        $idAgendamento = $_GET['id'] ?? null;

        if (!$idAgendamento || !is_numeric($idAgendamento)) {
            $_SESSION['erro'] = "ID de agendamento inválido ou faltando.";
            header("Location: " . URL_BASE . "index.php?url=historico");
            exit;
        }

        $dadosView = [
            'titulo' => "Avaliar Serviço",
            'id_agendamento' => $idAgendamento,
        ];

        // 3. Carregar a View (GET inicial ou reexibição após POST com erro)
        // A view usará $_SESSION['erro'] e $_SESSION['sucesso'] para exibir feedback.
        $this->carregarViews('avaliar', $dadosView);
    }

    public function postarAvaliacao($id)
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

        $id_cliente = $payload['id_cliente'] ?? $payload['id'] ?? null;

        // 2. Lógica para Processamento do Formulário (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
            $erro_encontrado = false;
            $idAgendamento = $_POST['id_agendamento'];

            if (empty($comentario)) {
                $_SESSION['erro'] = 'O campo de comentário é obrigatório.';
                $erro_encontrado = true;
            } elseif (empty($id_cliente)) {
                $_SESSION['erro'] = 'Não foi possível identificar o cliente para envio da avaliação.';
                $erro_encontrado = true;
            }

            if (!$erro_encontrado) {
                $dadosAPI = [
                    'id_cliente' => $id_cliente,
                    'id_agendamento' => $id,
                    'comentario' => $comentario,
                ];

                $url = API_BASE . "CriarComentario";

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dadosAPI));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $token]);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                // var_dump($response);
                // exit;
                $resultadoAPI = json_decode($response, true);

                if (isset($resultadoAPI['sucesso']) && $resultadoAPI['sucesso'] === true) {
                    // SUCESSO: Redireciona para o histórico (MANDATÓRIO para sucesso)
                    $_SESSION['sucesso'] = $resultadoAPI['mensagem'] ?? "Avaliação enviada com sucesso!";
                    header("Location: " . URL_BASE . "index.php?url=historico");
                    exit;
                } else {
                    // ERRO DA API: Define a mensagem e DEIXA O CÓDIGO CAIR PARA O FINAL (Carregar a View)
                    $_SESSION['erro'] = $resultadoAPI['mensagem'] ?? "Erro ao enviar a avaliação. Código HTTP: {$httpCode}";
                    $erro_encontrado = true;
                }
            }

            // SE HOUVE ERRO (Validação ou API), o código continua e carrega a view novamente.
            if ($erro_encontrado) {
                // É importante recarregar a view aqui, mas como já estamos no final do POST, 
                // simplesmente continuamos para o passo 3. O usuário verá a mesma URL no navegador.
            }
        }
    }
}
