<?php

require_once __DIR__ . '/../inc/Database.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Category.php';
require_once __DIR__ . '/../model/News.php';
require_once __DIR__ . '/../model/Comments.php';
require_once __DIR__ . '/../admin/modelAdmin/modelAdmin.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = getenv('NEWS_PORTAL_DB_HOST') ?: 'localhost';
$dbname = getenv('NEWS_PORTAL_DB_NAME') ?: 'newsportal';
$username = getenv('NEWS_PORTAL_DB_USER') ?: 'root';
$password = getenv('NEWS_PORTAL_DB_PASSWORD') ?: '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $exception) {
    fwrite(STDERR, "FAIL: ei saanud andmebaasiga ühendust. Käivitage MySQL ja kontrollige ühenduse seadeid.\n");
    exit(1);
}

$testId = date('YmdHis') . '_' . mt_rand(1000, 9999);
$email = 'test_' . $testId . '@example.com';
$usernameValue = 'Test User ' . $testId;
$title = 'Test news ' . $testId;
$categoryName = 'Test category ' . $testId;
$userId = null;
$newsId = null;
$categoryId = null;
$passed = 0;
$failed = 0;

function checkTest($condition, $message)
{
    global $passed, $failed;

    if (!$condition) {
        $failed++;
        throw new RuntimeException($message);
    }

    $passed++;
    echo "OK: {$message}\n";
}

try {
    $pdo->beginTransaction();
    $pdo->exec("INSERT INTO category (name) VALUES (" . $pdo->quote($categoryName) . ")");
    $categoryId = (int)$pdo->lastInsertId();
    $pdo->commit();

    $user = new User();
    checkTest(
        $user->register('', $email, 'secret123') === 'Введите имя пользователя.',
        'проверка обязательного имени пользователя'
    );
    checkTest(
        $user->register($usernameValue, $email, '123') === 'Пароль должен содержать минимум 6 символов.',
        'проверка минимальной длины пароля'
    );
    checkTest(
        $user->register($usernameValue, $email, 'secret123') === '',
        'регистрация пользователя'
    );
    checkTest(
        $user->register($usernameValue, $email, 'secret123') === 'Пользователь с таким email уже зарегистрирован.',
        'запрет повторной регистрации с тем же email'
    );

    $createdUser = $user->authenticate($email, 'secret123');
    checkTest(is_array($createdUser), 'вход с правильным паролем');
    checkTest($createdUser['email'] === $email, 'возвращается email пользователя');
    $userId = (int)$createdUser['id'];
    checkTest($user->authenticate($email, 'wrong-password') === false, 'отказ при неправильном пароле');

    $pdo->prepare('UPDATE users SET status = :status WHERE id = :id')
        ->execute([':status' => 'admin', ':id' => $userId]);

    $admin = new modelAdmin();
    $adminUser = $admin->authenticate($email, 'secret123');
    checkTest(is_array($adminUser) && $adminUser['status'] === 'admin', 'вход администратора');

    $category = new Category();
    $categoryResult = $category->getCategory($categoryId);
    checkTest($categoryResult['name'] === $categoryName, 'получение категории');

    $_SESSION['userId'] = $userId;
    checkTest(
        $admin->saveNews([
            'title' => $title,
            'text' => 'Automated test news',
            'category_id' => $categoryId,
        ]),
        'создание новости администратором'
    );

    $newsId = (int)$pdo->query(
        'SELECT id FROM news WHERE title = ' . $pdo->quote($title) . ' LIMIT 1'
    )->fetchColumn();
    checkTest($newsId > 0, 'новость сохранена в базе');

    $news = new News();
    $newsResult = $news->getNews($newsId);
    checkTest($newsResult['title'] === $title, 'получение новости по идентификатору');
    checkTest(count($news->getNewsByCategory($categoryId)) === 1, 'фильтрация новостей по категории');

    $comments = new Comments();
    checkTest($comments->add($newsId, '', $userId) === false, 'отказ от пустого комментария');
    checkTest($comments->add($newsId, 'Automated test comment', $userId), 'добавление комментария');
    checkTest($comments->countByNewsId($newsId) === 1, 'подсчёт комментариев');
    checkTest(count($comments->getByNewsId($newsId)) === 1, 'получение комментариев новости');
} catch (Throwable $exception) {
    fwrite(STDERR, "FAIL: {$exception->getMessage()}\n");
} finally {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    if ($newsId) {
        $statement = $pdo->prepare('DELETE FROM comments WHERE news_id = :news_id');
        $statement->execute([':news_id' => $newsId]);
        $statement = $pdo->prepare('DELETE FROM news WHERE id = :id');
        $statement->execute([':id' => $newsId]);
    }

    if ($userId) {
        $statement = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $statement->execute([':id' => $userId]);
    }

    if ($categoryId) {
        $statement = $pdo->prepare('DELETE FROM category WHERE id = :id');
        $statement->execute([':id' => $categoryId]);
    }

    echo "Результат: {$passed} пройдено, {$failed} ошибок.\n";
}

exit($failed === 0 ? 0 : 1);
