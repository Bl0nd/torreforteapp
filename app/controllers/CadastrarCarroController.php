<?php

class CadastrarCarroController extends Controller
{
    public function index()
    {
        // Verifica se o token de sessão está presente, o que indica que o usuário está logado
        if (!isset($_SESSION['token'])) {
            header("Location: " . URL_BASE . "index.php?url=login");
            exit;
        }

        // Valida o token para garantir que o usuário tem acesso
        $token = $_SESSION['token'];
        $payload = AuxiliarToken::validar($token);

        // Se o token não for válido, destrói a sessão e redireciona para o login
        if (!$payload) {
            session_destroy();
            header("Location: " . URL_BASE . "index.php?url=login");
            exit;
        }

        // Carrega a view do cadastro de carro
        $dados = array();
        $dados['titulo'] = "Cadastrar Carro";
        $this->carregarViews('cadastro_carro', $dados);
    }

    public function cadastrar()
    {
        // Verifica se o token está na sessão
        if (!isset($_SESSION['token'])) {
            header("Location: " . URL_BASE . "index.php?url=login");
            exit;
        }

        // Valida o token
        $token = $_SESSION['token'];
        $payload = AuxiliarToken::validar($token);
        if (!$payload) {
            session_destroy();
            header("Location: " . URL_BASE . "index.php?url=login");
            exit;
        }
        $dados = [
            "placa_veiculo" => $_POST['placa_veiculo'] ?? '',
            "modelo_veiculo" => $_POST['modelo_veiculo'] ?? '',
            "cor_original_veiculo" => $_POST['cor_original_veiculo'] ?? '',
            "primeiro_dono" => $_POST['primeiro_dono'] ?? ''
        ];

        if (empty($dados['placa_veiculo']) || empty($dados['modelo_veiculo']) || empty($dados['cor_original_veiculo']) || empty($dados['primeiro_dono'])) {
            $_SESSION['erro'] = "Todos os campos são obrigatórios!";
            header("Location: " . URL_BASE . "index.php?url=cadastrarcarro");
            exit;
        }
        $idCliente = $payload['id'];

        // Envia os dados para a API para cadastrar o carro
        $url = API_BASE . "cadastrarVeiculo/" . $idCliente;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
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

        if ($httpCode === 201) {
            $_SESSION['sucesso'] = $resultado['mensagem'] ?? "Veículo cadastrado com sucesso!";
            header("Location: " . URL_BASE . "index.php?url=veiculo");
            exit;
        } else {
            $_SESSION['erroCad'] = $resultado['erro'] ?? "Erro ao cadastrar o veículo";
            header("Location: " . URL_BASE . "index.php?url=cadastrarcarro");
            exit;
        }
    }
}
