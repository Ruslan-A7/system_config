<?php

namespace RA7\Framework\System\Config\Sources\FromFile;

use RA7\Framework\System\Config\ConfigErrorException;

/**
 * Джерело конфігурації з файлу, що містить PHP-константи визначені через функцію define().
 * 
 * !!! ВАЖЛИВО!
 * Перед реєстрацією такого джерела конфігурації треба робити перевірку, чи він не був зареєстрований вже раніше,
 * адже якщо зареєструвати повторно одне й теж джерело з константами (або навіть різні джерела але з однаковими константами),
 * то це видасть помилку ніби джерело не визначає жодної константи
 * (адже при завантаженні файлу воно перевіряє, чи визначає файл хоч одну НОВУ констану,
 * а якщо такі константи вже були визначені тому що раніше це джерело додавалось, то воно рахує, що файл не визначає константи і видає помилку) !!!
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
class DefinedFileConfigSource extends FileConfigSourceAbstract {

    public function load(): array {
        if (!$this->loaded) {
            // Константи, що доступні до підключення файлу
            $definedBefore = get_defined_constants(true)['user'] ?? [];

            ob_start();
            include $this->path;
            ob_end_clean();

            // Константи, що доступні після підключення файлу
            $definedAfter = get_defined_constants(true)['user'] ?? [];

            // Знаходимо лише нові ключі
            $newKeys = array_diff_key($definedAfter, $definedBefore);

            // Перевіряємо, чи визначено хоч якусь константу в джерелі
            // !!! ВАЖЛИВО!
            // Перед реєстрацією такого джерела конфігурації треба робити перевірку, чи він не був зареєстрований вже раніше,
            // адже якщо зареєструвати повторно одне й теж джерело з константами (або навіть різні джерела але з однаковими константами),
            // то це видасть помилку ніби джерело не визначає жодної константи
            // (адже при завантаженні файлу воно перевіряє, чи визначає файл хоч одну НОВУ констану,
            // а якщо такі константи вже були визначені тому що раніше це джерело додавалось, то воно рахує, що файл не визначає константи і видає помилку) !!!
            !empty($newKeys) ? /* skip */ : throw new ConfigErrorException('Джерело конфігурації "' . $this->getId() . '" не визначає жодної php-константи!');

            // Формуємо масив нових констант
            foreach ($newKeys as $key => $_) {
                $this->data[$key] = $definedAfter[$key];
            }
            $this->loaded = true;
        }
        return $this->data;
    }

    public function save(): bool {
        $this->throwExceptionIfFinal();

        $this->loaded ? /* skip */ : $this->load();

        $lines = ["<?php"];

        foreach ($this->data as $key => $value) {
            $export = var_export($value, true);
            $lines[] = "define('" . addslashes($key) . "', {$export});";
        }

        $content = implode("\n", $lines);
        return createFile($this->path, $content) !== false;
    }



    protected function createSource(): bool {
        return createFile($this->path, "<?php\n");
    }

}