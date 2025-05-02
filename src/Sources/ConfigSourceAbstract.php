<?php

namespace RA7\Framework\System\Config\Sources;

use Exception;

/**
 * Абстрактний клас для джерела конфігурації.
 * Містить універсальні властивості та методи для всіх джерел.
 *
 * Для остаточного збереження змін в цьому джерелі конфігурації потрібно скористатися методом save() - інакше вони будуть втрачені після при наступному запиті.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
abstract class ConfigSourceAbstract implements ConfigSourceInterface {

    /**
     * Шлях до файлу джерела (для файлових джерел)
     * або шлях до таблиці в БД типу databaseName>tableName (при цьому ОБОВ'ЯЗКОВО передати відповідний тип джерела в опції),
     * де замість '>' використовується роздільник вкладеності згідно $this->ns (для джерел з БД),
     * або інший ідентифікатор для швидкого пошуку місця розташування джерела
     */
    //!!! можливо є сенс перенести шлях в файловий ресурс, а для БД створити окремий ресурс з таблицею, полем та інш. ???
    public protected(set) string $path {
        get => $this->path;
    }

    /** Масив даних */
    protected array $data = [];

    /** Статус завантаження цього джерела */
    public protected(set) bool $loaded = false {
        get => $this->loaded;
    }

    /** Опції джерела конфігурації */
    public protected(set) ConfigSourceOptions $options {
        get => $this->options;
    }



    /**
     * Створити джерело конфігурації.
     *
     * @param string $path шлях до файлу джерела (для файлових джерел)
     * або шлях до таблиці в БД типу databaseName>tableName,
     * де замість '>' використовується роздільник вкладеності згідно $this->options->ns (для джерел з БД),
     * або інший ідентифікатор для швидкого пошуку місця розташування джерела
     * @param ConfigSourceOptions $options опції джерела конфігурації
     */
    public function __construct(string $path, ?ConfigSourceOptions $options = null) {
        $this->path = $path;
        $this->options = $options ? $options : new ConfigSourceOptions();
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
        if ($this->options->finalConfig) {
            throw new Exception('Джерело конфігурації "' . $this->path . '" є остаточним - будь-яка модифікація заборонена!
            Для скасування цього правила потрібно перевизначити відповідну опцію джерела при його ініціалізації!');
        }

        $this->loaded ? /* skip */ : $this->load();

        $segments = explode($this->options->ns, $key);
        $ref = &$this->data;

        foreach ($segments as $segment) {
            if (empty($ref[$segment]) || !is_array($ref[$segment])) {
                if ($this->options->strictSetter) {
                    throw new Exception('Для джерела конфігурації "' . $this->path . '" обмежено можливість додавання нових елементів!
                    Для скасування цього правила потрібно перевизначити його в опціях джерела!');
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



    /** Створити порожнє джерело конфігурації згідно очікуваного типу даних в ньому якщо воно не знайдено */
    protected abstract function createSourceIfNotFound(): bool;

}