<?php

namespace RA7\Framework\System\Config\Sources\Custom;

use RA7\Framework\System\Config\Sources\ConfigSourceInterface;
use RA7\Framework\System\Config\Sources\ConfigSourceOptions;
use RA7\Framework\System\Config\Sources\SourceTypeEnum;

/**
 * Інтерфейс для джерела конфігурації з користувацького класу.
 * Містить унікальні властивості та методи для всіх джерел з користувацьких класів.
 *
 * Важливо! Всі джерела з користувацьких класів рекомендується робити фінальними щоб уникнути їх зміни (за замовчуванням так і є).
 * Якщо в таке джерело потрібно вносити деякі дані не одразу або навіть в різних місцях - встановіть опції "final" значення false при ініціалізації джерела,
 * а після додавання всіх потрібних даних обов'язково зробіть його фінальним ось так: $this->options->set('final', true)!
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
interface ClassConfigSourceInterface extends ConfigSourceInterface {

    /**
     * Створити вбудоване в користувацький клас джерело конфігурації.
     *
     * @param array $data початкові дані джерела
     * (ВАЖЛИВО! Ці дані не зберігаються автоматично, а лише передаються в метод `$this->setData()`)
     * @param ConfigSourceOptions $options опції джерела конфігурації
     */
    public function __construct(array $data = [], ConfigSourceOptions $options = new ConfigSourceOptions(type: SourceTypeEnum::CustomClass, final: true));

    /**
     * Визначити дані для цього джерела (викликається автоматично в кінці ініціалізації класу).
     *
     * Важливо! Цей метод обов'язково повинен визначити властивість `$this->data`.
     *
     * @param array $data початкові дані джерела, що передаються сюди при його ініціалізації
     */
    public function setData(array $data = []): void;

    /**
     * Зберегти зміни в конфігурації (оновити джерело).
     *
     * !!! ВАЖЛИВО! Спроба зберегти зміни в конфігурації (оновити джерело) через цей метод приведе до помилки адже джерело з класу треба змінювати вручну!
     */
    public function save(): bool;

}