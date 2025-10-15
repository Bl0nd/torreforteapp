<?php

class EditarCadastroController extends Controller
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
        $iCliente = $payload['id'];
        $url = API_BASE . "cliente/{$iCliente}";

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
        //  var_dump($clientes);
        if (empty($clientes)) {
            $dados['clientes'] = [];
            $dados['mensagem'] = "Nenhum cliente encontrado";
        } else {
            $dados['clientes'] = $clientes;
            $dados['mensagem'] = null;
        }

        $this->carregarViews('editar_cadastro', $dados);
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
    
        $idCliente = $payload['id'];
    
        // Dados do formulário
        $dados = [
            "nome" => $_POST['nome_cliente'] ?? '',
            "cpf" => $_POST['cpf_cliente'] ?? '',
            "telefone" => $_POST['telefone_cliente'] ?? '',
            "email" => $_POST['email_cliente'] ?? '',
        ];       
    
        if (!empty($_POST['nova_senha'])) {
            $dados["senha_cliente"] = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);
        }
    
        $url = API_BASE . "atualizarCliente/" . $idCliente;
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        $resultado = json_decode($response, true);

        if ($httpCode === 200) {
            $_SESSION['sucesso'] = $resultado['mensagem'] ?? "Perfil atualizado com sucesso !";
            header("Location: " . URL_BASE . "index.php?url=perfil");
            exit;
        } else {
            $_SESSION['erro'] = $resultado['erro'] ?? "Erro ao atualizar o perfil";
            header("Location: " . URL_BASE . "index.php?url=editarcadastro");
            exit;
        }
    }
    
}
