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



    /**
     * Отримати значення за ключем із зазначеного джерела
     * (з підтримкою вкладених ключів через роздільник вкладеності згідно $this->sources[$source]->options->ns).
     *
     * @throws Exception якщо джерело не знайдено
     */
    public function get(string $source, string $key, $default = null);

    /**
     * Отримати значення за ключем, за першим збігом, з будь-якого джерела
     * (з підтримкою вкладених ключів через роздільник вкладеності згідно $this->sources[$source]->options->ns).
     *
     * @throws Exception якщо ключ не знайдено в жодному джерелі
     */
    public function getFirst(string $key, $default = null);

    /** Отримати джерело конфігурації якщо воно є (інакше - false) */
    public function getSource(string $name): ConfigSourceInterface|false;



    /** Додати нове джерело конфігурації */
    public function addSource(string $name, ConfigSourceInterface $source): void;

    /** Видалити джерело конфігурації */
    public function deleteSource(string $name): void;

    /** Очистити список джерел */
    public function clearSources(): void;

}