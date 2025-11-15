<?php

namespace RA7\Framework\System\Config;

use RA7\Framework\Structure\Singleton\SingletonTrait;
use RA7\Framework\System\Config\Sources\ConfigSourceInterface;
use RA7\Framework\System\Config\Sources\FromFile\DefinedFileConfigSource;
use RA7\Framework\System\Config\Sources\FromFile\ArrayFileConfigSource;

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

    /** Статус завантаження джерел конфігурації фреймворка */
    public protected(set) bool $loadedFrameworkSources = false {
        get => $this->loadedFrameworkSources;
    }



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
            throw new ConfigErrorException("Джерело конфігурації з назвою \"{$source}\" не знайдено!");
        }
    }

    public function getFirst(string $key, $default = null) {
        foreach ($this->sources as $source) {
            if ($source->get($key, null)) {
                return $source->get($key, $default);
            }
        }
        throw new ConfigErrorException("Ключ \"{$key}\" не знайдено в жодному джерелі конфігурації!");
    }

    public function getSource(string $name): ConfigSourceInterface|false {
        return $this->sources[$name] ?? false;
    }



    public function addSource(string $name, ConfigSourceInterface $source): void {
        $this->sources[$name] = $source;
    }



    public function autoloadFrameworkSources(): bool {
        if (!$this->loadedFrameworkSources) {
            // Перед реєстрацією джерела конфігурації треба робити перевірку, чи він не був зареєстрований вже раніше,
            // адже якщо якщо зареєструвати повторно одне й теж джерело з константами (або навіть різні джерела але з однаковими константами),
            // то це видасть помилку ніби джерело не визначає жодної константи (адже при завантаженні файлу воно перевіряє,
            // чи визначає файл хоч одну НОВУ констану, а якщо такі константи вже були визначені тому що раніше це джерело додавалось,
            // то конфігуратор думає, що файл не визначає константи і видає помилку) !!!
            if (!$this->getSource('app')) {
                $this->addSource('app', new DefinedFileConfigSource(
                    pathNormalize(__DIR__ . '/../../../../system/configs/framework/definition/app.php')
                ));
            }
            if (!$this->getSource('paths')) {
                $this->addSource('paths', new DefinedFileConfigSource(
                    pathNormalize(__DIR__ . '/../../../../system/configs/framework/definition/paths.php')
                ));
            }
            if (!$this->getSource('subdomains')) {
                $this->addSource('subdomains', new DefinedFileConfigSource(
                    pathNormalize(__DIR__ . '/../../../../system/configs/framework/definition/subdomains.php')
                ));
            }
            if (!$this->getSource('logging')) {
                $this->addSource('logging', new DefinedFileConfigSource(
                    pathNormalize(__DIR__ . '/../../../../system/configs/framework/definition/logging.php')
                ));
            }
            if (!$this->getSource('datetime')) {
                $this->addSource('datetime', new ArrayFileConfigSource(
                    pathNormalize(__DIR__ . '/../../../../system/configs/framework/arrays/datetime.php')
                ));
            }

            foreach ($this->sources as $k => $source) {
                if (!$source->loaded) {
                    if (empty($source->load())) {
                        return false;
                    }
                }
            }

            $this->loadedFrameworkSources = true;
        }

        return true;
    }



    public function deleteSource(string $name): void {
        unset($this->sources[$name]);
    }

    public function clearSources(): void {
        $this->sources = [];
    }

}