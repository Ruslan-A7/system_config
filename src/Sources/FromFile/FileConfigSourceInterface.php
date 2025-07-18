<?php

namespace RA7\Framework\System\Config\Sources\FromFile;

use RA7\Framework\System\Config\Sources\ConfigSourceInterface;
use RA7\Framework\System\Config\Sources\ConfigSourceOptions;

/**
 * Інтерфейс файлового джерела конфігурації.
 * Містить унікальні властивості та методи для всіх файлових джерел.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
interface FileConfigSourceInterface extends ConfigSourceInterface {

    /** Шлях до файлу джерела (нормалізовано функцією `pathNormalize()` з пакета `ra7/utils_normalizers` при ініціалізації джерела) */
    public string $path {get;}



    /**
     * Створити джерело конфігурації з файлу.
     *
     * @param string $path шлях до файлу джерела (автоматично нормалізується функцією `pathNormalize()` з пакета `ra7/utils_normalizers`)
     * @param ConfigSourceOptions $options опції джерела конфігурації
     */
    public function __construct(string $path, ConfigSourceOptions $options = new ConfigSourceOptions());

}