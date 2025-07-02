-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Июн 06 2025 г., 16:11
-- Версия сервера: 8.0.35
-- Версия PHP: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `db_kurs`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(11, 'Дом и ремонт'),
(9, 'Красота'),
(14, 'Кружка'),
(12, 'Маркетплейсы'),
(2, 'Медицина'),
(4, 'Мобильная связь'),
(5, 'Одежда и обувь'),
(8, 'Переводы'),
(1, 'Продукты'),
(10, 'Развлечения'),
(13, 'Техника'),
(3, 'Топливо'),
(7, 'Транспорт'),
(17, 'Учёба'),
(6, 'Фастфуд и рестораны');

-- --------------------------------------------------------

--
-- Структура таблицы `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `category_id`, `amount`, `type`, `description`, `created_at`) VALUES
(1, 1, 1, 6437.00, 'expense', '', '2025-05-15 20:32:41'),
(2, 1, 8, 346778.00, 'income', '', '2025-05-15 20:33:06'),
(3, 1, 11, 6574.00, 'expense', '', '2025-05-15 20:33:17'),
(4, 1, 11, 1000.00, 'expense', '', '2025-05-22 10:20:21'),
(5, 1, 10, 3000.00, 'expense', '', '2025-05-22 10:20:41'),
(6, 2, 12, 34899.00, 'income', '', '2025-06-02 13:38:49'),
(7, 2, 12, 400.00, 'expense', '', '2025-06-02 13:46:39'),
(8, 1, 2, 3390.00, 'expense', '', '2025-06-02 14:33:42'),
(9, 1, 13, 6000.00, 'expense', '', '2025-06-03 13:50:07'),
(11, 1, 8, 3242.00, 'expense', '', '2025-06-05 13:35:03'),
(12, 4, 8, 10000.00, 'income', '', '2025-06-05 20:59:20'),
(13, 4, 14, 350.00, 'expense', '', '2025-06-05 20:59:31'),
(14, 4, 1, 4500.00, 'expense', '', '2025-06-05 20:59:45'),
(15, 4, 3, 800.00, 'expense', '', '2025-06-05 21:00:01'),
(18, 6, 12, 1959.00, 'expense', '', '2025-06-06 01:52:34'),
(20, 6, 3, 2670.00, 'expense', '', '2025-06-06 01:53:01'),
(26, 6, 6, 4670.00, 'expense', '', '2025-06-06 02:13:17'),
(27, 6, 2, 3689.00, 'expense', '', '2025-06-06 02:13:33');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'Verka-admin', '$2y$10$oxaA8mXF4fYV2o/PFApKAOVLmgTgPgnH0SwTpWkXjaFb6nQaVU9ny', '2025-05-15 20:32:16'),
(2, 'Verka-test-ob', '$2y$10$jKWftqF6lyMNfX5i1CUh2ekbDCrZLlTvTmfEGXr2W/afKQH5uwH4.', '2025-06-02 13:38:20'),
(3, 'admin', '$2y$10$qGD1kwAtz5fZasWZwIdqhuuJZNQI2pv23.SP7.F1QgD0pV7948eDO', '2025-06-02 13:54:28'),
(4, 'test-org', '$2y$10$p7pqH9.de1UNmzMXKUniq.yoLTuhCVLQ/G.QF9pe2YRX0RX2GyLki', '2025-06-05 20:56:06'),
(5, 'йц', '$2y$10$Bb5g3pmnqu7lGTySmS8RYeWtopv.IW8qHkI6LKaNTNLTwznzW5HkO', '2025-06-05 23:29:48'),
(6, 'Kurs-people', '$2y$10$1iL6A5n1iAx1qDjLLt0NCOYWVF2mA8vgvg84ZPBCPcIcK27oOtsr2', '2025-06-06 01:37:14');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
