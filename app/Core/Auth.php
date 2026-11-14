<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
	public static function user(): ?array
	{
		return $_SESSION['user'] ?? null;
	}

	public static function check(): bool
	{
		return self::user() !== null;
	}

	public static function login(array $user): void
	{
		$_SESSION['user'] = $user;
		session_regenerate_id(true);
	}

	public static function logout(): void
	{
		$_SESSION = [];
		session_destroy();
	}

	public static function requireLogin(): void
	{
		if (!self::check()) {
			header('Location: /?page=login');
			exit;
		}
	}
}