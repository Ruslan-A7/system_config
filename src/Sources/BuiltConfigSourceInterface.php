<?php

namespace RA7\Framework\System\Config\Sources;

/**
 * Інтерфейс для вбудованого в PHP-код джерела конфігурації.
 * Містить унікальні властивості та методи для всіх вбудованих в PHP-код джерел.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
interface BuiltConfigSourceInterface extends ConfigSourceInterface {

    /** Ідентифікатор для швидкого пошуку місця розташування джерела */
    public string $id {get;}



    /**
     * Створити вбудоване в PHP-код джерело конфігурації.
     *
     * @param string $id ідентифікатор для швидкого пошуку місця розташування джерела
     * @param array $data початкові дані джерела
     * @param ConfigSourceOptions $options опції джерела конфігурації
     */
    public function __construct(string $id, array $data = [], ConfigSourceOptions $options = new ConfigSourceOptions(type: SourceTypeEnum::BuiltInCode, final: true));

}