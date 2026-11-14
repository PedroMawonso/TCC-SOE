<?php
ob_start();
$user = \App\Core\Auth::user();
?>

<div class="shell">
  <?php
  $activePage = 'dashboard';
  require BASE_PATH . '/app/Views/componentes/barra-lateral.php';
  ?>

  <section class="content">
    <header>
      <div>
        <p class="eyebrow">
          SÁBADO, <?= strtoupper(date('d \d\e F \d\e Y')) ?>
        </p>
        <h1>
          Bem-vindo, <?= htmlspecialchars(explode(' ', $user['name'])[0]) ?>
        </h1>
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

    <div class="hero">
      <div>
        <p class="eyebrow">FOCO DE HOJE</p>
        <h2>
          Pequenos passos.<br>
          <em>Grandes resultados.</em>
        </h2>
        <p>Organiza o teu tempo e acompanha o teu progresso académico.</p>
      </div>

      <div class="hero-stat">
        <strong><?= count($tasks) ?></strong>
        <span>tarefas registadas</span>
      </div>
    </div>

    <div class="stats">
      <div>
        <span class="stat-icon coral">✓</span>
        <small>TAREFAS PENDENTES</small>
        <strong>
          <?= count(array_filter($tasks, fn($t) => ($t['status'] ?? '') !== 'Concluída')) ?>
        </strong>
      </div>

      <div>
        <span class="stat-icon blue">▣</span>
        <small>DISCIPLINAS</small>
        <strong><?= count($subjects) ?></strong>
      </div>

      <div>
        <span class="stat-icon gold">◷</span>
        <small>HORAS ESTA SEMANA</small>
        <strong><?= count($schedules) * 2 ?>h</strong>
      </div>
    </div>

    <div class="grid">
      <section class="panel" id="tarefas">
        <div class="panel-head">
          <div>
            <p class="eyebrow">ACOMPANHAMENTO</p>
            <h3>Próximas tarefas</h3>
          </div>
        </div>

        <?php if (!$tasks): ?>
          <div class="empty">Ainda não tens tarefas. Adiciona a primeira para começar.</div>
        <?php endif; ?>

        <?php foreach (array_slice($tasks, 0, 5) as $task): ?>
          <div class="task-row">
            <span class="check">✓</span>
            <div>
              <strong><?= htmlspecialchars($task['title'] ?? 'Sem título') ?></strong>
              <small><?= htmlspecialchars($task['subject'] ?? 'Geral') ?></small>
            </div>
            <time><?= htmlspecialchars($task['due_date'] ?? 'Sem prazo') ?></time>
          </div>
        <?php endforeach; ?>
      </section>

      <section class="panel" id="disciplinas">
        <div class="panel-head">
          <div>
            <p class="eyebrow">O TEU SEMESTRE</p>
            <h3>Disciplinas</h3>
          </div>
        </div>

        <?php if (!$subjects): ?>
          <div class="empty">Regista as disciplinas do teu semestre.</div>
        <?php endif; ?>

        <?php foreach (array_slice($subjects, 0, 4) as $subject): ?>
          <div class="subject-row">
            <span class="subject-dot"></span>
            <strong><?= htmlspecialchars($subject['name'] ?? '') ?></strong>
            <small><?= htmlspecialchars($subject['teacher'] ?? '') ?></small>
          </div>
        <?php endforeach; ?>
      </section>
    </div>

    <div class="overview-grid">
      <section class="panel wide" id="materiais">
        <div class="panel-head">
          <div>
            <p class="eyebrow">BIBLIOTECA PESSOAL</p>
            <h3>Materiais de estudo</h3>
          </div>
        </div>

        <?php if (!$materials): ?>
          <div class="empty">Organiza aqui os teus documentos e materiais de apoio.</div>
        <?php endif; ?>

        <?php foreach (array_slice($materials, 0, 3) as $material): ?>
          <div class="material">
            <span>▤</span>
            <div>
              <strong><?= htmlspecialchars($material['title'] ?? '') ?></strong>
              <small><?= htmlspecialchars($material['subject'] ?? 'Material de apoio') ?></small>
            </div>
          </div>
        <?php endforeach; ?>
      </section>

      <section class="panel wide" id="horarios">
        <div class="panel-head">
          <div>
            <p class="eyebrow">PLANEAMENTO</p>
            <h3>Horários de estudo</h3>
          </div>
        </div>

        <?php if (!$schedules): ?>
          <div class="empty">Cria blocos de estudo por dia e período.</div>
        <?php endif; ?>

        <?php foreach ($schedules as $schedule): ?>
          <div class="schedule-row">
            <strong><?= htmlspecialchars($schedule['day'] ?? '') ?></strong>
            <span><?= htmlspecialchars($schedule['period'] ?? '') ?></span>
            <small><?= htmlspecialchars($schedule['activity'] ?? '') ?></small>
          </div>
        <?php endforeach; ?>
      </section>
    </div>
  </section>
</div>

<?php
foreach (
  [
    'task' => ['Nova tarefa', 'Título', 'subject', 'Disciplina', 'tasks'],
    'subject' => ['Nova disciplina', 'Nome da disciplina', 'teacher', 'Professor', 'subjects'],
    'material' => ['Novo material', 'Título do material', 'subject', 'Disciplina', 'materials'],
    'schedule' => ['Novo horário', 'Dia', 'period', 'Período', 'schedules'],
  ] as $type => $fields
):
?>
  <div class="modal" id="<?= $type ?>-modal">
    <form class="modal-card" method="post" action="/?page=save">
      <button
        type="button"
        class="close"
        onclick="closeModal('<?= $type ?>-modal')"
      >×</button>

      <p class="eyebrow">REGISTO ACADÉMICO</p>
      <h2><?= $fields[0] ?></h2>

      <input type="hidden" name="type" value="<?= $fields[4] ?>">

      <label>
        <?= $fields[1] ?>
        <input
          name="<?= $type === 'task' || $type === 'material' ? 'title' : ($type === 'subject' ? 'name' : 'day') ?>"
          required
        >
      </label>

      <label>
        <?= $fields[3] ?>
        <input name="<?= $fields[2] ?>" required>
      </label>

      <button class="button primary">Guardar registo</button>
    </form>
  </div>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/estrutura.php';
