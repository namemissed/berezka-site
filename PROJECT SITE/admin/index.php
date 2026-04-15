<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/site_repository.php';

requireAdmin();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $error = 'Не удалось проверить форму. Обновите страницу и попробуйте снова.';
    } else {
        try {
            updateSiteSettings($_POST['settings'] ?? []);
            replacePrograms($_POST['programs'] ?? []);
            replaceHighlights($_POST['highlights'] ?? []);
            
            if (isset($_POST['action']) && $_POST['action'] === 'staff') {
                saveStaff($_POST['staff'] ?? []);
                $message = 'Сотрудники сохранены.';
            } elseif (isset($_POST['action']) && $_POST['action'] === 'methodologies') {
                saveMethodologies($_POST['methodologies'] ?? []);
                $message = 'Методики сохранены.';
            } else {
                $message = 'Изменения сохранены.';
            }
        } catch (Throwable $exception) {
            $error = 'Сохранение не удалось. Проверьте подключение к базе данных.';
        }
    }
}

$site = fetchSiteData();
$settings = $site['settings'];
$programs = $site['programs'];
$highlights = $site['highlights'];
$staff = fetchStaff();
$methodologies = fetchMethodologies();

while (count($programs) < 4) {
    $programs[] = ['icon' => '', 'title' => '', 'description' => ''];
}

while (count($highlights) < 4) {
    $highlights[] = ['title' => '', 'description' => ''];
}

while (count($staff) < 3) {
    $staff[] = ['name' => '', 'position' => '', 'description' => '', 'photo_filename' => ''];
}

while (count($methodologies) < 3) {
    $methodologies[] = ['title' => '', 'description' => '', 'content' => ''];
}

$currentTab = $_GET['tab'] ?? 'main';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
    <header class="admin-header">
        <div>
            <div class="login-card__badge">Панель управления</div>
            <h1>Редактирование сайта</h1>
        </div>
        <div class="admin-header__actions">
            <a class="admin-link" href="/" target="_blank" rel="noopener noreferrer">Открыть сайт</a>
            <a class="admin-link admin-link--danger" href="/admin/logout.php">Выйти</a>
        </div>
    </header>

    <nav class="admin-tabs">
        <a class="admin-tab <?= $currentTab === 'main' ? 'admin-tab--active' : '' ?>" href="?tab=main">Главная</a>
        <a class="admin-tab <?= $currentTab === 'staff' ? 'admin-tab--active' : '' ?>" href="?tab=staff">Сотрудники</a>
        <a class="admin-tab <?= $currentTab === 'methodologies' ? 'admin-tab--active' : '' ?>" href="?tab=methodologies">Методики</a>
    </nav>

    <main class="admin-layout">
        <?php if ($message !== ''): ?>
            <div class="notice notice--success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="notice notice--error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if ($currentTab === 'main'): ?>
        <form method="post" class="admin-stack">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">

            <section class="panel">
                <h2>Основные настройки</h2>
                <div class="grid">
                    <label class="field">
                        <span>Заголовок вкладки</span>
                        <input type="text" name="settings[site_title]" value="<?= htmlspecialchars($settings['site_title'], ENT_QUOTES, 'UTF-8') ?>">
                    </label>
                    <label class="field">
                        <span>Верхняя плашка</span>
                        <input type="text" name="settings[hero_badge]" value="<?= htmlspecialchars($settings['hero_badge'], ENT_QUOTES, 'UTF-8') ?>">
                    </label>
                    <label class="field field--full">
                        <span>Главный заголовок</span>
                        <input type="text" name="settings[hero_heading]" value="<?= htmlspecialchars($settings['hero_heading'], ENT_QUOTES, 'UTF-8') ?>">
                    </label>
                    <label class="field field--full">
                        <span>Текст первого экрана</span>
                        <textarea name="settings[hero_text]" rows="4"><?= htmlspecialchars($settings['hero_text'], ENT_QUOTES, 'UTF-8') ?></textarea>
                    </label>
                    <label class="field">
                        <span>Текст основной кнопки</span>
                        <input type="text" name="settings[primary_button_text]" value="<?= htmlspecialchars($settings['primary_button_text'], ENT_QUOTES, 'UTF-8') ?>">
                    </label>
                    <label class="field">
                        <span>Ссылка основной кнопки</span>
                        <input type="text" name="settings[primary_button_link]" value="<?= htmlspecialchars($settings['primary_button_link'], ENT_QUOTES, 'UTF-8') ?>">
                    </label>
                    <label class="field">
                        <span>Текст второй кнопки</span>
                        <input type="text" name="settings[secondary_button_text]" value="<?= htmlspecialchars($settings['secondary_button_text'], ENT_QUOTES, 'UTF-8') ?>">
                    </label>
                    <label class="field">
                        <span>Ссылка второй кнопки</span>
                        <input type="text" name="settings[secondary_button_link]" value="<?= htmlspecialchars($settings['secondary_button_link'], ENT_QUOTES, 'UTF-8') ?>">
                    </label>
                    <label class="field">
                        <span>Метка второго блока</span>
                        <input type="text" name="settings[about_label]" value="<?= htmlspecialchars($settings['about_label'], ENT_QUOTES, 'UTF-8') ?>">
                    </label>
                    <label class="field field--full">
                        <span>Заголовок второго блока</span>
                        <input type="text" name="settings[about_heading]" value="<?= htmlspecialchars($settings['about_heading'], ENT_QUOTES, 'UTF-8') ?>">
                    </label>
                    <label class="field field--full">
                        <span>Текст второго блока</span>
                        <textarea name="settings[about_text]" rows="4"><?= htmlspecialchars($settings['about_text'], ENT_QUOTES, 'UTF-8') ?></textarea>
                    </label>
                    <label class="field field--full">
                        <span>Текст в подвале</span>
                        <textarea name="settings[footer_text]" rows="3"><?= htmlspecialchars($settings['footer_text'], ENT_QUOTES, 'UTF-8') ?></textarea>
                    </label>
                </div>
            </section>

            <section class="panel">
                <h2>Карточки направлений</h2>
                <div class="repeaters">
                    <?php foreach ($programs as $index => $program): ?>
                        <div class="repeater-card">
                            <h3>Карточка <?= $index + 1 ?></h3>
                            <label class="field">
                                <span>Иконка</span>
                                <input type="text" name="programs[<?= $index ?>][icon]" value="<?= htmlspecialchars((string)($program['icon'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            </label>
                            <label class="field">
                                <span>Заголовок</span>
                                <input type="text" name="programs[<?= $index ?>][title]" value="<?= htmlspecialchars((string)($program['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            </label>
                            <label class="field">
                                <span>Описание</span>
                                <textarea name="programs[<?= $index ?>][description]" rows="4"><?= htmlspecialchars((string)($program['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="panel">
                <h2>Факты и преимущества</h2>
                <div class="repeaters">
                    <?php foreach ($highlights as $index => $highlight): ?>
                        <div class="repeater-card">
                            <h3>Факт <?= $index + 1 ?></h3>
                            <label class="field">
                                <span>Заголовок</span>
                                <input type="text" name="highlights[<?= $index ?>][title]" value="<?= htmlspecialchars((string)($highlight['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            </label>
                            <label class="field">
                                <span>Описание</span>
                                <textarea name="highlights[<?= $index ?>][description]" rows="4"><?= htmlspecialchars((string)($highlight['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <button type="submit" class="admin-button admin-button--wide">Сохранить изменения</button>
        </form>
        
        <?php elseif ($currentTab === 'staff'): ?>
        <form method="post" class="admin-stack">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="action" value="staff">

            <section class="panel">
                <h2>Наши сотрудники</h2>
                <p style="color: var(--soft); margin-bottom: 20px;">Добавьте информацию о воспитателях и других сотрудниках детского сада.</p>
                <div class="repeaters">
                    <?php foreach ($staff as $index => $member): ?>
                        <div class="repeater-card">
                            <h3>Сотрудник <?= $index + 1 ?></h3>
                            <label class="field">
                                <span>ФИО</span>
                                <input type="text" name="staff[<?= $index ?>][name]" value="<?= htmlspecialchars((string)($member['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            </label>
                            <label class="field">
                                <span>Должность</span>
                                <input type="text" name="staff[<?= $index ?>][position]" value="<?= htmlspecialchars((string)($member['position'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            </label>
                            <label class="field">
                                <span>Описание</span>
                                <textarea name="staff[<?= $index ?>][description]" rows="4"><?= htmlspecialchars((string)($member['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                            </label>
                            <label class="field">
                                <span>Имя файла фото</span>
                                <input type="text" name="staff[<?= $index ?>][photo_filename]" value="<?= htmlspecialchars((string)($member['photo_filename'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="например: photo1.jpg">
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <button type="submit" class="admin-button admin-button--wide">Сохранить сотрудников</button>
        </form>

        <?php elseif ($currentTab === 'methodologies'): ?>
        <form method="post" class="admin-stack">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="action" value="methodologies">

            <section class="panel">
                <h2>Методики работы</h2>
                <p style="color: var(--soft); margin-bottom: 20px;">Опишите методики и подходы к обучению, используемые в детском саду.</p>
                <div class="repeaters">
                    <?php foreach ($methodologies as $index => $methodology): ?>
                        <div class="repeater-card">
                            <h3>Методика <?= $index + 1 ?></h3>
                            <label class="field">
                                <span>Название</span>
                                <input type="text" name="methodologies[<?= $index ?>][title]" value="<?= htmlspecialchars((string)($methodology['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            </label>
                            <label class="field">
                                <span>Краткое описание</span>
                                <textarea name="methodologies[<?= $index ?>][description]" rows="3"><?= htmlspecialchars((string)($methodology['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                            </label>
                            <label class="field field--full">
                                <span>Подробное описание</span>
                                <textarea name="methodologies[<?= $index ?>][content]" rows="6"><?= htmlspecialchars((string)($methodology['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <button type="submit" class="admin-button admin-button--wide">Сохранить методики</button>
        </form>
        <?php endif; ?>
    </main>
</body>
</html>
