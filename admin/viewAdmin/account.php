<!doctype html>
<html lang="ru">
<head><meta charset="UTF-8"><title>Аккаунт</title></head>
<body>
    <main>
        <h1>Управление аккаунтом</h1>
        <form method="post" action="index.php?route=account">
            <label>Имя пользователя <input name="username" value="<?= htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required></label>
            <label>Новый пароль <input type="password" name="password" minlength="6"></label>
            <button type="submit">Сохранить</button>
        </form>
        <a href="index.php?route=dashboard">Назад</a>
    </main>
</body>
</html>
