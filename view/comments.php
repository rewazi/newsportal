<?php

class ViewComments
{
    public static function form($newsId)
    {
        $newsId = (int)$newsId;
        if (empty($_SESSION['accountUserId'])) {
            echo '<p><a href="index.php?route=account-login">Войдите в аккаунт</a>, чтобы написать комментарий.</p>';
            return;
        }
        ?>
        <section class="comments-form">
            <h2>Добавить комментарий</h2>
            <form action="index.php?route=addcomment&amp;id=<?= $newsId ?>" method="post">
                <label for="comment-text">Ваш комментарий:</label>
                <textarea id="comment-text" name="comment" maxlength="500" required></textarea>
                <button type="submit">Отправить</button>
            </form>
        </section>
        <?php
    }

    public static function list($comments)
    {
        ?>
        <section class="comments">
            <h2>Комментарии (<?= count($comments) ?>)</h2>
            <?php if (empty($comments)): ?>
                <p>Комментариев пока нет.</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <article class="comment">
                        <div class="comment-meta">
                            Пользователь <?= (int)$comment['user_id'] ?>,
                            <?= htmlspecialchars($comment['date'], ENT_QUOTES, 'UTF-8') ?>
                        </div>
                        <p><?= nl2br(htmlspecialchars($comment['text'], ENT_QUOTES, 'UTF-8')) ?></p>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
        <?php
    }
}
