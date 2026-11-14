<?php
$activePage = $activePage ?? '';
?>

<aside class="sidebar">
    <div class="logo">
        <img
            class="school-logo sidebar-logo"
            src="/assets/Luiza-Andaluz-Logo.png"
            alt="Logotipo do Instituto Médio Técnico Luiza Andaluz"
        >
        <strong>IMTLA</strong>
    </div>

    <p class="nav-label">ESPAÇO ACADÉMICO</p>
    <a class="nav <?= $activePage === 'dashboard' ? 'active' : '' ?>" href="/">
        ◈ <span>Visão geral</span>
    </a>
    <a class="nav <?= $activePage === 'subjects' ? 'active' : '' ?>" href="/?page=subjects">
        ▣ <span>Disciplinas</span>
    </a>
    <a class="nav <?= $activePage === 'schedules' ? 'active' : '' ?>" href="/?page=schedules">
        ◷ <span>Horários</span>
    </a>
    <a class="nav <?= $activePage === 'tasks' ? 'active' : '' ?>" href="/?page=tasks">
        ✓ <span>Tarefas</span>
    </a>
    <a class="nav <?= $activePage === 'materials' ? 'active' : '' ?>" href="/?page=materials">
        ▤ <span>Materiais</span>
    </a>
    <a class="nav <?= $activePage === 'report' ? 'active' : '' ?>" href="/?page=report">
        ↗ <span>Relatórios</span>
    </a>

    <div class="sidebar-bottom">
        <a class="nav" href="/?page=logout">↪ <span>Terminar sessão</span></a>
    </div>
</aside>
