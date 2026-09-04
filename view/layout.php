
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Новости</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            color: #222;
        }

        header {
            background: #222;
            color: white;
            padding: 20px 0;
        }

        .container {
            width: 1100px;
            max-width: 95%;
            margin: 0 auto;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
            font-size: 16px;
        }

        nav a:hover {
            color: #ddd;
        }

        main {
            padding: 40px 0;
            min-height: 500px;
        }

        .title {
            font-size: 32px;
            margin-bottom: 30px;
        }

        .news-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .news-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .news-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .news-content {
            padding: 20px;
        }

        .news-content h2 {
            font-size: 21px;
            margin-bottom: 12px;
        }

        .news-content p {
            color: #666;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .author {
            font-size: 14px;
            color: #888;
            margin-bottom: 15px;
        }

        .read-more {
            display: inline-block;
            background: #222;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 5px;
        }

        .read-more:hover {
            background: #444;
        }

        footer {
            background: #222;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
        }

        /* Страница новости */
        .single-news {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .single-news h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .single-news img {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .single-news-text {
            font-size: 17px;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .comments-form,
        .comments {
            background: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
        }

        .comments-form h2,
        .comments h2 {
            margin-bottom: 15px;
        }

        .comments-form textarea {
            display: block;
            width: 100%;
            min-height: 100px;
            margin: 8px 0 12px;
            padding: 10px;
            resize: vertical;
        }

        .comments-form button {
            background: #222;
            color: white;
            border: 0;
            padding: 10px 16px;
            cursor: pointer;
        }

        .comment {
            border-top: 1px solid #ddd;
            padding: 12px 0;
        }

        .comment-meta,
        .comment-count {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }

        /* Категории */
        .category-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .category-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .category-card h2 {
            margin-bottom: 15px;
        }

        .category-card a {
            display: inline-block;
            background: #222;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .category-card a:hover {
            background: #444;
        }

        .error {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .error h1 {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .error p {
            margin-bottom: 20px;
            color: #666;
        }

        .error a {
            display: inline-block;
            background: #222;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 5px;
        }

        @media (max-width: 800px) {

            .news-list {
                grid-template-columns: 1fr;
            }

            .category-list {
                grid-template-columns: 1fr;
            }

            header .container {
                flex-direction: column;
                gap: 15px;
            }

            nav {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }

            nav a {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

<header>
    <div class="container">

        <div class="logo">
            НОВОСТИ
        </div>

        <nav>
            <a href="index.php">
                Главная
            </a>

            <a href="index.php?route=allnews">
                Все новости
            </a>

            <a href="index.php?route=category">
                Категории
            </a>

            <a href="index.php?route=register">
                Регистрация
            </a>

            <?php if (!empty($_SESSION['accountUserId'])): ?>
                <span>Вы вошли как <?= htmlspecialchars($_SESSION['accountName'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                <a href="index.php?route=account-logout">Выйти</a>
            <?php else: ?>
                <a href="index.php?route=account-login">Войти в аккаунт</a>
            <?php endif; ?>

            <?php if (!empty($_SESSION['userId']) && ($_SESSION['status'] ?? '') === 'admin'): ?>
                <a href="admin/index.php?route=dashboard">Админ-панель</a>
            <?php endif; ?>
        </nav>

    </div>
</header>

<main>

    <div class="container">

        <?php

        $route = $_GET['route'] ?? 'start';

        switch ($route) {

            case 'start':

                require __DIR__ . '/start.php';

                break;


            case 'allnews':

                require __DIR__ . '/allnews.php';

                break;


            case 'category':

                require __DIR__ . '/category.php';

                break;


            case 'register':

                require __DIR__ . '/register.php';

                break;


            case 'account-login':

                require __DIR__ . '/accountLogin.php';

                break;


            case 'catnews':

                require __DIR__ . '/catnews.php';

                break;


            case 'readnews':

                require __DIR__ . '/readnews.php';

                break;


            default:

                require __DIR__ . '/error404.php';

                break;
        }

        ?>

    </div>

</main>

<footer>

    <div class="container">

        &copy; 2026 Сайт новостей

    </div>

</footer>

</body>

</html>

