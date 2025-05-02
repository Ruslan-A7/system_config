<?php

namespace RA7\Framework\System\Config\Sources;

/**
 * Інтерфейс для файлового джерела конфігурації.
 * Містить унікальні властивості та методи для всіх файлових джерел.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
interface FileConfigSourceInterface extends ConfigSourceInterface {

    /** Шлях до файлу джерела */
    public string $path {get;}



    /**
     * Створити джерело конфігурації з файлу.
     *
     * @param string $path шлях до файлу джерела
     * @param ConfigSourceOptions $options опції джерела конфігурації
     */
    public function __construct(string $path, ConfigSourceOptions $options = new ConfigSourceOptions());

}