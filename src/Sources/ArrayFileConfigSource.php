<?php

namespace RA7\Framework\System\Config\Sources;

use Exception;

/**
 * Джерело конфігурації з файлу, що повертає PHP-масив.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
class ArrayFileConfigSource extends FileConfigSourceAbstract {

    public function load(): array {
        if (!$this->loaded) {
            $data = include $this->path;

            // Перевіряємо, чи повертає джерело масив
            is_array($data) ? /* skip */ : throw new Exception("Файл конфігурації \"{$this->path}\" не повертає масив!");

            $this->data = $data;
            $this->loaded = true;
        }
        return $this->data;
    }

    /** Зберегти поточний масив у файл (перегенерувати повністю) */
    public function save(): bool {
        if ($this->options->finalConfig) {
            throw new Exception('Джерело конфігурації "' . $this->path . '" є остаточним - будь-яка модифікація заборонена!
            Для скасування цього правила потрібно перевизначити відповідну опцію джерела при його ініціалізації!');
        }

        $this->loaded ? /* skip */ : $this->load();

        $export = var_export($this->data, true);
        $content = "<?php\nreturn {$export};";

        return file_put_contents($this->path, $content) !== false;
    }



    protected function createSourceIfNotFound(): bool {
        return file_put_contents($this->path, "<?php\nreturn [];");
    }

}