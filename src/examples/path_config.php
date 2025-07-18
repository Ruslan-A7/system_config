<?php
// ----------------------
// Директорії цього сайту
// ----------------------

/** ROOT або коренева директорія проекту (може містити декілька сайтів та ресурсні піддомени) */
define('DIR_ROOT', 'root' . DS);

/** 
 * Директорія сайту/домену для цього додатку з усіма його файлами
 * (фактично ROOT або коренева директорія конкретно цього сайту)
 */
define('DIR_SITE', DIR_ROOT . SITE_DOMAIN . DS);

/** Директорія з системними файлами */
define('DIR_SYSTEM', DIR_SITE . 'system' . DS);

/** Публічна директорія сайту */
define('DIR_PUBLIC', DIR_SITE . 'public' . DS);

/** Директорія додатку */
define('DIR_APP', DIR_SITE . 'app' . DS);

/** Директорія з тимчасовими файлами додатку */
define('DIR_TEMP', DIR_SITE . 'temp' . DS);

/** Директорія з кешем додатку */
define('DIR_CACHE', DIR_TEMP . 'cache' . DS);

/** Директорія з файлами встановлення додатку */
define('DIR_INSTALL', DIR_SITE . 'install' . DS);

/** Директорія з файлами оновлення додатку */
define('DIR_UPDATE', DIR_SITE . 'update' . DS);