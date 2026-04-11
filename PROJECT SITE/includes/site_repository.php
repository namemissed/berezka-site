<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function defaultSiteData(): array
{
    return [
        'settings' => [
            'site_title' => 'Берёзка | Детский сад',
            'hero_badge' => 'Забота, игра и развитие каждый день',
            'hero_heading' => 'Детский сад «Берёзка»',
            'hero_text' => 'Здесь будет краткое описание детского сада. Этот текст можно заменить на информацию о подходе, атмосфере и главных преимуществах.',
            'primary_button_text' => 'Наши программы',
            'primary_button_link' => '#programs',
            'secondary_button_text' => 'Связаться',
            'secondary_button_link' => '#contact',
            'about_label' => 'Дополнительный блок',
            'about_heading' => 'Здесь будет основной заголовок раздела',
            'about_text' => 'Здесь будет основной текст раздела. Его можно заменить на описание преимуществ, ценностей, условий пребывания или другой важной информации.',
            'footer_text' => 'Здесь будет контактная информация, адрес или дополнительная подпись.',
        ],
        'programs' => [
            ['id' => 1, 'icon' => '🎨', 'title' => 'Название направления', 'description' => 'Здесь будет описание направления, программы или услуги. Добавьте нужный текст вручную.', 'sort_order' => 1],
            ['id' => 2, 'icon' => '🌿', 'title' => 'Название направления', 'description' => 'Здесь будет описание направления, программы или услуги. Добавьте нужный текст вручную.', 'sort_order' => 2],
            ['id' => 3, 'icon' => '📚', 'title' => 'Название направления', 'description' => 'Здесь будет описание направления, программы или услуги. Добавьте нужный текст вручную.', 'sort_order' => 3],
        ],
        'highlights' => [
            ['id' => 1, 'title' => 'Заголовок', 'description' => 'Краткое описание или важный факт для заполнения вручную.', 'sort_order' => 1],
            ['id' => 2, 'title' => 'Заголовок', 'description' => 'Краткое описание или важный факт для заполнения вручную.', 'sort_order' => 2],
            ['id' => 3, 'title' => 'Заголовок', 'description' => 'Краткое описание или важный факт для заполнения вручную.', 'sort_order' => 3],
        ],
    ];
}

function fetchSiteData(): array
{
    try {
        $pdo = db();

        $settingsRows = $pdo->query('SELECT setting_key, setting_value FROM site_settings')->fetchAll();
        if ($settingsRows === []) {
            return defaultSiteData();
        }

        $settings = [];
        foreach ($settingsRows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        $programs = $pdo->query('SELECT id, icon, title, description, sort_order FROM programs ORDER BY sort_order, id')->fetchAll();
        $highlights = $pdo->query('SELECT id, title, description, sort_order FROM highlights ORDER BY sort_order, id')->fetchAll();

        return [
            'settings' => array_merge(defaultSiteData()['settings'], $settings),
            'programs' => $programs ?: defaultSiteData()['programs'],
            'highlights' => $highlights ?: defaultSiteData()['highlights'],
        ];
    } catch (Throwable $exception) {
        return defaultSiteData();
    }
}

function updateSiteSettings(array $input): void
{
    $allowedKeys = array_keys(defaultSiteData()['settings']);
    $pdo = db();
    $statement = $pdo->prepare(
        'INSERT INTO site_settings (setting_key, setting_value) VALUES (:setting_key, :setting_value)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
    );

    foreach ($allowedKeys as $key) {
        $statement->execute([
            'setting_key' => $key,
            'setting_value' => trim((string)($input[$key] ?? '')),
        ]);
    }
}

function replacePrograms(array $programs): void
{
    $pdo = db();
    $pdo->beginTransaction();
    $pdo->exec('DELETE FROM programs');

    $statement = $pdo->prepare(
        'INSERT INTO programs (icon, title, description, sort_order) VALUES (:icon, :title, :description, :sort_order)'
    );

    foreach ($programs as $index => $program) {
        $title = trim((string)($program['title'] ?? ''));
        $description = trim((string)($program['description'] ?? ''));

        if ($title === '' && $description === '') {
            continue;
        }

        $statement->execute([
            'icon' => trim((string)($program['icon'] ?? '')),
            'title' => $title,
            'description' => $description,
            'sort_order' => $index + 1,
        ]);
    }

    $pdo->commit();
}

function replaceHighlights(array $highlights): void
{
    $pdo = db();
    $pdo->beginTransaction();
    $pdo->exec('DELETE FROM highlights');

    $statement = $pdo->prepare(
        'INSERT INTO highlights (title, description, sort_order) VALUES (:title, :description, :sort_order)'
    );

    foreach ($highlights as $index => $highlight) {
        $title = trim((string)($highlight['title'] ?? ''));
        $description = trim((string)($highlight['description'] ?? ''));

        if ($title === '' && $description === '') {
            continue;
        }

        $statement->execute([
            'title' => $title,
            'description' => $description,
            'sort_order' => $index + 1,
        ]);
    }

    $pdo->commit();
}
