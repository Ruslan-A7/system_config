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



    /** Додати нове джерело конфігурації */
    public function addSource(string $name, ConfigSourceInterface $source): void;

    /** Видалити джерело конфігурації */
    public function deleteSource(string $name): void;

    /** Очистити список джерел */
    public function clearSources(): void;

}