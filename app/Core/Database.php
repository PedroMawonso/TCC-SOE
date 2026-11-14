<?php

declare(strict_types=1);

namespace App\Core;

final class Database
{
    private static function defaults(): array
    {
        return [
            'users' => [
                [
                    'id' => 1,
                    'name' => 'Administrador IMTLA',
                    'email' => 'admin@imtla.ao',
                    'password' => '$2y$10$NiawZU0J7MJC6olmYlAQPOxSCsMiL.1nyljxtGoqoAeZ4lkLUO2jW',
                    'role' => 'admin',
                    'course' => 'Administração',
                    'avatar' => null,
                    'theme' => 'light',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
            ],
            'subjects' => [],
            'tasks' => [],
            'schedules' => [],
            'materials' => [],
        ];
    }

    public static function read(): array
    {
        if (!is_dir(STORAGE_PATH)) {
            mkdir(STORAGE_PATH, 0775, true);
        }

        if (!file_exists(DATA_FILE)) {
            self::write(self::defaults());
        }

        $data = json_decode((string) file_get_contents(DATA_FILE), true);

        return is_array($data) ? $data : self::defaults();
    }

    public static function write(array $data): void
    {
        if (!is_dir(STORAGE_PATH)) {
            mkdir(STORAGE_PATH, 0775, true);
        }

        $temp = DATA_FILE . '.tmp';
        file_put_contents(
            $temp,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX,
        );
        rename($temp, DATA_FILE);
    }

    public static function nextId(array $items): int
    {
        return empty($items) ? 1 : max(array_column($items, 'id')) + 1;
    }

    public static function backup(): string
    {
        if (!is_dir(BACKUP_PATH)) {
            mkdir(BACKUP_PATH, 0775, true);
        }

        $name = 'backup-' . date('Ymd-His') . '.json';
        copy(DATA_FILE, BACKUP_PATH . '/' . $name);

        return $name;
    }
}