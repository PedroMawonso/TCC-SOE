<?php
ob_start();
$user = $user ?? \App\Core\Auth::user();
$error = $_GET['error'] ?? '';
$saved = isset($_GET['saved']);
?>

<div class="shell">
    <?php
    $activePage = 'settings';
    require BASE_PATH . '/app/Views/componentes/barra-lateral.php';
    ?>

    <section class="content">
        <header>
            <div>
                <p class="eyebrow">PREFERÊNCIAS</p>
                <h1>Configurações</h1>
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

        <?php if ($saved): ?>
            <div class="notice success">As tuas configurações foram atualizadas.</div>
        <?php elseif ($error === 'email'): ?>
            <div class="notice error">Este email já está associado a outra conta.</div>
        <?php elseif ($error === 'invalid'): ?>
            <div class="notice error">Preenche um nome e um email válido.</div>
        <?php endif; ?>

        <form class="settings-layout" method="post" action="/?page=save-settings" enctype="multipart/form-data">
            <section class="panel settings-profile">
                <div class="panel-head">
                    <div>
                        <p class="eyebrow">CONTA</p>
                        <h3>Informações pessoais</h3>
                    </div>
                </div>

                <div class="avatar-upload">
                    <?php if (!empty($user['avatar'])): ?>
                        <img class="avatar avatar-large avatar-image" src="<?= htmlspecialchars($user['avatar']) ?>" alt="Foto atual de <?= htmlspecialchars($user['name']) ?>">
                    <?php else: ?>
                        <div class="avatar avatar-large"><?= strtoupper(substr($user['name'], 0, 2)) ?></div>
                    <?php endif; ?>
                    <label class="upload-label">
                        Foto do utilizador
                        <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp">
                        <small>JPG, PNG ou WebP até 2 MB.</small>
                    </label>
                </div>

                <label>
                    Nome completo
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                </label>

                <label>
                    Email
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </label>

                <label>
                    Curso
                    <input type="text" name="course" value="<?= htmlspecialchars($user['course'] ?? '') ?>" placeholder="Ex.: Administração">
                </label>

                <label>
                    Nova palavra-passe
                    <input type="password" name="password" placeholder="Deixa vazio para manter a atual">
                </label>
            </section>

            <section class="panel settings-appearance">
                <div class="panel-head">
                    <div>
                        <p class="eyebrow">INTERFACE</p>
                        <h3>Aparência do layout</h3>
                    </div>
                </div>

                <label class="theme-option">
                    <input type="radio" name="theme" value="light" <?= ($user['theme'] ?? 'light') === 'light' ? 'checked' : '' ?>>
                    <span>
                        <strong>Claro</strong>
                        <small>Visual limpo e luminoso.</small>
                    </span>
                </label>

                <label class="theme-option">
                    <input type="radio" name="theme" value="dark" <?= ($user['theme'] ?? 'light') === 'dark' ? 'checked' : '' ?>>
                    <span>
                        <strong>Escuro</strong>
                        <small>Menos brilho para estudar à noite.</small>
                    </span>
                </label>

                <button class="button primary" type="submit">Guardar alterações</button>
            </section>
        </form>
    </section>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/estrutura.php';
