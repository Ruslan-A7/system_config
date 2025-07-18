<?php

namespace RA7\Framework\System\Config\Sources;

use RA7\Framework\System\Config\ConfigErrorException;

/**
 * Опції джерела конфігурації.
 * Можна використовувати для додаткового налаштування самого джерела
 * (особливо корисно при використанні сторонніх бібліотек або у випадку коли джерела мають різні роздільники вкладеності).
 * 
 * Важливо! Опції не призначені для перезапису після ініціалізації тому їм визначено публічний доступ лише для читання,
 * а для запису захищений режим доступу завдяки "public protected(set)" перед $propName.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
class ConfigSourceOptions {

    /** Тип джерела */
    public protected(set) SourceTypeEnum $type {
        get => $this->type;
    }

    /** Необхідність автоматичного створення порожнього джерела згідно очікуваного типу даних в ньому якщо воно не знайдено */
    public protected(set) bool $createSourceIfNotFound {
        get => $this->createSourceIfNotFound;
    }

    /**
     * Роздільник вкладеності, що використовується при побудові шляхів без використання стандартного роздільника каталогів,
     * а також для масивів та інших структур.
     *
     * Рекомендується використовувати константу NS (що містить знак більше '>' або крапку '.'),
     * але якщо в джерелі використовується інший роздільник замість NS, то такому джерелу можна задати свій роздільник.
     */
    public protected(set) string $ns {
        get => $this->ns;
    }

    /** Необхідність додавати коментар з назвою групи перед групою ключів */
    public protected(set) bool $addCommentBeforeGroupInFile {
        get => $this->addCommentBeforeGroupInFile;
    }

    /**
     * Строгість сетера - визначає, чи допускається створення нових елементів при спробі встановити значення для неіснуючого ключа через метод set.
     *
     * Якщо значення = true, то нові елементи спочатку потрібно додати через метод add, а інакше буде викинуто помилку.
     */
    public protected(set) bool $strictSetter {
        get => $this->strictSetter;
    }

    /**
     * Остаточна конфігурація - визначає, чи допускається модифікація цього джерела.
     *
     * Якщо значення = true, то при спробі модифікувати це джерело буде викинуто помилку.
     * Це розповсюджується тільки на зміни через об'єкт цього джерела і не розповсюджується на ручну зміну файлу або бази даних.
     */
    public protected(set) bool $final {
        get => $this->final;
    }



    /**
     * Створити джерело конфігурації
     *
     * @param SourceTypeEnum $type тип джерела
     * @param bool $createSourceIfNotFound необхідність автоматичного створення джерела якщо його не знайдено
     * @param string $ns роздільник вкладеності, що використовується при побудові шляхів без використання стандартного роздільника каталогів,
     * а також для масивів та інших структур
     * @param bool $addCommentBeforeGroupInFile визначає необхідність додавати коментар з назвою групи перед групою ключів
     * @param bool $strictSetter визначає, чи допускається створення нових елементів при спробі встановити значення для неіснуючого ключа через метод set
     * @param bool $final визначає, чи допускається модифікація цього джерела (якщо `true` - модифікацію заборонено)
     */
    public function __construct(
        SourceTypeEnum $type = SourceTypeEnum::File,
        bool $createSourceIfNotFound = false,
        string $ns = NS,
        bool $addCommentBeforeGroupInFile = false,
        bool $strictSetter = false,
        bool $final = true) {

        $this->type = $type;
        $this->createSourceIfNotFound = $createSourceIfNotFound;
        $this->ns = $ns;
        $this->addCommentBeforeGroupInFile = $addCommentBeforeGroupInFile;
        $this->strictSetter = $strictSetter;
        $this->final = $final;

    }

    /**
     * Визначити значення вказаної властивості.
     * Тип значення має відповідати типу властивості!
     */
    public function set(string $propName, $value) {
        $this->final !== true ? /* skip */ : throw new ConfigErrorException('Це джерело конфігурації є остаточним - будь-яка модифікація заборонена!
            Для скасування цього правила потрібно перевизначити відповідну опцію джерела при його ініціалізації'); 

        if (property_exists($this, $propName)) {
            $this->$propName = $value;
        } else {
            throw new ConfigErrorException('Опції конфігурацій не мають властивості: ' . $propName);
        }
    }

}