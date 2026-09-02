<h1>
    Новости категории:
    <?= !empty($category) ? htmlspecialchars($category['name']) : 'Категория не найдена' ?>
</h1>

<div class="news-list">

    <?php if (!empty($news)): ?>

        <?php foreach ($news as $item): ?>

            <article class="news-card">

                <?php if (!empty($item['picture'])): ?>

                    <img
                        src="images/<?= htmlspecialchars($item['picture']) ?>"
                        alt="<?= htmlspecialchars($item['title']) ?>"
                    >

                <?php endif; ?>

                <div class="news-content">

                    <h2>
                        <?= htmlspecialchars($item['title']) ?>
                    </h2>

                    <p>
                        <?= htmlspecialchars(mb_substr($item['text'], 0, 150)) ?>...
                    </p>

                    <div class="author">
                        Автор:
                        <?= htmlspecialchars($item['user_id']) ?>
                    </div>

                    <a
                        class="button"
                        href="index.php?route=readnews&id=<?= $item['id'] ?>"
                    >
                        Читать далее
                    </a>

                </div>

            </article>

        <?php endforeach; ?>

    <?php else: ?>

        <p>В этой категории новостей нет.</p>

    <?php endif; ?>

</div>