<?php

namespace RA7\Framework\System\Config\Sources\InCode;

use RA7\Framework\System\Config\Sources\ConfigSourceInterface;
use RA7\Framework\System\Config\Sources\ConfigSourceOptions;
use RA7\Framework\System\Config\Sources\SourceTypeEnum;

/**
 * Інтерфейс джерела конфігурації, що вбудовується в PHP-код.
 * Містить унікальні властивості та методи для всіх вбудованих в PHP-код джерел.
 *
 * Важливо! Всі вбудовані джерела рекомендується робити фінальними щоб уникнути їх зміни (за замовчуванням так і є).
 * Якщо в таке джерело потрібно вносити деякі дані не одразу або навіть в різних місцях - встановіть опції "final" значення false при ініціалізації джерела,
 * а після додавання всіх потрібних даних обов'язково зробіть його фінальним ось так: $this->options->set('final', true)! 
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