<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

startSessionIfNeeded();

if (isAdminLoggedIn()) {
    header('Location: /admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $error = 'Сессия устарела. Попробуйте ещё раз.';
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if (!adminLogin($username, $password)) {
            $error = 'Неверный логин или пароль.';
        } else {
            header('Location: /admin/index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body admin-body--centered">
    <main class="login-card">
        <div class="login-card__badge">Админ-панель Берёзка</div>
        <h1>Вход для администратора</h1>
        <p>После входа можно редактировать главный экран, карточки и факты сайта.</p>

        <?php if ($error !== ''): ?>
            <div class="notice notice--error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form method="post" class="admin-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">

            <label class="field">
                <span>Логин</span>
                <input type="text" name="username" required>
            </label>

            <label class="field">
                <span>Пароль</span>
                <input type="password" name="password" required>
            </label>

            <button type="submit" class="admin-button">Войти</button>
        </form>
    </main>
</body>
</html>
