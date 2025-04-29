<?php

namespace RA7\Framework\System\Config;

use Exception;
use RA7\Framework\Structure\Singleton\SingletonTrait;
use RA7\Framework\System\Config\Sources\ConfigSourceInterface;

/**
 * Базовий універсальний клас конфігурації, що може складатися з джерел різних типів в будь-якій кількості.
 * 
 * TODO: Додати функціонал для приватних конфігурацій при доступі до яких буде перевірятись наявність певних прав доступу (для захисту від несанкціонованого доступу до приватних даних типу паролів БД з боку менеджерів/модераторів додатку)
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
class Config implements ConfigInterface {

	// Додаємо функціонал Сінглтону
	use SingletonTrait;

    /** Версія цього класу */
    const VERSION = '1.0.0';

    /**
     * Масив джерел конфігурації
     * @var array<string, ConfigSourceInterface>
     */
    public protected(set) array $sources = [];

    /**
     * !!! Масив приватних джерел конфігурації при доступі до яких буде перевірятись наявність певних прав доступу заданих при їх ініціалізації
     * !!! Незавершений експериментальний функціонал !!!
     * @var array<string, PrivateConfigSourceInterface>
     */
    public protected(set) array $privateSources = [] {
        get {
            // !!! ЕКСПЕРИМЕНТАЛЬНИЙ ФУНКЦІОНАЛ !!! Потребує модифікації!
            // !!! ЕКСПЕРИМЕНТАЛЬНИЙ ФУНКЦІОНАЛ !!! Потребує модифікації!
            // !!! ЕКСПЕРИМЕНТАЛЬНИЙ ФУНКЦІОНАЛ !!! Потребує модифікації!
            !empty($access) ? $this->privateSources : throw new Exception('Access denied!');
        }
    }



    /**
     * Робимо конструктор приватним щоб забезпечити Сінглтон і заборонити створення екземплярів ззовні.
     *
     * @param array<string, ConfigSourceInterface> $sources
     */
    private function __construct(array $sources = []) {
        $this->sources = $sources;
    }



    public function addSource(string $name, ConfigSourceInterface $source): void {
        $this->sources[$name] = $source;
    }

    public function deleteSource(string $name): void {
        unset($this->sources[$name]);
    }

    public function clearSources(): void {
        $this->sources = [];
    }

}