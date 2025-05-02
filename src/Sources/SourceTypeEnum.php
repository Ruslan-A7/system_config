<?php

namespace RA7\Framework\System\Config\Sources;

/**
 * Доступні типи джерел.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
enum SourceTypeEnum: int {

    /** Джерело з файлу */
    case File = 1;

    /** Джерело з бази даних */
    case Database = 2;

    /** Джерело з вбудованого PHP-коду */
    case BuiltInCode = 3;

    /** Джерело з власного PHP-класу */
    case CustomClass = 4;

}