<!doctype html>
<html lang="ru">
<head><meta charset="UTF-8"><title>Вход в админ-панель</title></head>
<body>
    <main>
        <h1>Вход в админ-панель</h1>
        <?php if (($error ?? '') !== ''): ?><p><?= htmlspecialchars($error ?? '', ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <form method="post" action="index.php?route=login">
            <label>Email <input type="email" name="email" required></label>
            <label>Пароль <input type="password" name="password" required></label>
            <button type="submit">Войти</button>
        </form>
        <a href="../index.php">Вернуться на сайт</a>
    </main>
</body>
</html>
