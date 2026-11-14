<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\{Auth, Controller, Database};

final class AuthController extends Controller
{
	public function login(): void
	{
		$error = null;

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$email = strtolower(trim($_POST['email'] ?? ''));
			$password = $_POST['password'] ?? '';

			foreach (Database::read()['users'] as $user) {
				if ($user['email'] === $email && password_verify($password, $user['password'])) {
					Auth::login($user);
					$this->redirect('/');
				}
			}

			$error = 'Email ou palavra-passe inválidos.';
		}

		$this->render('autenticacao/login', compact('error'));
	}

	public function logout(): void
	{
		Auth::logout();
		$this->redirect('/?page=login');
	}
}