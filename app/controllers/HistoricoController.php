<?php

class HistoricoController extends Controller
{
    public function index()
    {
        // 1️⃣ Verifica autenticação
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

        // 2️⃣ Busca histórico do veículo
        $idVeiculo = $payload['id'];
        $urlHistorico = API_BASE . "getHistorico/{$idVeiculo}";

        $ch = curl_init($urlHistorico);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        $responseHistorico = curl_exec($ch);
        curl_close($ch);

        $historico = json_decode($responseHistorico, true);

        // 3️⃣ Busca comentários já existentes
        $urlComentarios = API_BASE . 'ListarComentarios';
        $ch = curl_init($urlComentarios);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultComentarios = curl_exec($ch);
        curl_close($ch);

        $comentarios = json_decode($resultComentarios, true);
        // var_dump($comentarios);
        // Extrai todos os IDs de agendamento que já possuem comentário
        $idsComentados = [];
        if (isset($comentarios) && is_array($comentarios)) {
            foreach ($comentarios as $coment) {
                if (isset($coment['id_agendamento'])) {
                    $idsComentados[] = $coment['id_agendamento'];
                }
            }
        }

        // 4️⃣ Monta os dados finais
        $dados = [];
        $dados['titulo'] = "Histórico";

        if (!isset($historico) || empty($historico['Histórico'])) {
            $dados['historicos'] = [];
            $dados['mensagem'] = "Nenhum histórico encontrado.";
        } else {
            // Adiciona uma flag para saber se pode comentar
            foreach ($historico['Histórico'] as &$h) {
                $h['pode_comentar'] = !in_array($h['id_agendamento'], $idsComentados);
            }

            $dados['historicos'] = $historico['Histórico'];
            $dados['mensagem'] = null;
        }

        // 5️⃣ Envia tudo pra view
        $this->carregarViews('historico_cliente', $dados);
    }
}
