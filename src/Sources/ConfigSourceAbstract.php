<?php

namespace RA7\Framework\System\Config\Sources;

use Exception;

/**
 * Абстрактне джерело конфігурації.
 * Містить універсальні властивості та методи для всіх джерел.
 *
 * Для остаточного збереження змін в цьому джерелі конфігурації потрібно скористатися методом save() - інакше вони будуть втрачені при наступному запиті.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
abstract class ConfigSourceAbstract implements ConfigSourceInterface {

    /** Статус завантаження цього джерела */
    public protected(set) bool $loaded = false {
        get => $this->loaded;
    }

    /** Опції джерела конфігурації */
    public protected(set) ConfigSourceOptions $options {
        get => $this->options;
    }

    /** Масив даних */
    protected array $data = [];



    /**
     * Створити джерело конфігурації.
     *
     * @param ConfigSourceOptions $options опції джерела конфігурації
     */
    public function __construct(ConfigSourceOptions $options = new ConfigSourceOptions()) {
        $this->options = $options;
    }



    public function getId(): string {
        return __FILE__ . ' #' . __LINE__;
    }

    public function get(string $key, $default = null) {
        $this->loaded ? /* skip */ : $this->load();

        $segments = explode($this->options->ns, $key);
        $value = $this->data;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    public function set(string $key, $value): void {
        $this->throwExceptionIfFinal();

        $this->loaded ? /* skip */ : $this->load();

        $segments = explode($this->options->ns, $key);
        $ref = &$this->data;

        foreach ($segments as $segment) {
            if (empty($ref[$segment]) || !is_array($ref[$segment])) {
                if ($this->options->strictSetter) {
                    throw new Exception('Для джерела конфігурації "' . $this->getId() . '" обмежено можливість додавання нових елементів!
                    Для скасування цього правила потрібно перевизначити значення strictSetter в опціях джерела на false!');
                }
                $ref[$segment] = [];
            }
            $ref = &$ref[$segment];
        }

        $ref = $value;
    }

    public function delete(string $key): void {
        $this->loaded ? /* skip */ : $this->load();

        $keys = explode($this->options->ns, $key);
        $data =& $this->data;
    
        while (count($keys) > 1) {
            $part = array_shift($keys);
    
            if (!isset($data[$part]) || !is_array($data[$part])) {
                // Шлях не існує — нічого не видаляємо
                return;
            }
    
            $data =& $data[$part];
        }
    
        unset($data[array_shift($keys)]);
    }

    public function has(string $key): bool {
        return $this->get($key, null) ? true : false;
    }

    public function clear(): void {
        $this->data = [];
    }



    /** Викинути виняток якщо це джерело визначено остаточним */
    protected function throwExceptionIfFinal(): void {
        if ($this->options->final) {
            throw new Exception('Джерело конфігурації "' . $this->getId() . '" є остаточним - будь-яка модифікація заборонена!
            Для скасування цього правила потрібно перевизначити відповідну опцію джерела при його ініціалізації!');
        }
    }

    /**
     * Створити порожнє джерело конфігурації згідно очікуваного типу даних в ньому.
     * Призначено на випадок якщо джерело не знайдено при ініціалізації,
     * а в опціях зазначено необхідність автоматичного створення джерела в таких випадках!
     */
    protected abstract function createSource(): bool;

}