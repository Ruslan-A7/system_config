<?php

namespace RA7\Framework\System\Config\Sources;

/**
 * Джерело конфігурації з .env-файлу.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
class EnvFileConfigSource extends FileConfigSourceAbstract {

    public function load(): array {
        if (!$this->loaded) {
            $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $data = [];

            foreach ($lines as $line) {
                $line = trim($line);

                // Ігноруємо коментарі та пусті рядки
                if ($line === '' || str_starts_with($line, '#')) {
                    continue;
                }

                // Розділити на ключ=значення
                [$key, $value] = explode('=', $line, 2) + [null, null];
                $key = trim($key);
                $value = trim($value);

                $segments = explode($this->options->ns, $key);
                $ref = &$data;

                foreach ($segments as $i => $segment) {
                    if ($i === array_key_last($segments)) {
                        // Останній елемент — присвоюємо значення
                        $ref[$segment] = $this->parseValue($value);
                    } else {
                        // Проміжні — рухаємось далі або створюємо масив, якщо його немає
                        if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
                            $ref[$segment] = [];
                        }
                        $ref = &$ref[$segment];
                    }
                }
            }

            $this->data = $data;
            $this->loaded = true;
        }
        return $this->data;
    }

    public function save(): bool {
        $this->throwExceptionIfFinal();

        $this->loaded ? /* skip */ : $this->load();

        $lines = [];

        // Рекурсивно розпаковуємо всі значення в форматі ключ => значення
        $flatData = $this->flatten($this->data);

        if (!empty($this->options->addCommentBeforeGroupInFile)) {
            // Групуємо за першою частиною ключа (до першого $this->options->ns)
            $groups = [];

            foreach ($flatData as $fullKey => $value) {
                $parts = explode($this->options->ns, $fullKey);
                $group = $parts[0];
        
                if (!isset($groups[$group])) {
                    $groups[$group] = [];
                }
        
                $groups[$group][$fullKey] = $value;
            }

            // Формуємо блоки
            foreach ($groups as $group => $items) {
                // Додаємо коментар, якщо є вкладені ключі
                if (count($items) > 1 || strpos(array_key_first($items), $this->options->ns) !== false) {
                    $lines[] = ""; // Порожній рядок перед групою
                    $lines[] = "# {$group}";
                }

                foreach ($items as $key => $value) {
                    $lines[] = "{$key}=" . $this->formatValue($value);
                }
            }
        } else {
            foreach ($flatData as $key => $value) {
                $lines[] = "{$key}=" . $this->formatValue($value);
            }
        }

        $content = implode("\n", $lines);

        return file_put_contents($this->path, $content) !== false;
    }



    protected function createSource(): bool {
        return file_put_contents($this->path, '');
    }

    /**
     * Рекурсивно пройти по масиву і повернути "плаский" (однорівневий) масив з ключами що підтримують вкладеність згідно $this->options->ns
     * (тобто вкладеність масиву перетворюється на рядки типу key>nestedKey=value для коректного збереження в рядковому форматі .env-файлів).
     */
    protected function flatten(array $data, string $prefix = ''): array {
        $result = [];

        foreach ($data as $key => $value) {
            $fullKey = $prefix === '' ? $key : $prefix . $this->options->ns . $key;

            if (is_array($value)) {
                $result += $this->flatten($value, $fullKey);
            } else {
                $result[$fullKey] = $value;
            }
        }

        return $result;
    }

    /**
     * Парсити значення з рядка до типу PHP
     */
    protected function parseValue(string $value): mixed {
        $lower = strtolower($value);

        return match ($lower) {
            'true', 'yes', 'on', '+' => true,
            'false', 'no', 'off', '-' => false,
            'null', '' => null,
            default => match (true) {
                is_numeric($value) => str_contains($value, '.') ? (float)$value : (int)$value,
                default => $value
            }
        };
    }

    /**
     * Форматувати значення для запису в .env-файл
     */
    protected function formatValue($value): string {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value === null) {
            return 'null';
        }

        return (string)$value;
    }

}