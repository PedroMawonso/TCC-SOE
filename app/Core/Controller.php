<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
	protected function render(string $view, array $data = []): void
	{
		extract($data);
		require BASE_PATH . '/app/Views/' . $view . '.php';
	}

	protected function redirect(string $url): never
	{
		header('Location: ' . $url);
		exit;
	}
}