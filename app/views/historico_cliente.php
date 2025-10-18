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
                    <?php if (in_array($historico['status_agendamento'], ['Concluido', 'Pendente', 'Cancelado'])): ?>
                        <div class="historico-item" data-status="<?= $historico['status_agendamento'] ?>">
                            <p><strong>Modelo do Veículo:</strong> <?= $historico['modelo_veiculo'] ?></p>
                            <p><strong>Placa do Veículo:</strong> <?= $historico['placa_veiculo'] ?></p>
                            <p><strong>Tipo de Serviço:</strong> <?= $historico['nome_servico'] ?></p>
                            <p><strong>Descrição:</strong> <?= $historico['descricao_agendamento'] ?? 'Sem descrição' ?></p>
                            <p><strong>Status:</strong> <?= $historico['status_agendamento'] ?></p>
                            <p><strong>Data Agendada:</strong> <?= $historico['data_agendada'] ?></p>
                            <p><strong>Valor:</strong>
                                <?php 
                                if ($historico['status_agendamento'] === 'Concluido' && 
                                    !empty($historico['preco_base_servico']) && 
                                    is_numeric($historico['preco_base_servico'])) {
                                    echo 'R$ ' . number_format($historico['preco_base_servico'], 2, ',', '.');
                                } else {
                                    echo '-';
                                }
                                ?>
                            </p>

                            <?php if ($historico['status_agendamento'] === 'Concluido'): ?>
                                <?php if (!empty($historico['pode_comentar']) && $historico['pode_comentar']): ?>
                                    <a href="<?= URL_BASE; ?>index.php?url=avaliar&id=<?= $historico['id_agendamento'] ?? 0 ?>" class="btn-avaliar">
                                        <i class="uil uil-star"></i> Avaliar Serviço
                                    </a>
                                <?php else: ?>
                                    <span class="btn-avaliar-disabled">
                                        <i class="uil uil-check-circle"></i> Avaliação já enviada
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?= $dados['mensagem'] ?? 'Nenhum histórico encontrado' ?></p>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once('template/footer.php') ?>

    <script src="assets/js/script.js"></script>
</body>

</html>
