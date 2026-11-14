<?php
ob_start();
$user = \App\Core\Auth::user();
$activePage = $module;
$icons = [
    'subjects' => '▣',
    'schedules' => '◷',
    'tasks' => '✓',
    'materials' => '▤',
];
?>

<div class="shell">
    <?php require BASE_PATH . '/app/Views/componentes/barra-lateral.php'; ?>

    <section class="content">
        <header>
            <div>
                <p class="eyebrow">ESPAÇO ACADÉMICO</p>
                <h1><?= htmlspecialchars($config[0]) ?></h1>
            </div>

            <div class="profile">
                <?php if (!empty($user['avatar'])): ?>
                    <img class="avatar avatar-image" src="<?= htmlspecialchars($user['avatar']) ?>" alt="Foto de <?= htmlspecialchars($user['name']) ?>">
                <?php else: ?>
                    <div class="avatar"><?= strtoupper(substr($user['name'], 0, 2)) ?></div>
                <?php endif; ?>
                <div>
                    <strong><?= htmlspecialchars($user['name']) ?></strong>
                    <small><?= htmlspecialchars($user['role']) ?></small>
                </div>
            </div>
        </header>

        <section class="module-intro">
            <div>
                <p class="eyebrow">MÓDULO <?= strtoupper($config[0]) ?></p>
                <h2><?= htmlspecialchars($config[1]) ?></h2>
            </div>
            <button class="button primary compact" onclick="openModal('module-modal')">
                + Novo registo
            </button>
        </section>

        <section class="panel module-list">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">REGISTOS</p>
                    <h3><?= count($items) ?> registo(s)</h3>
                </div>
            </div>

            <?php if (!$items): ?>
                <div class="empty">
                    Ainda não existem registos neste módulo. Cria o primeiro para começar.
                </div>
            <?php endif; ?>

            <?php foreach ($items as $item): ?>
                <div class="module-row">
                    <span class="module-icon"><?= $icons[$module] ?></span>
                    <div>
                        <strong><?= htmlspecialchars($item[$config[2]] ?? '') ?></strong>
                        <small><?= htmlspecialchars($item[$config[4]] ?? '') ?></small>
                    </div>

                    <?php if ($module === 'tasks' && !empty($item['due_date'])): ?>
                        <time><?= htmlspecialchars($item['due_date']) ?></time>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </section>
    </section>
</div>

<div class="modal" id="module-modal">
    <form class="modal-card" method="post" action="/?page=save">
        <button type="button" class="close" onclick="closeModal('module-modal')">×</button>
        <p class="eyebrow">NOVO REGISTO</p>
        <h2><?= htmlspecialchars($config[0]) ?></h2>

        <input type="hidden" name="type" value="<?= $module ?>">

        <label>
            <?= htmlspecialchars($config[3]) ?>
            <input name="<?= $config[2] ?>" required>
        </label>

        <label>
            <?= htmlspecialchars($config[5]) ?>
            <input name="<?= $config[4] ?>" required>
        </label>

        <?php if ($module === 'tasks'): ?>
            <label>
                Prazo
                <input type="date" name="due_date">
            </label>

            <label>
                Estado
                <select name="status">
                    <option>Por iniciar</option>
                    <option>Em progresso</option>
                    <option>Concluída</option>
                </select>
            </label>
        <?php endif; ?>

        <button class="button primary">Guardar registo</button>
    </form>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/estrutura.php';
