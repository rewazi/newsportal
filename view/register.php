<section class="register-form">
    <h1>Регистрация</h1>
    <?php if (($registrationError ?? '') !== ''): ?>
        <p><?= htmlspecialchars($registrationError ?? '', ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <?php if (!empty($registrationSuccess)): ?>
        <p>Регистрация завершена. Теперь можно войти в админ-панель.</p>
    <?php else: ?>
        <form method="post" action="index.php?route=register">
            <label>Имя пользователя <input name="username" value="<?= htmlspecialchars($registrationUsername ?? '', ENT_QUOTES, 'UTF-8') ?>" required></label>
            <label>Email <input type="email" name="email" value="<?= htmlspecialchars($registrationEmail ?? '', ENT_QUOTES, 'UTF-8') ?>" required></label>
            <label>Пароль <input type="password" name="password" minlength="6" required></label>
            <button type="submit">Зарегистрироваться</button>
        </form>
    <?php endif; ?>
</section>
