CREATE TABLE IF NOT EXISTS site_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(120) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS programs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    icon VARCHAR(30) NOT NULL DEFAULT '',
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS highlights (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_title', 'Берёзка | Детский сад'),
('hero_badge', 'Забота, игра и развитие каждый день'),
('hero_heading', 'Детский сад «Берёзка»'),
('hero_text', 'Здесь будет краткое описание детского сада. Этот текст можно заменить на информацию о подходе, атмосфере и главных преимуществах.'),
('primary_button_text', 'Наши программы'),
('primary_button_link', '#programs'),
('secondary_button_text', 'Связаться'),
('secondary_button_link', '#contact'),
('about_label', 'Дополнительный блок'),
('about_heading', 'Здесь будет основной заголовок раздела'),
('about_text', 'Здесь будет основной текст раздела. Его можно заменить на описание преимуществ, ценностей, условий пребывания или другой важной информации.'),
('footer_text', 'Здесь будет контактная информация, адрес или дополнительная подпись.')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO programs (icon, title, description, sort_order) VALUES
('🎨', 'Название направления', 'Здесь будет описание направления, программы или услуги. Добавьте нужный текст вручную.', 1),
('🌿', 'Название направления', 'Здесь будет описание направления, программы или услуги. Добавьте нужный текст вручную.', 2),
('📚', 'Название направления', 'Здесь будет описание направления, программы или услуги. Добавьте нужный текст вручную.', 3);

INSERT INTO highlights (title, description, sort_order) VALUES
('Заголовок', 'Краткое описание или важный факт для заполнения вручную.', 1),
('Заголовок', 'Краткое описание или важный факт для заполнения вручную.', 2),
('Заголовок', 'Краткое описание или важный факт для заполнения вручную.', 3);

INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$REPLACE_WITH_PASSWORD_HASH_FROM_PASSWORD_HASH_FUNCTION');

CREATE TABLE IF NOT EXISTS staff (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    description TEXT NOT NULL DEFAULT '',
    photo_filename VARCHAR(255) NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS methodologies (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    content TEXT NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO staff (name, position, description, photo_filename, sort_order) VALUES
('Иванова Мария Петровна', 'Заведующая', 'Высшее педагогическое образование, стаж работы более 20 лет. Любит детей и свою работу.', '', 1),
('Петрова Анна Сергеевна', 'Воспитатель', 'Образование: дошкольная педагогика. Творческий подход к развитию детей.', '', 2),
('Сидорова Елена Ивановна', 'Музыкальный руководитель', 'Консерваторское образование. Организует праздники и музыкальные занятия.', '', 3);

INSERT INTO methodologies (title, description, content, sort_order) VALUES
('Методика Монтессори', 'Развитие самостоятельности и естественное обучение через игровую деятельность.', 'Принципы методики включают создание подготовленной среды, свободу выбора деятельности и уважение к индивидуальности ребёнка.', 1),
('Творческое развитие', 'Раскрытие творческого потенциала через рисование, лепку, музыку и театр.', 'Занятия направлены на развитие воображения, мелкой моторики и эмоционального интеллекта детей.', 2),
('Физическое воспитание', 'Комплексный подход к здоровью: гимнастика, подвижные игры, закаливание.', 'Регулярные физические упражнения укрепляют здоровье и формируют правильные двигательные навыки.', 3);
