<!doctype html>
<html lang="ru">
<head><meta charset="UTF-8"><title>Админ-панель</title></head>
<body>
    <header>
        <a href="index.php?route=dashboard">Админ-панель</a>
        <a href="index.php?route=news-form">Добавить новость</a>
        <a href="index.php?route=account">Аккаунт</a>
        <a href="index.php?route=logout">Выйти</a>
    </header>
    <main>
        <h1>Новости</h1>
        <table border="1" cellpadding="8">
            <tr><th>ID</th><th>Заголовок</th><th>Категория</th><th>Действия</th></tr>
            <?php foreach (($news ?? []) as $item): ?>
                <tr>
                    <td><?= (int)$item['id'] ?></td>
                    <td><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($item['category_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="index.php?route=news-form&amp;id=<?= (int)$item['id'] ?>">Изменить</a>
                        <a href="index.php?route=news-delete&amp;id=<?= (int)$item['id'] ?>" onclick="return confirm('Удалить новость?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>
