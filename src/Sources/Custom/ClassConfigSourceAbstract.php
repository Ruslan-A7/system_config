<?php

namespace RA7\Framework\System\Config\Sources\Custom;

use RA7\Framework\System\Config\Sources\ConfigSourceAbstract;
use RA7\Framework\System\Config\Sources\ConfigSourceOptions;
use RA7\Framework\System\Config\Sources\SourceTypeEnum;
use RA7\Framework\System\Config\ConfigErrorException;

/**
 * Абстрактний клас для джерела конфігурації з користувацького класу.
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
abstract class ClassConfigSourceAbstract extends ConfigSourceAbstract implements ClassConfigSourceInterface {

    /**
     * Створити вбудоване в користувацький клас джерело конфігурації.
     *
     * @param array $data початкові дані джерела
     * (ВАЖЛИВО! Ці дані не зберігаються автоматично, а лише передаються в метод `$this->setData()`)
     * @param ConfigSourceOptions $options опції джерела конфігурації
     */
    public function __construct(array $data = [], ConfigSourceOptions $options = new ConfigSourceOptions(type: SourceTypeEnum::CustomClass, final: true)) {
        parent::__construct($options);

        if ($this->options->type !== SourceTypeEnum::CustomClass) {
            throw new ConfigErrorException(
                'Всі джерела, що успадковують абстрактний клас джерела конфігурації з користувацького класу, обов\'язково повинні мати в опціях тип: ' . SourceTypeEnum::CustomClass
            );
        }

        $this->setData($data);
        is_array($this->data) ?? throw new ConfigErrorException(
            'Помилка при ініціалізації джерела конфігурації з класу "' . $this->getId() . '"! Метод "$this->setData()" обов\'язково повинен визначити властивість "$this->data"'
        );
    }



    public function load(): array {
        $this->loaded ? /* skip */ : $this->loaded = true;
        return $this->data;
    }



    public function getId(): string {
        return $this::class;
    }

    public function save(): bool {
        throw new ConfigErrorException('Джерело конфігурації з класу "' . $this->getId() . '" неможливо зберегти, навіть якщо воно не є остаточним, адже його дані визначаються безпосередньо в коді класу!');
        return  false;
    }



    protected function createSource(): bool {
        $this->data = [];
        return true;
    }

}