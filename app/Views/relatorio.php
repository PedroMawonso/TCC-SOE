<?php
ob_start();
$user = \App\Core\Auth::user();
$activePage = 'report';
?>

<div class="shell">
    <?php require BASE_PATH . '/app/Views/componentes/barra-lateral.php'; ?>

    <section class="content">
        <header>
            <div>
                <p class="eyebrow">ANÁLISE ACADÉMICA</p>
                <h1>Relatórios</h1>
            </div>

            <div class="profile">
                <div class="avatar">AD</div>
                <div>
                    <strong><?= htmlspecialchars($user['name']) ?></strong>
                    <small><?= htmlspecialchars($user['role']) ?></small>
                </div>
            </div>
        </header>

        <section class="module-intro">
            <div>
                <p class="eyebrow">PROGRESSO DOS ESTUDOS</p>
                <h2>Uma visão clara do teu ritmo.</h2>
                <p class="muted">
                    Consulta os registos e acompanha a evolução da tua organização.
                </p>
            </div>
        </section>

        <div class="report-grid">
            <?php foreach ($counts as $label => $value): ?>
                <div class="report-card">
                    <small><?= strtoupper(htmlspecialchars($label)) ?></small>
                    <strong><?= $value ?></strong>
                    <span>registo(s)</span>
                </div>
            <?php endforeach; ?>
        </div>

        <section class="panel wide">
            <p class="eyebrow">PRÓXIMOS PASSOS</p>
            <h3>Continua a construir consistência</h3>
            <p class="muted">
                Mantém os teus horários actualizados e transforma cada tarefa concluída
                em progresso académico.
            </p>
        </section>
    </section>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/estrutura.php';
