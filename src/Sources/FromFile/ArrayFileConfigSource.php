<?php

namespace RA7\Framework\System\Config\Sources\FromFile;

use RA7\Framework\System\Config\ConfigErrorException;

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
            is_array($data) ? /* skip */ : throw new ConfigErrorException('Джерело конфігурації "' . $this->getId() . '" не повертає масив!');

            $this->data = $data;
            $this->loaded = true;
        }
        return $this->data;
    }

    /** Зберегти поточний масив у файл (перегенерувати повністю) */
    public function save(): bool {
        $this->throwExceptionIfFinal();

        $this->loaded ? /* skip */ : $this->load();

        $export = var_export($this->data, true);
        $content = "<?php\nreturn {$export};";

        return createFile($this->path, $content);
    }



    protected function createSource(): bool {
        return createFile($this->path, "<?php\nreturn [];");
    }

}