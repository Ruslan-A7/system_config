<?php

namespace RA7\Framework\System\Config\Sources;

use Exception;

/**
 * Джерело конфігурації з файлу, що містить PHP-константи визначені через функцію define().
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
            !empty($newKeys) ? /* skip */ : throw new Exception('Джерело конфігурації "' . $this->getId() . '" не визначає жодної php-константи!');

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
        return file_put_contents($this->path, $content) !== false;
    }



    protected function createSource(): bool {
        return file_put_contents($this->path, "<?php\n");
    }

}