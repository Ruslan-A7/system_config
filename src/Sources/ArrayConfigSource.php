<?php

namespace RA7\Framework\System\Config\Sources;

use Exception;

/**
 * Джерело конфігурації з php-масиву, що вбудований безпосередньо в код.
 *
 * !!! ВАЖЛИВО! Спроба зберегти зміни в конфігурації (оновити джерело) приведе до помилки адже вбудоване джерело змінювати заборонено!
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
class ArrayConfigSource extends ConfigSourceAbstract {

    /** Екземпляр цього джерела */
    private static ?self $instance = null;

    /** Отримати екземпляр (Singleton) цього класу (створивши його за відсутності). */
    public static function instance(... $args): self {
        if (self::$instance === null) {
            self::$instance = new self(... $args);
        }
        return self::$instance;
    }

    /**
     * Створити джерело конфігурації з php-масиву, що вбудований безпосередньо в код
     * (викликає стандартний конструктор та зберігає передані дані).
     *
     * @param array $data початкові дані джерела
     * @param string $path шлях до файлу джерела (для файлових джерел)
     * або шлях до таблиці в БД типу databaseName>tableName,
     * де замість '>' використовується роздільник вкладеності згідно $this->options->ns (для джерел з БД)
     * @param ?ConfigSourceOptions $options опції джерела конфігурації
     */
    public static function new(array $data, string $path, ?ConfigSourceOptions $options = new ConfigSourceOptions(finalConfig: true)): self {
        self::$instance = new self($path, $options);
        self::$instance->data = $data;
        return self::$instance;
    }



    public function load(): array {
        $this->loaded ? /* skip */ : $this->loaded = true;
        return $this->data;
    }

    /**
     * !!! ВАЖЛИВО! Спроба зберегти зміни в конфігурації (оновити джерело) приведе до помилки адже вбудоване джерело змінювати заборонено!
     */
    public function save(): bool {
        throw new Exception('Вбудоване джерело конфігурації "' . $this->path . '" неможливо зберегти, навіть якщо воно не є остаточним, адже воно визначено в безпосередньо в коді!');
        return  false;
    }



    protected function createSourceIfNotFound(): bool {
        $this->data = [];
        return true;
    }

}