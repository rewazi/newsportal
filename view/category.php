<h1>Категории новостей</h1>

<div class="categories">

    <?php if (!empty($categories)): ?>

        <?php foreach ($categories as $item): ?>

            <div class="category">

                <a
                    href="index.php?route=catnews&id=<?= $item['id'] ?>"
                >
                    <?= htmlspecialchars($item['name']) ?>
                </a>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Категорий пока нет.</p>

    <?php endif; ?>

</div>