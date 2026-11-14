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
        Auth::requireLogin();
        $data = $this->data();

        $this->render('relatorio', [
            'counts' => [
                'Disciplinas' => count($data['subjects']),
                'Tarefas' => count($data['tasks']),
                'Horários' => count($data['schedules']),
                'Materiais' => count($data['materials']),
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
        Auth::requireLogin();

        if (Auth::user()['role'] !== 'admin') {
            http_response_code(403);
            exit('Acesso negado');
        }

        $name = Database::backup();
        header('Content-Type: application/json');
        echo json_encode(['file' => $name]);
    }
}