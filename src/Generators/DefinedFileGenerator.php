<?php

namespace RA7\Framework\System\Config\Generators;

/**
 * Генератор реального конфігураційного файлу з шаблонного, що містить визначення констант.
 * Призначено для генерації конфіг-файлів при встановленні додатку.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
class DefinedFileGenerator implements GeneratorInterface {

    public static function generate(string $inputFile, string $outputFile): bool {

        $source = file_get_contents($inputFile);

        // 1. Отримаємо всі визначені константи за допомогою ізольованого середовища
        $resolvedValues = self::extractDefines($source);

        // 2. Парсимо токени
        $tokens = token_get_all($source);
        $output = '';

        $state = 'default';
        $currentConst = null;
        $skipUntil = null;

        for ($i = 0; $i < count($tokens); $i++) {
            $token = $tokens[$i];

            if (is_array($token)) {
                [$id, $text] = $token;
            } else {
                $id = null;
                $text = $token;
            }

            // Пропускаємо токени, якщо потрібно
            if ($skipUntil !== null) {
                if ($text === $skipUntil) {
                    $skipUntil = null;
                }
                continue;
            }

            switch ($state) {
                case 'default':
                    if ($id === T_STRING && strtolower($text) === 'define') {
                        $state = 'define_start';
                    }
                    break;

                case 'define_start':
                    if ($text === '(') {
                        $state = 'define_name';
                    }
                    break;

                case 'define_name':
                    if ($id === T_CONSTANT_ENCAPSED_STRING) {
                        $currentConst = trim($text, "'\"");
                        $state = 'define_comma';
                    }
                    break;

                case 'define_comma':
                    if ($text === ',') {
                        $state = 'define_value';
                    }
                    break;

                case 'define_value':
                    if ($currentConst !== null && isset($resolvedValues[$currentConst])) {
                        // Вставляємо значення у вигляді PHP-літералу
                        $output .= ' ' . self::formatPhpLiteral($resolvedValues[$currentConst]);

                        // Пропускаємо всі токени до закриваючої дужки функції
                        while ($i < count($tokens)) {
                            $next = $tokens[$i];
                            $t = is_array($next) ? $next[1] : $next;
                            if ($t === ')') {
                                $output .= $t; // закриваю дужку
                                break;
                            }
                            $i++;
                        }

                        // Після закриття дужки чекаємо на крапку з комою
                        if (isset($tokens[$i + 1]) && $tokens[$i + 1] === ';') {
                            $output .= ';';
                            $i++;
                        }

                        $currentConst = null;
                        $state = 'default';
                        continue 2;
                    }
                    break;
            }

            $output .= is_array($token) ? $text : $token;
        }

        createFile($outputFile, $output);
        unset($GLOBALS['__collected_defines']);
        return true;

    }



    /**
     * Перехопити визначення константи, зберігаючи її назву та значення у глобальний масив.
     * ВАЖЛИВО! Цей метод зроблено публічним лише для використання в eval, що викликається в цьому класі!
     * НЕ використовувати цей метод вручну в інших місцях для уникнення проблем при генерації реальних конфіг-файлів!!!
     */
    public static function defineIntercept(string $name, mixed $value): void {
        $GLOBALS['__collected_defines'][$name] = $value;
    }



    /**
     * Витягнути всі define(...) з коду та обчислити їх значення з поверненням результату.
     */
    protected static function extractDefines(string $code): array {
        $sandbox = preg_replace('/\bdefine\s*\(/', 'RA7\Framework\System\Config\Generators\DefinedFileGenerator::defineIntercept(', $code);
        $GLOBALS['__collected_defines'] = [];

        // Обгортаємо eval у функцію, щоб уникнути глобального впливу
        eval("?>$sandbox");

        return $GLOBALS['__collected_defines'];
    }

    /**
     * Перетворити PHP-змінну у відповідний літерал PHP-коду для збереження в результатний файл.
     * Приклад:
     * - "text"   → 'text'
     * - true     → true
     * - 123      → 123
     * - null     → null
     */
    protected static function formatPhpLiteral(mixed $value): string {
        if (is_string($value)) {
            return "'" . addslashes($value) . "'";
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_null($value)) {
            return 'null';
        }
        return (string)$value; // для чисел
    }

}