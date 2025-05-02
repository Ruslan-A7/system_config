<?php

namespace RA7\Framework\System\Config\Sources;

use Exception;

/**
 * Абстрактне джерело конфігурації, що вбудовується в PHP-код.
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
abstract class BuiltConfigSourceAbstract extends ConfigSourceAbstract implements BuiltConfigSourceInterface {

    /** Ідентифікатор для швидкого пошуку місця розташування джерела */
    public protected(set) string $id {
        get => $this->id;
    }

    /**
     * Створити вбудоване в PHP-код джерело конфігурації.
     *
     * @param string $id ідентифікатор для швидкого пошуку місця розташування джерела
     * @param array $data початкові дані джерела
     * @param ConfigSourceOptions $options опції джерела конфігурації
     */
    public function __construct(string $id, array $data = [], ConfigSourceOptions $options = new ConfigSourceOptions(type: SourceTypeEnum::BuiltInCode, final: true)) {
        parent::__construct($options);
        if ($this->options->type !== SourceTypeEnum::BuiltInCode) {
            throw new Exception('Всі джерела, що успадковують абстрактний клас вбудованого джерела конфігурації, обов\'язково повинні мати в опціях тип:
            RA7\Framework\System\Config\Sources\SourceTypeEnum::BuiltInCode');
        }
        $this->id = $id;
        $this->data = $data;
    }

    public function getId(): string {
        return $this->id;
    }

    /**
     * !!! ВАЖЛИВО! Спроба зберегти зміни в конфігурації (оновити джерело) приведе до помилки адже вбудоване джерело змінювати заборонено!
     */
    public function save(): bool {
        throw new Exception('Вбудоване джерело конфігурації "' . $this->getId() . '" неможливо зберегти, навіть якщо воно не є остаточним, адже воно визначено в безпосередньо в коді!');
        return  false;
    }



    protected function createSource(): bool {
        $this->data = [];
        return true;
    }

}