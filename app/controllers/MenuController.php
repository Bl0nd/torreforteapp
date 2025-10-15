<?php

class MenuController extends Controller
{

    public function index()
    {
        if (!isset($_SESSION['token'])) {
            header("Location: " . URL_BASE . "index.php?url=login");
            exit;
        }

        $token = $_SESSION['token'];
        // $idCliente = $_SESSION['id_cliente']; // Pega o ID que está logado na sessão

        $payload = AuxiliarToken::validar($token);
        if(!$payload){
            echo 'Token inválido ou expirado. Faça login novamente';
            header("Location:".URL_BASE);
            exit;
        }

        $idCliente = $payload['id'];

        if(!$idCliente){
            echo 'ID do aluno não encontrado';
            exit;
        }


        $url = API_BASE . "cliente/" . $idCliente;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $token,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch); 
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $dadosCliente = [];
        if ($httpCode === 200) {
            $dadosCliente = json_decode($response, true);
        } else {
            // Para debug:
            var_dump($httpCode, $response);
            exit;
        }

        $urlServicos = API_BASE . "ListarServicos"; 
        $chServicos = curl_init($urlServicos);
        curl_setopt($chServicos, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chServicos, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $token,
            "Content-Type: application/json"
        ]);
        curl_setopt($chServicos, CURLOPT_SSL_VERIFYPEER, false);
        $responseServicos = curl_exec($chServicos);
        $httpCodeServicos = curl_getinfo($chServicos, CURLINFO_HTTP_CODE);
        curl_close($chServicos);
    
        $dadosServicos = [];
        if ($httpCodeServicos === 200) {
            $dadosServicos = json_decode($responseServicos, true);
        }
    
        // Enviar dados para a view
        $dados = array();
        $dados['titulo'] = "menu";
        $dados['cliente'] = $dadosCliente[0] ?? [];
        $dados['servicos'] = $dadosServicos;
    
        $this->carregarViews('menu', $dados);
    }
}
