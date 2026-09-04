<article class="single-news">

    <?php if (!empty($news)): ?>

    <h1>
        <?= htmlspecialchars($news['title']) ?>
    </h1>

    <div class="author">

        Автор:
        <?= htmlspecialchars($news['user_id']) ?>

        <?php if (!empty($news['category_name'])): ?>

            | Категория:
            <?= htmlspecialchars($news['category_name']) ?>

        <?php endif; ?>

    </div>

    <?php if (!empty($news['picture'])): ?>

        <img
            src="images/<?= htmlspecialchars($news['picture']) ?>"
            alt="<?= htmlspecialchars($news['title']) ?>"
        >

    <?php endif; ?>

    <div class="single-news-text">

        <?= nl2br(htmlspecialchars($news['text'])) ?>

    </div>

    <?php ViewComments::list($comments ?? []); ?>
    <?php ViewComments::form($news['id']); ?>

    <br>

    <a
        class="button"
        href="index.php"
    >
        Вернуться на главную
    </a>

    <?php endif; ?>

</article>