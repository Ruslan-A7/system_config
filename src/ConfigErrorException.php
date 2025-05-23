<?php

namespace RA7\Framework\System\Config;

use RA7\Framework\System\Exception\Exception;
use Throwable;
use RA7\Framework\System\Exception\ExceptionMessage;
use RA7\Framework\System\Exception\ExceptionDetails;
use RA7\Framework\System\Enums\InitiatorsEnum;
use RA7\Framework\System\Exception\ExceptionTypesEnum;

/**
 * Помилка конфігурації.
 * Призначено для помилок пов'язаних з конфігурацією.
 *
 * @author Ruslan_A7 (RA7) <https://ra7.iuid.cc>
 * Код може містити деякі частини, що були створені за допомогою ChatGPT.
 * @license RA7 Open Free License
 * @github <https://github.com/Ruslan-A7>
 */
class ConfigErrorException extends Exception {

    /**
     * Створити помилку конфігурації.
     *
     * @param string $message опис помилки
     * @param int $code код винятку
     * @param null|Throwable $previous попередній виняток (використовується для відстеження ланцюжків винятків)
     */
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null) {
        $this->fullMessage = new ExceptionMessage('Config Error', $message);
        $this->details = new ExceptionDetails(InitiatorsEnum::Config, ExceptionTypesEnum::Error);

        parent::__construct($this->fullMessage, $this->details, $code, $previous);
    }

}