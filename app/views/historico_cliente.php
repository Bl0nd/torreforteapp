<!DOCTYPE html>
<html lang="pt-br">

<?php require_once('template/head.php') ?>

<body class="body_cadastro">

    <?php require_once('template/header.php') ?>

    <a href="<?= URL_BASE; ?>index.php?url=menu" class="btn-voltar" aria-label="Voltar">
        <i class="uil uil-arrow-left"></i>
    </a>

    <section class="historico">
        <h2>MEU HISTÓRICO</h2>

        <div class="historico-filtros">
            <button class="filtro-btn active" data-filter="todos">Todos</button>
            <button class="filtro-btn" data-filter="Pendente">Pendentes</button>
            <button class="filtro-btn" data-filter="Concluido">Concluídos</button>
            <button class="filtro-btn" data-filter="Cancelado">Cancelados</button>
        </div>
        
        <div class="historico-lista"> 
            <?php if (!empty($dados['historicos'])): ?>
                <?php foreach ($dados['historicos'] as $historico): ?>
                    <?php 
                        $status = $historico['status_agendamento'];
                        $idAgendamento = $historico['id_agendamento'] ?? null;
                        
                        $jaAvaliado = $historico['comentario_enviado'] ?? false; 
                        
                        if ($status === 'Concluido' || $status === 'Pendente' || $status === 'Cancelado'): 
                    ?>
                        <div class="historico-card" data-status="<?= $status ?>"> 
                            <p><strong>Modelo do Veículo:</strong> <?= $historico['modelo_veiculo'] ?></p>
                            <p><strong>Placa do Veículo:</strong> <?= $historico['placa_veiculo'] ?></p>
                            <p><strong>Tipo de Serviço:</strong> <?= $historico['tipo_servico'] ?></p>
                            <p><strong>Descrição:</strong> <?= $historico['descricao_agendamento'] ?? 'Sem descrição' ?></p>
                            
                            <p class="status-info">
                                <strong>Status:</strong> 
                                <span class="status-label status-label-<?= $status ?>"><?= $status ?></span>
                            </p>

                            <p><strong>Data Agendada:</strong> <?= $historico['data_agendada'] ?></p>
                            <p><strong>Valor:</strong>
                                <?php if ($status === 'Concluido' && !empty($historico['preco_base_servico']) && is_numeric($historico['preco_base_servico'])) {
                                    echo 'R$ ' . number_format($historico['preco_base_servico'], 2, ',', '.');
                                } else {
                                    echo '-';
                                }
                                ?>
                            </p>
                            
                           <div class="card-acoes">
    <?php if ($status === 'Concluido' && $idAgendamento): ?>
        <?php if ($jaAvaliado): ?>
            <span class="info-avaliado">
                <i class="uil uil-check-circle"></i> Serviço Avaliado
            </span>
        <?php else: ?>
            <a href="<?= URL_BASE; ?>index.php?url=agendamento&acao=avaliar&id=<?= $idAgendamento ?>" class="btn-avaliar">
                <i class="uil uil-star"></i> Avaliar Serviço
            </a>
        <?php endif; ?>
    <?php endif; ?>
</div>
                            
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="historico-vazio"><?= $dados['mensagem'] ?? 'Nenhum histórico' ?></p>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once('template/footer.php') ?>

    <script src="assets/js/script.js"> </script>
</body>

</html>