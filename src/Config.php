<?php

namespace RA7\Framework\System\Config;

use RA7\Framework\Structure\Singleton\SingletonTrait;
use RA7\Framework\System\Config\Sources\ConfigSourceInterface;
use Exception;

/**
 * Універсальний клас конфігурації, що може складатися з джерел різних типів в будь-якій кількості.
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
     * Робимо конструктор приватним щоб забезпечити Сінглтон і заборонити створення екземплярів ззовні.
     *
     * @param array<string, ConfigSourceInterface> $sources
     */
    private function __construct(array $sources = []) {
        $this->sources = $sources;
    }



    public function get(string $source, string $key, $default = null) {
        if (!empty($this->sources[$source])) {
            return $this->sources[$source]->get($key, $default);
        } else {
            throw new Exception('Джерело конфігурації з назвою "' . $source . '" не знайдено!');
        }
    }

    public function getFirst(string $key, $default = null) {
        foreach ($this->sources as $source) {
            if ($source->get($key, null)) {
                return $source->get($key, $default);
            }
        }
        throw new Exception('Ключ "' . $key . '" не знайдено в жодному джерелі конфігурації!');
    }

    public function getSource(string $name): ConfigSourceInterface|false {
        return $this->sources[$name] ?? false;
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