<?php

namespace RA7\Framework\System\Config\Sources;

use Exception;

/**
 * Абстрактний клас для файлового джерела конфігурації.
 * Містить універсальні властивості та методи для всіх файлових джерел.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
abstract class FileConfigSourceAbstract extends ConfigSourceAbstract {

    /**
     * Створити джерело конфігурації з файлу.
     *
     * @param string $path шлях до файлу джерела
     * @param ConfigSourceOptions $options опції джерела конфігурації
     */
    public function __construct(string $path, ?ConfigSourceOptions $options = null) {
        parent::__construct($path, $options);

        if (!file_exists($this->path)) {
            if (!empty($this->options->createSourceIfNotFound)) {
                if ($this->options->createSourceIfNotFound) {
                    $this->createSourceIfNotFound();
                } else {
                    throw new Exception("Файл конфігурації \"{$this->path}\" не знайдено!");
                }
            }
        }
    }

}