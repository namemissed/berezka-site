<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/site_repository.php';

$site = fetchSiteData();
$settings = $site['settings'];
$programs = $site['programs'];
$highlights = $site['highlights'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($settings['site_title'], ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <div class="page-shell">
        <header class="hero">
            <div class="hero__badge"><?= htmlspecialchars($settings['hero_badge'], ENT_QUOTES, 'UTF-8') ?></div>
            <h1><?= htmlspecialchars($settings['hero_heading'], ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="hero__text"><?= htmlspecialchars($settings['hero_text'], ENT_QUOTES, 'UTF-8') ?></p>
            <div class="hero__actions">
                <a class="button button--primary" href="<?= htmlspecialchars($settings['primary_button_link'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($settings['primary_button_text'], ENT_QUOTES, 'UTF-8') ?></a>
                <a class="button button--secondary" href="<?= htmlspecialchars($settings['secondary_button_link'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($settings['secondary_button_text'], ENT_QUOTES, 'UTF-8') ?></a>
            </div>
        </header>

        <main>
            <section class="cards" id="programs">
                <?php foreach ($programs as $program): ?>
                    <article class="card">
                        <div class="card__icon"><?= htmlspecialchars($program['icon'], ENT_QUOTES, 'UTF-8') ?></div>
                        <h2><?= htmlspecialchars($program['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <p><?= htmlspecialchars($program['description'], ENT_QUOTES, 'UTF-8') ?></p>
                    </article>
                <?php endforeach; ?>
            </section>

            <section class="about">
                <div class="about__content">
                    <span class="section-label"><?= htmlspecialchars($settings['about_label'], ENT_QUOTES, 'UTF-8') ?></span>
                    <h2><?= htmlspecialchars($settings['about_heading'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <p><?= htmlspecialchars($settings['about_text'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <div class="about__highlights">
                    <?php foreach ($highlights as $highlight): ?>
                        <div class="highlight">
                            <strong><?= htmlspecialchars($highlight['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <span><?= htmlspecialchars($highlight['description'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>

        <footer class="footer" id="contact">
            <p><?= htmlspecialchars($settings['footer_text'], ENT_QUOTES, 'UTF-8') ?></p>
        </footer>
    </div>
</body>
</html>
