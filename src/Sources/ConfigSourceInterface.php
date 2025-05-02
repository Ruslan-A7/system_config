<?php

namespace RA7\Framework\System\Config\Sources;

/**
 * Інтерфейс для джерела конфігурації.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
interface ConfigSourceInterface {

    /** Статус завантаження цього джерела */
    public bool $loaded {get;}

    /** Опції джерела конфігурації */
    public ?ConfigSourceOptions $options {get;}



    /** Отримати масив з усіма даними цього джерела конфігурації (за потреби завантажує їх з файлу/БД) */
    public function load(): array;

    /**
     * Отримати шлях до файлу джерела (для файлових джерел)
     * або шлях до таблиці в БД типу databaseName>tableName (при цьому ОБОВ'ЯЗКОВО передати відповідний тип джерела в опції),
     * де замість '>' використовується роздільник вкладеності згідно $this->ns (для джерел з БД),
     * або інший ідентифікатор для швидкого пошуку місця розташування джерела
     */
    public function getId(): string;

    /** Отримати значення за ключем (з підтримкою вкладених ключів через роздільник вкладеності згідно $this->options->ns) */
    public function get(string $key, $default = null);

    /** Встановити (перезаписати або додати) значення для вказаного ключа (з підтримкою вкладених ключів через роздільник вкладеності згідно $this->options->ns) */
    public function set(string $key, $value): void;

    /** Видаляє елемент з внутрішнього масиву конфігурації (з підтримкою вкладених ключів через роздільник вкладеності згідно $this->options->ns) */
    public function delete(string $key): void;

    /** Перевірити наявність елемента в цьому джерелі (з підтримкою вкладених ключів через роздільник вкладеності згідно $this->options->ns) */
    public function has(string $key): bool;

    /** Очистити всі дані цього джерела */
    public function clear(): void;

    /** Зберегти зміни в конфігурації (оновити джерело) */
    public function save(): bool;

}