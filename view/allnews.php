<h1>Все новости</h1>

<div class="all-news">

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
                        <?= htmlspecialchars(mb_substr($item['text'], 0, 250)) ?>...
                    </p>

                    <div class="author">
                        Автор:
                        <?= htmlspecialchars($item['user_id']) ?>
                    </div>

                    <div class="comment-count">
                        Комментариев: <?= (int)$item['comment_count'] ?>
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

        <p>Новостей нет.</p>

    <?php endif; ?>

</div>