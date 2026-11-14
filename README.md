# IMTLA StudyHub

Plataforma responsiva de organização de estudos para o IMTLA, construída com PHP MVC, HTML, CSS e JavaScript.

## Executar

```bash
php -S localhost:8000 -t public
```

Abrir `http://localhost:8000`.

Conta inicial: `admin@imtla.ao` / `Admin@123`.

## Arquitetura

- `public/`: ponto de entrada e assets.
- `app/Controllers/`: autenticação e fluxos dos módulos.
- `app/Models/`: repositório de dados.
- `app/Core/`: autenticação, controller base e persistência.
- `app/Views/`: templates do frontend.
- `storage/`: dados persistentes e cópias de segurança.

Os dados são gravados com escrita atómica em JSON porque o PHP local não tem `pdo_sqlite` ativo. A classe `Database` isola esse detalhe para permitir a migração para PDO/SQLite ou MySQL sem alterar a interface dos controllers. O endpoint `POST /?page=backup` gera uma cópia com timestamp e só pode ser usado por administradores.
