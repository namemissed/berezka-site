<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/site_repository.php';

$site = fetchSiteData();
$settings = $site['settings'];
$programs = $site['programs'];
$highlights = $site['highlights'];
$staff = fetchStaff();
$methodologies = fetchMethodologies();
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

            <?php if (!empty($staff)): ?>
            <section class="staff-section" id="staff">
                <span class="section-label">Наша команда</span>
                <h2>Сотрудники детского сада</h2>
                <div class="staff-grid">
                    <?php foreach ($staff as $member): ?>
                        <div class="staff-card">
                            <div class="staff-card__photo">
                                <?php if (!empty($member['photo_filename'])): ?>
                                    <img src="/uploads/staff/<?= htmlspecialchars($member['photo_filename'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($member['name'], ENT_QUOTES, 'UTF-8') ?>">
                                <?php else: ?>
                                    <div class="staff-card__placeholder">👤</div>
                                <?php endif; ?>
                            </div>
                            <div class="staff-card__info">
                                <h3><?= htmlspecialchars($member['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <p class="staff-card__position"><?= htmlspecialchars($member['position'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="staff-card__description"><?= htmlspecialchars($member['description'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <?php if (!empty($methodologies)): ?>
            <section class="methodologies-section" id="methodologies">
                <span class="section-label">Подход к обучению</span>
                <h2>Методики работы</h2>
                <div class="methodologies-list">
                    <?php foreach ($methodologies as $methodology): ?>
                        <div class="methodology-card">
                            <h3><?= htmlspecialchars($methodology['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="methodology-card__short"><?= htmlspecialchars($methodology['description'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php if (!empty($methodology['content'])): ?>
                                <p class="methodology-card__full"><?= htmlspecialchars($methodology['content'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        </main>

        <footer class="footer" id="contact">
            <p><?= htmlspecialchars($settings['footer_text'], ENT_QUOTES, 'UTF-8') ?></p>
        </footer>
    </div>
</body>
</html>
