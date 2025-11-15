<?php

namespace RA7\Framework\System\Config;

use RA7\Framework\Structure\Singleton\SingletonInterface;
use RA7\Framework\System\Config\Sources\ConfigSourceInterface;

/**
 * Інтерфейс універсального класу конфігурації, що може складатися з джерел різних типів в будь-якій кількості.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
interface ConfigInterface extends SingletonInterface {

    /**
     * Масив джерел конфігурації
     * @var array<string, ConfigSourceInterface>
     */
    public array $sources {get;}

    /** Статус завантаження джерел конфігурації фреймворка */
    public bool $loadedFrameworkSources {get;}



    /**
     * Отримати значення за ключем із зазначеного джерела
     * (з підтримкою вкладених ключів через роздільник вкладеності згідно $this->sources[$source]->options->ns).
     *
     * @throws ConfigErrorException якщо джерело не знайдено
     */
    public function get(string $source, string $key, $default = null);

    /**
     * Отримати значення за ключем, за першим збігом, з будь-якого джерела
     * (з підтримкою вкладених ключів через роздільник вкладеності згідно $this->sources[$source]->options->ns).
     *
     * @throws ConfigErrorException якщо ключ не знайдено в жодному джерелі
     */
    public function getFirst(string $key, $default = null);

    /** Отримати джерело конфігурації якщо воно є (інакше - false) */
    public function getSource(string $name): ConfigSourceInterface|false;



    /**
     * Додати нове джерело конфігурації
     *
     * !!! ВАЖЛИВО!
     * Перед реєстрацією джерела конфігурації з файлу з константами треба робити перевірку, чи він не був зареєстрований вже раніше,
     * адже якщо зареєструвати повторно одне й теж джерело з константами (або навіть різні джерела але з однаковими константами),
     * то це видасть помилку ніби джерело не визначає жодної константи
     * (адже при завантаженні файлу воно перевіряє, чи визначає файл хоч одну НОВУ констану,
     * а якщо такі константи вже були визначені тому що раніше це джерело додавалось, то воно рахує, що файл не визначає константи і видає помилку) !!!
     */
    public function addSource(string $name, ConfigSourceInterface $source): void;



    /**
     * Автоматично завантажити всі джерела конфігурації фреймворку.
     *
     * !!! ВАЖЛИВО!
     * Слід враховувати, що якщо до виклику цього методу буде додано якесь інше джерело конфігурації,
     * то воно теж буде завантажено автоматично (якщо до  цього не завантажувалось).
     * Враховуючи сказане - краще викликати цей метод на початку запуску додатку щоб уникнути завантаження решти джерел передчасно.
     *
     * @return true якщо вийшло завантажити всі джерела фреймворку (а також всі інші, що були додані до виклику цього методу) або якщо вони вже були завантажені раніше
     * @return false якщо якесь джерело не вийшло завантажити (точніше - якесь джерело виявилось пустим)
     */
    public function autoloadFrameworkSources(): bool;



    /** Видалити джерело конфігурації */
    public function deleteSource(string $name): void;

    /** Очистити список джерел */
    public function clearSources(): void;

}