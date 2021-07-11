<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'next88' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'root' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', '' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'CzcF%sc]@pvB9azo;f.Gb_:k%TMpb[U?6/cas0}S0Bqlj1f%hyeG8Uv^(h[-G!R:' );
define( 'SECURE_AUTH_KEY',  'uD`(OZ3HSS=*MfkiLiTQTADf|E-^(]b%!_ZEzJabPE0Wfyh[bY>$jDDK2nD@Y^U]' );
define( 'LOGGED_IN_KEY',    'YgUq$wA9%?66&`I]26z=o|P`kFVo!d7M}!9<p?+!{ni$O#vUF$d=Ho(K,m~qB{F3' );
define( 'NONCE_KEY',        'S|7Ar2-vH=3H_s^(,RqVE{bF`TikdQlDa6)2k)yLmb/u+6~ThnlR9J$O D*zJ.[J' );
define( 'AUTH_SALT',        'ZX;4ZK.JKQy_4MmoS% }{YVTg}>P4{^i~B%uRI~3[>pY2P<#`7terp?iBW{*y!`?' );
define( 'SECURE_AUTH_SALT', 'GcOv9HaEe}<n@7/1#ctdPF$},3,L_jOrn{?Mi_::iZ3kWE;(U#%&}b57~!mK5&f5' );
define( 'LOGGED_IN_SALT',   'B/Or*5<fF^DWX2Qq#_Soml*qQ}Wv/]aKXo@=5wyfAlQ{!MIdezHl%pPOvkdpPdM}' );
define( 'NONCE_SALT',       'hEtQCm77NBxrni~I`Hf*SLzm2rfS`(U*D`*lx9vZ2~}{3Ou-/Zfxp]|_NTP#^u)7' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
