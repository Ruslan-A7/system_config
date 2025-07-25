<?php

namespace RA7\Framework\System\Config\Sources\FromFile;

use RA7\Framework\System\Config\Sources\ConfigSourceAbstract;
use RA7\Framework\System\Config\Sources\ConfigSourceOptions;
use RA7\Framework\System\Config\ConfigErrorException;

/**
 * Абстрактне файлове джерело конфігурації.
 * Містить унікальні властивості та методи для всіх файлових джерел.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
abstract class FileConfigSourceAbstract extends ConfigSourceAbstract implements FileConfigSourceInterface {

    /** Шлях до файлу джерела (нормалізовано функцією `pathNormalize()` з пакета `ra7/utils_normalizers` при ініціалізації джерела) */
    public protected(set) string $path {
        get => $this->path;
    }

    /**
     * Створити джерело конфігурації з файлу.
     *
     * @param string $path шлях до файлу джерела (автоматично нормалізується функцією `pathNormalize()` з пакета `ra7/utils_normalizers`)
     * @param ConfigSourceOptions $options опції джерела конфігурації
     */
    public function __construct(string $path, ConfigSourceOptions $options = new ConfigSourceOptions()) {
        parent::__construct($options);

        $this->path = pathNormalize($path);

        if (!file_exists($this->path)) {
            if (!empty($this->options->createSourceIfNotFound)) {
                if ($this->options->createSourceIfNotFound) {
                    $this->createSource();
                } else {
                    throw new ConfigErrorException('Джерело конфігурації "' . $this->getId() . '" не знайдено!');
                }
            }
        }
    }

    public function getId(): string {
        return $this->path;
    }

}