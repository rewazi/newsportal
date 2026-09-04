<section class="register-form">
    <h1>Вход в аккаунт</h1>
    <?php if (($loginError ?? '') !== ''): ?>
        <p><?= htmlspecialchars($loginError ?? '', ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <form method="post" action="index.php?route=account-login">
        <label>Email <input type="email" name="email" required></label>
        <label>Пароль <input type="password" name="password" required></label>
        <button type="submit">Войти</button>
    </form>
    <p><a href="index.php?route=register">Зарегистрироваться</a></p>
</section>
