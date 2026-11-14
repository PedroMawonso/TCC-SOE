<!doctype html>
<html lang="pt">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?= APP_NAME ?></title>
		<link rel="stylesheet" href="/assets/style.css">
	</head>
	<body>
		<div class="page-loader" aria-hidden="true">
			<span></span>
			<strong>A carregar</strong>
		</div>

		<?= $content ?? '' ?>

		<script src="/assets/app.js"></script>
	</body>
</html>