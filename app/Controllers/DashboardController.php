<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\{Auth, Controller, Database};
use App\Models\Repository;

final class DashboardController extends Controller
{
    private function data(): array
    {
        $id = (int) Auth::user()['id'];

        return [
            'subjects' => Repository::all('subjects', $id),
            'tasks' => Repository::all('tasks', $id),
            'schedules' => Repository::all('schedules', $id),
            'materials' => Repository::all('materials', $id),
        ];
    }

    public function index(): void
    {
        Auth::requireLogin();
        $this->render('dashboard', $this->data());
    }

    public function settings(): void
    {
        Auth::requireLogin();
        $this->render('configuracoes', ['user' => Auth::user()]);
    }

    public function module(string $module): void
    {
        Auth::requireLogin();

        $labels = [
            'subjects' => [
                'Disciplinas',
                'Organiza as disciplinas do teu trimestre.',
                'name',
                'Nome da disciplina',
                'teacher',
                'Professor',
            ],
            'schedules' => [
                'Horários',
                'Planeia os teus períodos de estudo.',
                'day',
                'Dia da semana',
                'period',
                'Período',
            ],
            'tasks' => [
                'Tarefas',
                'Acompanha trabalhos, prazos e actividades.',
                'title',
                'Título da tarefa',
                'subject',
                'Disciplina',
            ],
            'materials' => [
                'Materiais de estudo',
                'Guarda e consulta o teu material de apoio.',
                'title',
                'Título do material',
                'subject',
                'Disciplina',
            ],
        ];

        if (!isset($labels[$module])) {
            $this->redirect('/');
        }

        $viewNames = [
            'subjects' => 'disciplinas',
            'schedules' => 'horarios',
            'tasks' => 'tarefas',
            'materials' => 'materiais',
        ];
        $data = $this->data();
        $this->render($viewNames[$module], [
            'module' => $module,
            'config' => $labels[$module],
            'items' => $data[$module],
        ]);
    }

    public function report(): void
    {
		Auth::requireRole('admin');
        $data = $this->data();
        $students = array_values(
            array_filter(
                Repository::all('users'),
                fn($user) => ($user['role'] ?? '') === 'student',
            ),
        );

        $this->render('relatorio', [
            'counts' => [
                'Disciplinas' => count($data['subjects']),
                'Tarefas' => count($data['tasks']),
                'Horários' => count($data['schedules']),
                'Materiais' => count($data['materials']),
                'Alunos cadastrados' => count($students),
                'Tarefas concluídas' => count(
                    array_filter(
                        $data['tasks'],
                        fn($task) => ($task['status'] ?? '') === 'Concluída',
                    ),
                ),
            ],
        ]);
    }

    public function save(): void
    {
        Auth::requireLogin();
        $type = $_POST['type'] ?? '';
        $allowed = ['subjects', 'tasks', 'schedules', 'materials'];

        if (in_array($type, $allowed, true)) {
            $item = array_map('trim', $_POST);
            unset($item['type']);
            $item['user_id'] = (int) Auth::user()['id'];
            Repository::create($type, $item);
        }

        $this->redirect('/?page=' . $type);
    }

    public function saveSettings(): void
    {
        Auth::requireLogin();

        $currentUser = Auth::user();
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $course = trim($_POST['course'] ?? '');
        $theme = $_POST['theme'] ?? 'light';

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect('/?page=settings&error=invalid');
        }

        if (!in_array($theme, ['light', 'dark'], true)) {
            $theme = 'light';
        }

        foreach (Repository::all('users') as $user) {
            if ((int) $user['id'] !== (int) $currentUser['id'] && $user['email'] === $email) {
                $this->redirect('/?page=settings&error=email');
            }
        }

        $changes = [
            'name' => $name,
            'email' => $email,
            'course' => $course,
            'theme' => $theme,
        ];

        if (!empty($_POST['password'])) {
            $changes['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        if (!empty($_FILES['avatar']['tmp_name'])) {
            $avatar = $this->storeAvatar($_FILES['avatar'], (int) $currentUser['id']);

            if ($avatar !== null) {
                $changes['avatar'] = $avatar;
            }
        }

        $updatedUser = Repository::updateUser((int) $currentUser['id'], $changes);

        if ($updatedUser !== null) {
            Auth::login($updatedUser);
        }

        $this->redirect('/?page=settings&saved=1');
    }

    private function storeAvatar(array $file, int $userId): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK
            || ($file['size'] ?? 0) > 2 * 1024 * 1024
        ) {
            return null;
        }

        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return null;
        }

        $extensions = [
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp',
        ];
        $extension = $extensions[$imageInfo[2] ?? 0] ?? null;

        if ($extension === null) {
            return null;
        }

        $directory = BASE_PATH . '/public/uploads/avatars';
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = 'avatar-' . $userId . '-' . bin2hex(random_bytes(6)) . '.' . $extension;
        $target = $directory . '/' . $filename;

        return move_uploaded_file($file['tmp_name'], $target)
            ? '/uploads/avatars/' . $filename
            : null;
    }

    public function delete(): void
    {
        Auth::requireLogin();
        Repository::delete(
            $_POST['type'],
            (int) $_POST['id'],
            (int) Auth::user()['id'],
        );
        $this->redirect('/');
    }

    public function backup(): void
    {
        Auth::requireRole('admin');

        $name = Database::backup();
        header('Content-Type: application/json');
        echo json_encode(['file' => $name]);
    }
}