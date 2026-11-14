<?php
ob_start();
?>

<section class="login-page">
    <div class="login-art">
      
        <h1>
            Estuda com<br>
            <em>intenção.</em>
        </h1>
        <p class="muted">A tua rotina académica, organizada num só lugar.</p>
    </div>

    <form class="login-card" method="post">
        <p class="eyebrow">BEM-VINDO AO IMTLA</p>
        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <label>
            Email
            <input type="email" name="email" placeholder="nome@imtla.ao" required>
        </label>

        <label>
            Palavra-passe
            <input type="password" name="password" placeholder="••••••••" required>
        </label>

        <button class="button primary" type="submit">
            Entrar <span>→</span>
        </button>

        <small>Conta inicial: admin@imtla.ao / Admin@123</small>
    </form>
</section>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/estrutura.php';
