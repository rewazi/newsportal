<?php
$item = $item ?? null;
$categories = $categories ?? [];
?>
<!doctype html>
<html lang="ru">
<head><meta charset="UTF-8"><title><?= $item ? 'Изменить' : 'Добавить' ?> новость</title></head>
<body>
    <main>
        <h1><?= $item ? 'Изменить' : 'Добавить' ?> новость</h1>
        <form method="post" action="index.php?route=news-save" enctype="multipart/form-data">
            <?php if ($item): ?><input type="hidden" name="id" value="<?= (int)$item['id'] ?>"><?php endif; ?>
            <label>Заголовок <input name="title" value="<?= htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required></label>
            <label>Текст <textarea name="text" required><?= htmlspecialchars($item['text'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></label>
            <label>Категория <select name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int)$category['id'] ?>" <?= isset($item['category_id']) && $item['category_id'] == $category['id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select></label>
            <label>Картинка <input type="file" name="picture" accept="image/*"></label>
            <button type="submit">Сохранить</button>
        </form>
        <a href="index.php?route=dashboard">Назад</a>
    </main>
</body>
</html>
