-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 24 2025 г., 07:23
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `eduvideo`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cafes`
CREATE TABLE `cafes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Дамп данных таблицы `cafes`
INSERT INTO `cafes` (`name`, `description`, `location`, `logo_url`, `created_at`, `updated_at`) VALUES
('Кафе "Уют"', 'Уютное кафе с домашней атмосферой', 'г. Москва', NULL, NOW(), NOW()),
('Кофейня "Аромат"', 'Кофейня с лучшими сортами кофе', 'г. Санкт-Петербург', NULL, NOW(), NOW()),
('Кафе "Сладкий уголок"', 'Кафе с десертами и выпечкой', 'г. Казань', NULL, NOW(), NOW()),
('Кафе "Зеленый чай"', 'Здоровое питание и вегетарианские блюда', 'г. Екатеринбург', NULL, NOW(), NOW()),
('Кафе "Быстрая еда"', 'Фастфуд и закуски на вынос', 'г. Новосибирск', NULL, NOW(), NOW());

-- Структура таблицы `menu_items`
CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cafe_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Дамп данных таблицы `menu_items`
INSERT INTO `menu_items` (`cafe_id`, `name`, `description`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Капучино', 'Ароматный кофе с молочной пенкой', 150.00, NOW(), NOW()),
(1, 'Торт Наполеон', 'Слойка с кремом и орехами', 200.00, NOW(), NOW()),
(2, 'Эспрессо', 'Крепкий кофе без молока', 100.00, NOW(), NOW()),
(3, 'Шоколадный торт', 'Нежный торт с шоколадным кремом', 250.00, NOW(), NOW()),
(4, 'Салат Цезарь', 'Салат с курицей и пармезаном', 300.00, NOW(), NOW()),
(5, 'Бургер с говядиной', 'Сочный бургер с мясом и овощами', 350.00, NOW(), NOW());

-- Структура таблицы `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Дамп данных таблицы `users`
INSERT INTO `users` (`email`, `password`, `role`, `created_at`) VALUES
('admin@cafe.com', '123456', 'admin', NOW());

-- Структура таблицы `comments`
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cafe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Дамп данных таблицы для комментариев
INSERT INTO comments (cafe_id, user_id, content, created_at) VALUES
(1, 1, 'Отличное место для отдыха!', NOW()),
(2, 1, 'Лучший кофе в городе!', NOW());
-- --------------------------------------------------------

--
-- Структура таблицы `video_views`
--

CREATE TABLE `video_views` (
  `id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `college_id` (`college_id`);

--
-- Индексы таблицы `video_views`
--
ALTER TABLE `video_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `colleges`
--
ALTER TABLE `colleges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `verification_codes`
--
ALTER TABLE `verification_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `video_views`
--
ALTER TABLE `video_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD CONSTRAINT `verification_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `video_views`
--
ALTER TABLE `video_views`
  ADD CONSTRAINT `video_views_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `video_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
