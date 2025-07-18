<?php

namespace RA7\Framework\System\Config\Generators;

/**
 * Інтерфейс генератора реального конфігураційного файлу з шаблонного.
 * Призначено для генерації конфіг-файлів при встановленні додатку.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
interface GeneratorInterface {

    /**
     * Згенерувати реальний конфігураційний файл з шаблонного файлу.
     *
     * @param string $inputFile файл шаблону
     * @param string $outputFile файл для збереження результату
     * @return bool
     */
    public static function generate(string $inputFile, string $outputFile): bool;

}