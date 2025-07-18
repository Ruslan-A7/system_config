<?php
// --------------------
// Дані поточного сайту
// --------------------

/** Адреса сайту з незахищеним HTTP-доступом */
define('HTTP_SERVER', "http://{$_SERVER['HTTP_HOST']}/");

/** Адреса сайту з захищеним HTTPS-доступом */
define('HTTPS_SERVER', "https://{$_SERVER['HTTP_HOST']}/");

/** Домен сайту */
define('SITE_DOMAIN', $_SERVER['HTTP_HOST']);

// ----------------------------
// Налаштування поточного сайту
// ----------------------------

/** Тема сайту */
define('SITE_THEME', 'night');

/** Мова сайту за замовчуванням */
define('DEFAULT_LANGUAGE', 'uk');

/** Локаль сайту за замовчуванням */
define('DEFAULT_LOCALE', 'uk-UA');