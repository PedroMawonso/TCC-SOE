<?php

declare(strict_types=1);

require __DIR__ . '/../config/config.php';

spl_autoload_register(function (string $class) {
	$file = BASE_PATH . '/' . str_replace(['App\\', '\\'], ['app/', '/'], $class) . '.php';

	if (file_exists($file)) {
		require $file;
	}
});

use App\Controllers\{AuthController, DashboardController};

$page = $_GET['page'] ?? 'dashboard';
$auth = new AuthController();
$dashboard = new DashboardController();

if ($page === 'login') {
	$auth->login();
	exit;
}

if ($page === 'logout') {
	$auth->logout();
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($page === 'save') {
		$dashboard->save();
	}

	if ($page === 'delete') {
		$dashboard->delete();
	}

	if ($page === 'backup') {
		$dashboard->backup();
	}

	exit;
}

if ($page === 'report') {
	$dashboard->report();
	exit;
}

if ($page === 'settings') {
	$dashboard->settings();
	exit;
}

if ($page === 'save-settings' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$dashboard->saveSettings();
	exit;
}

if (in_array($page, ['subjects', 'schedules', 'tasks', 'materials'], true)) {
	$dashboard->module($page);
	exit;
}

$dashboard->index();