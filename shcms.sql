
-- --------------------------------------------------------

--
-- Структура таблицы `action`
--

CREATE TABLE IF NOT EXISTS `action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `id_topic` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_topic` (`id_topic`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Таблица действие на движке' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `advertisement`
--

CREATE TABLE IF NOT EXISTS `advertisement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `start` varchar(255) NOT NULL,
  `stop` varchar(255) NOT NULL,
  `group_rekl` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `alt` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Реклама' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `application`
--

CREATE TABLE IF NOT EXISTS `application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posi` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Название',
  `text` text NOT NULL COMMENT 'Описание',
  `version` varchar(255) NOT NULL COMMENT 'Версия',
  `icon` varchar(255) NOT NULL COMMENT 'Иконка',
  `author` varchar(255) NOT NULL COMMENT 'Автор',
  `dir` varchar(255) NOT NULL COMMENT 'Директория приложения',
  `app_on` int(11) NOT NULL COMMENT 'Приложение доступно',
  `official` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Приложения Системы' AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `application`
--

INSERT INTO `application` (`id`, `posi`, `name`, `text`, `version`, `icon`, `author`, `dir`, `app_on`, `official`) VALUES
(1, 3, 'Общение', 'Управление общим чатом', '2.5', 'chat.png', 'Shamsik, Inc', 'chat', 1, 3),
(2, 1, 'Форум', 'Управление форумом', '2.5', 'forums.png', 'Shamsik, inc', 'forum', 1, 3),
(3, 2, 'Новости', 'Управление Новостями', '2.5', 'news.png', 'Shamsik, Inc', 'news', 1, 3),
(4, 4, 'Библиотека', 'Библиотека на тестирование', '2.5', 'library.png', 'Shamsik, Inc', 'libs', 1, 3),
(5, 5, 'Файловая система', 'Управление Загрузками', '2.5', 'loads.png', 'Shamsik, Inc', 'download', 1, 3),
(6, 6, 'Фотоальбомы', 'Модуль Фотоальбомов', '1.0', 'forums.png', 'Shamsik, Inc', 'gallery', 1, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `ban`
--

CREATE TABLE IF NOT EXISTS `ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `reason` int(11) NOT NULL,
  `text` text NOT NULL,
  `time` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Бан с ограничением' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL COMMENT 'Текст',
  `time` int(11) NOT NULL COMMENT 'Время ',
  `id_user` int(11) NOT NULL COMMENT 'id пользователя',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Мини чат' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `down_comment`
--

CREATE TABLE IF NOT EXISTS `down_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `text` text NOT NULL,
  `id_file` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_dir` int(11) NOT NULL,
  `idir` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `files` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `countd` int(11) NOT NULL COMMENT 'Счетчик скачиваний',
  `screen` varchar(255) NOT NULL,
  `screen_2` varchar(255) NOT NULL,
  `screen_3` varchar(255) NOT NULL,
  `oldtime` int(11) NOT NULL DEFAULT '0',
  `filesize` varchar(255) NOT NULL,
  `text1` varchar(255) NOT NULL COMMENT 'Краткое описание',
  `text2` text NOT NULL COMMENT 'Полное описание',
  `desc` varchar(500) NOT NULL COMMENT 'Описание (meta):',
  `key` varchar(255) NOT NULL COMMENT 'Ключевые слова (meta)',
  `update` int(11) NOT NULL COMMENT 'Последнее редактирование',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Файлы загрузок' AUTO_INCREMENT=1;

-- --------------------------------------------------------


--
-- Структура таблицы `files_favorites`
--

CREATE TABLE IF NOT EXISTS `files_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_files` int(11) NOT NULL,
  `time` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Избранные файлы' AUTO_INCREMENT=1 ;


--
-- Структура таблицы `files_dir`
--

CREATE TABLE IF NOT EXISTS `files_dir` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Индификатор',
  `name` varchar(255) NOT NULL COMMENT 'Название папки',
  `text` varchar(255) NOT NULL COMMENT 'Описание к папки',
  `time` int(11) NOT NULL COMMENT 'Время создания папки',
  `load` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Запрет на добавления - 2 Разрешить на добавления файлов',
  `upload` enum('1','2') NOT NULL DEFAULT '1',
  `dir` int(11) NOT NULL COMMENT 'Путь',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Загрузки папки' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_category`
--

CREATE TABLE IF NOT EXISTS `forum_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Категории в форуме' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_file`
--

CREATE TABLE IF NOT EXISTS `forum_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL COMMENT 'ID поста',
  `id_them` int(11) NOT NULL COMMENT 'ID темы',
  `text` text NOT NULL COMMENT 'Текст',
  `type` varchar(255) NOT NULL COMMENT 'Тип файла',
  `size` int(11) NOT NULL COMMENT 'Размер файла',
  `time` int(11) NOT NULL COMMENT 'Время добавления',
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Прикрепление файлов к теме' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_like`
--

CREATE TABLE IF NOT EXISTS `forum_like` (
  `id_like` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_topic` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `like` enum('plus','minus') NOT NULL,
  PRIMARY KEY (`id_like`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_post`
--

CREATE TABLE IF NOT EXISTS `forum_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat` int(11) NOT NULL,
  `id_sec` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `id_top` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Сообщение к Темам' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_subsection`
--

CREATE TABLE IF NOT EXISTS `forum_subsection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Категории форума' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_topics`
--

CREATE TABLE IF NOT EXISTS `forum_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sec` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `id_cat` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `last_time` int(11) NOT NULL COMMENT 'Последнее сообщение',
  `close` enum('1','2') NOT NULL DEFAULT '1' COMMENT 'Закрыть-Открыть тему',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Темы форума' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Уникальный ID',
  `id_user` int(11) NOT NULL COMMENT 'ID Пользователя',
  `id_friends` int(11) NOT NULL COMMENT 'ID Добавленного друга',
  `approved` enum('0','1') NOT NULL COMMENT 'Утверждение на дружбу',
  `time` int(11) NOT NULL COMMENT 'Время добавления',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Друзья' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `friends_flood`
--

CREATE TABLE IF NOT EXISTS `friends_flood` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_friends` int(11) NOT NULL COMMENT 'ID друга',
  `id_user` int(11) NOT NULL COMMENT 'ID пользователя',
  `time` int(11) NOT NULL COMMENT 'Время удаления из друзей',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Действие в друзьях' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_dir`
--

CREATE TABLE IF NOT EXISTS `gallery_dir` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Папки альбомов' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_files`
--

CREATE TABLE IF NOT EXISTS `gallery_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_dir` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `images2` varchar(255) NOT NULL,
  `view` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Файлы фотоальбомов' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `ignor`
--

CREATE TABLE IF NOT EXISTS `ignor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL COMMENT 'id добавленного',
  `id_ignor` int(11) NOT NULL COMMENT 'id игнорируемого',
  `ignor_mess` enum('0','1') NOT NULL COMMENT 'Игнорировать сообщения',
  `ignor_prof` enum('0','1') NOT NULL COMMENT 'Игнорировать доступ к профилю',
  `ignor_poch` enum('0','1') NOT NULL COMMENT 'Игнорировать личные сообщения',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Управление списком игнорируемых пользователей' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `info_secure`
--

CREATE TABLE IF NOT EXISTS `info_secure` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `text` text NOT NULL COMMENT 'Текст',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Отключение Опасных PHP-функций' AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `info_secure`
--

INSERT INTO `info_secure` (`id`, `text`) VALUES
(1, 'Мы рекомендуем отключить следующие PHP функции:  exec, system, popen, proc_open, shell_exec так как они чаще всего применяются во вредоностных скриптах.\nДля того чтобы это сделать, необходимо привести раздел disable_functions файла php.ini к\n\nследующему виду: \ndisable_functions = escapeshellarg,escapeshellcmd,exec,ini_alter,parse_ini_file,passthru,pcntl_exec,popen,proc_close,proc_get_status,proc_nice,proc_open,proc_terminate,show_source,shell_exec,symlink,system\n\n[b]Если вы не в силах отключить данные функция обратитесь к вашему Хостинг провайдеру и попросите отключить выбранные функции![/b]');

-- --------------------------------------------------------

--
-- Структура таблицы `libs`
--

CREATE TABLE IF NOT EXISTS `libs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `libs_comment`
--

CREATE TABLE IF NOT EXISTS `libs_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_libs` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Коментарии к статьям' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `libs_files`
--

CREATE TABLE IF NOT EXISTS `libs_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lib` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `mtext` text NOT NULL,
  `time` int(11) NOT NULL,
  `view` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `like`
--

CREATE TABLE IF NOT EXISTS `like` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `id_list` int(11) NOT NULL COMMENT 'ID темы',
  `id_user` int(11) NOT NULL COMMENT 'ID  пользователя',
  `action` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Не активен 2 - активен',
  `text` text NOT NULL COMMENT 'Дополнительный скрытый текст',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Таблица : Мне нравится' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `log_auth`
--

CREATE TABLE IF NOT EXISTS `log_auth` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL COMMENT 'Id Пользователя',
  `text` varchar(255) NOT NULL COMMENT 'Текст сообщения',
  `ip` varchar(255) NOT NULL COMMENT 'IP Адрес',
  `browser` text NOT NULL COMMENT 'Браузер',
  `time` int(11) NOT NULL COMMENT 'Время',
  `OC` text NOT NULL COMMENT 'Операционная система',
  `version_browser` varchar(255) NOT NULL COMMENT 'Версия Браузера',
  `version_oc` varchar(255) NOT NULL COMMENT 'Версия Операционной системы',
  PRIMARY KEY (`id_log`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Лог попыток открытия Страницы' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `messaging`
--

CREATE TABLE IF NOT EXISTS `messaging` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Индификатор',
  `id_dir` int(11) NOT NULL COMMENT 'ID папки где хранится почта',
  `id_topics` int(11) NOT NULL COMMENT 'ID темы',
  `id_user` int(11) NOT NULL COMMENT 'ID отправителя',
  `id_post` int(11) NOT NULL COMMENT 'ID получателя',
  `text` text NOT NULL COMMENT 'Текст сообщения',
  `time` int(11) NOT NULL COMMENT 'Время добавления',
  `action` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 - Не прочитано 1 - Прочитано',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Личный ящик' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `messaging_dir`
--

CREATE TABLE IF NOT EXISTS `messaging_dir` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Индификатор',
  `name` varchar(255) NOT NULL COMMENT 'Название папки',
  `time` int(11) NOT NULL COMMENT 'Время создания',
  `images` varchar(255) NOT NULL COMMENT 'Картинка',
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Папки в Личном ящике' AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `messaging_dir`
--

INSERT INTO `messaging_dir` (`id`, `name`, `time`, `images`, `text`) VALUES
(1, 'Новые', 1397128653, 'mnew.png', 'Находятся все новые письма'),
(2, 'Мои переписки', 1397128668, 'gomail.png', 'Полученные отправленные письма'),
(3, 'Черновики', 1397128689, 'nmail.png', 'Письма которые не отправлены');

-- --------------------------------------------------------

--
-- Структура таблицы `messaging_topics`
--

CREATE TABLE IF NOT EXISTS `messaging_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Инфификатор',
  `id_user` int(11) NOT NULL COMMENT 'ID отправителя',
  `id_post` int(11) NOT NULL COMMENT 'ID получателя',
  `id_dir` int(11) NOT NULL COMMENT 'id папки',
  `time` int(11) NOT NULL COMMENT 'Время создания темы',
  `name` varchar(255) NOT NULL COMMENT 'Тема Сообщения',
  `start_time` int(11) NOT NULL COMMENT 'Время добавления первого поста',
  `last_time` int(11) NOT NULL COMMENT 'Время добавления последнего поста',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Темы сообщений' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `messaging_topics_user`
--

CREATE TABLE IF NOT EXISTS `messaging_topics_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL COMMENT 'ID отправителя',
  `id_dir` int(11) NOT NULL COMMENT 'ID папки',
  `id_topics` int(11) NOT NULL COMMENT 'ID темы',
  `time` int(11) NOT NULL COMMENT 'Время создания',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Дополнительное действие к личному ящику' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'noimage.gif',
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `date` varchar(255) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL,
  `id_cat` int(11) NOT NULL,
  `cr_news` text NOT NULL COMMENT 'Краткое описание',
  `view` int(11) NOT NULL COMMENT 'просмотров',
  `addnews` int(11) NOT NULL,
  `addcomm` int(11) NOT NULL,
  `noall` int(11) NOT NULL,
  `anonim` int(11) NOT NULL,
  `editm` int(11) NOT NULL,
  `timem` int(11) NOT NULL,
  `textm` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Новости' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `news_category`
--

CREATE TABLE IF NOT EXISTS `news_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Категории новостей' AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `news_category`
--

INSERT INTO `news_category` (`id`, `name`, `time`) VALUES
(1, 'Информация', 11231231);

-- --------------------------------------------------------

--
-- Структура таблицы `news_comment`
--

CREATE TABLE IF NOT EXISTS `news_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_news` int(11) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Комментарии к новостям' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `off_modules`
--

CREATE TABLE IF NOT EXISTS `off_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `off_forum` enum('1','2') NOT NULL COMMENT 'Отключаем форум',
  `text_forum` text NOT NULL COMMENT 'Текст при отключение форума',
  `off_chat` enum('1','2') NOT NULL COMMENT 'Отключение чата',
  `text_chat` text NOT NULL COMMENT 'Текст при отключение чата',
  `time_forum` int(11) NOT NULL COMMENT 'Время отключение форума',
  `time_chat` int(11) NOT NULL COMMENT 'Время отключение чата',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Отключение модулей' AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `off_modules`
--

INSERT INTO `off_modules` (`id`, `off_forum`, `text_forum`, `off_chat`, `text_chat`, `time_forum`, `time_chat`) VALUES
(1, '2', 'С сожелению Форум временно отключен!\r\n\r\nCopyright: SHCMS Engine (Шамсик Сердеров)', '2', 'С сожелению Мини-чат временно отключен!\r\n\r\nCopyright: SHCMS Engine (Шамсик Сердеров)', 1417538622, 1408374094);

-- --------------------------------------------------------

--
-- Структура таблицы `social`
--

CREATE TABLE IF NOT EXISTS `social` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vk_id` int(11) NOT NULL,
  `vk_key` varchar(255) NOT NULL,
  `vk_close` enum('1','2') NOT NULL DEFAULT '1',
  `fc_close` int(11) NOT NULL,
  `fc_id` varchar(255) NOT NULL,
  `fc_key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `status_user`
--

CREATE TABLE IF NOT EXISTS `status_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `points` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Статус пользователя' AUTO_INCREMENT=12 ;

--
-- Дамп данных таблицы `status_user`
--

INSERT INTO `status_user` (`id`, `name`, `points`) VALUES
(1, 'Простой', '1'),
(2, 'Обычный', '50'),
(3, 'Чаттер', '90'),
(4, 'Хороший', '150'),
(5, 'Очень хороший', '230'),
(6, 'Активный', '410'),
(7, 'Самый активный', '700');

-- --------------------------------------------------------

--
-- Структура таблицы `system_settings`
--

CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_kol` int(11) NOT NULL DEFAULT '1' COMMENT 'Ограниченное количество вводимых название пользвователей',
  `time_core` int(11) NOT NULL COMMENT 'Открытие сайта',
  `name_site` varchar(255) NOT NULL COMMENT 'Название сайта',
  `description` varchar(255) NOT NULL COMMENT 'Описание',
  `keywords` varchar(255) NOT NULL COMMENT 'Ключевые слова',
  `html_email` int(1) NOT NULL COMMENT 'Отправлять HTML форматированные письма',
  `email_p` varchar(255) NOT NULL COMMENT 'Email адрес для писем',
  `from_email` varchar(255) NOT NULL COMMENT 'Email адрес для поля От',
  `un_auth` int(11) NOT NULL DEFAULT '3' COMMENT 'Количество неудачных попыток  авторизоваться',
  `notify_reg` enum('1','2') NOT NULL DEFAULT '2' COMMENT 'Уведомлять при регистрации нового  пользователя?',
  `off_reg` enum('1','2') NOT NULL DEFAULT '2' COMMENT 'Отключить регистрацию?',
  `method_pass` enum('1','2') NOT NULL DEFAULT '1' COMMENT 'Метод восстановления забытого пароля',
  `antimat` enum('1','2') NOT NULL DEFAULT '1' COMMENT 'Защита от Мата',
  `antiadv` enum('1','2') NOT NULL COMMENT 'Антиреклама',
  `antilink` enum('1','2') NOT NULL COMMENT 'Удаление ссылок из текста',
  `ls_message` int(11) NOT NULL DEFAULT '10' COMMENT 'Максимальное количество ЛС на одной странице',
  `on_mail` int(11) NOT NULL DEFAULT '1' COMMENT 'Включить почту',
  `editor` enum('1','2') NOT NULL COMMENT 'Редактор',
  `filesadmin` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `system_settings`
--

INSERT INTO `system_settings` (`id`, `name_kol`, `time_core`, `name_site`, `description`, `keywords`, `html_email`, `email_p`, `from_email`, `un_auth`, `notify_reg`, `off_reg`, `method_pass`, `antimat`, `antiadv`, `antilink`, `ls_message`, `on_mail`, `editor`, `filesadmin`) VALUES
(1, 1, 0, 'Новая система SHCMS Engine', 'SHCMS Engine - Многопользовательская система управление сайтом\r\n', 'SHCMS, SHCMS Engine, CMS, Система управление сайтом', 1, 'support@shcms.ru', 'support@shcms.ru', 3, '1', '2', '1', '1', '2', '2', 10, 1, '','0');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `avatar` varchar(255) NOT NULL,
  `nick` varchar(255) NOT NULL COMMENT 'Логин',
  `password` varchar(255) NOT NULL COMMENT 'Пароль',
  `email` varchar(255) NOT NULL COMMENT 'Эл.Почта',
  `points` int(11) NOT NULL COMMENT 'Баллы',
  `warnings` int(11) NOT NULL COMMENT 'Предупреждений',
  `war_balls` int(11) NOT NULL COMMENT 'Предупредительные баллы',
  `reg_date` int(11) NOT NULL COMMENT 'Дата Регистрации',
  `lastdate` int(11) NOT NULL COMMENT 'Последний вход',
  `views` int(11) NOT NULL COMMENT 'Просмотров',
  `name` varchar(255) NOT NULL COMMENT 'Имя ',
  `family` varchar(255) NOT NULL,
  `icq` int(11) NOT NULL COMMENT 'Уин',
  `logged_ip` varchar(60) NOT NULL COMMENT 'IP пользователя',
  `group` int(11) NOT NULL DEFAULT '1' COMMENT 'Группа пользователей',
  `pol` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT 'Пол',
  `hash` varchar(255) NOT NULL COMMENT 'Хеш',
  `desc` text NOT NULL COMMENT 'Обо мне',
  `coom_prof` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Разрешить комментирование профиля',
  `add_komm` enum('0','1') NOT NULL DEFAULT '0' COMMENT ' Включено — добавлять только когда я это разрешил',
  `frend_prof` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Показывать друзей в профиле',
  `month` int(11) NOT NULL COMMENT 'Месяц рождения',
  `day` int(11) NOT NULL COMMENT 'День рождения',
  `year` int(11) NOT NULL COMMENT 'Год рождения',
  `site` varchar(255) NOT NULL COMMENT 'Сайт',
  `skype` varchar(255) NOT NULL COMMENT 'Скайп',
  `city` varchar(255) NOT NULL COMMENT 'Город',
  `key` varchar(255) NOT NULL COMMENT 'Ключевое слово',
  `new_email` varchar(255) NOT NULL COMMENT 'Новый не активированный Email',
  `new_pass` varchar(255) NOT NULL COMMENT 'Новый не активный пароль',
  `changes` enum('0','1') NOT NULL COMMENT 'Сколько изменений сделали до ограничения',
  `time_name` int(11) NOT NULL COMMENT 'Время последней смены имени',
  `restrict` int(11) NOT NULL COMMENT 'Ограничения по смени имени',
  `lostpass_n` varchar(255) NOT NULL,
  `lostpass_t` int(11) NOT NULL,
  `limit_auth` int(11) NOT NULL DEFAULT '0',
  `generate` varchar(255) NOT NULL COMMENT 'Сгенерированный пароль',
  `limit_time` int(11) NOT NULL,
  `wap_template` varchar(255) NOT NULL DEFAULT 'wap_default' COMMENT 'Мобильный шаблон',
  `web_template` varchar(255) NOT NULL DEFAULT 'web_default' COMMENT 'Компьютерный шаблон',
  `off_mail` enum('0','1') NOT NULL DEFAULT '0',
  `frating` varchar(255) NOT NULL DEFAULT '0',
  `asocial` enum('sh','vk','fc') NOT NULL DEFAULT 'sh',
  `forum_status` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Польщовательская таблица' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `vcomment`
--

CREATE TABLE IF NOT EXISTS `vcomment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_prof` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `warnings`
--

CREATE TABLE IF NOT EXISTS `warnings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `points` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Предупреждение пользователям' AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `warnings`
--

INSERT INTO `warnings` (`id`, `name`, `points`, `time`, `text`) VALUES
(1, 'Спам', 2, 0, ''),
(2, 'Нецензурные выражения', 2, 0, ''),
(3, 'Злоупотребление подписью', 2, 0, ''),
(4, 'Асоциальное поведение', 2, 0, ''),
(5, 'Черезмерный подъем тем', 2, 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `warnings_list`
--

CREATE TABLE IF NOT EXISTS `warnings_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `pr_user` text NOT NULL,
  `balls` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `time_exit` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Предупреждение пользователям' AUTO_INCREMENT=1 ;
