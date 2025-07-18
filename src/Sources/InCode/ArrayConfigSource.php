<?php

namespace RA7\Framework\System\Config\Sources\InCode;

/**
 * Джерело конфігурації з php-масиву, що вбудований безпосередньо в код.
 *
 * !!! ВАЖЛИВО! Спроба зберегти зміни в конфігурації (оновити джерело) методом `$this->save()` приведе до помилки адже вбудоване джерело змінювати заборонено!
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
class ArrayConfigSource extends BuiltConfigSourceAbstract {

    public function load(): array {
        $this->loaded ? /* skip */ : $this->loaded = true;
        return $this->data;
    }

}