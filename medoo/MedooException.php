<?php

namespace Medoo;
use Medoo\tools\StatusTypes;

class MedooException extends \Exception {
    public function __construct($message, $code, $errorType, int $status = 0, $hint = null, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->status = $status;
        $this->errorType = $errorType;
        $this->hint = $hint;
        $this->payload = [
            'error' => $errorType,
            'error_description' => $message,
        ];
        if ($hint !== null) {
            $this->payload['hint'] = $hint;
        }
    }

    public static function debugFileNotSet(string $message) {
        return new static($message, StatusTypes::DEBUG_FILE_NOT_SET, 'debug_file_not_set');
    }

    public static function debugFileNotWritable(string $message) {
        return new static($message, StatusTypes::DEBUG_FILE_NOT_WRITABLE, 'debug_file_not_writable');
    }

    public static function debugModeConfigError(string $message) {
        return new static($message, StatusTypes::DEBUG_MODE_CONFIG_ERROR, 'debug_mode_config_error');
    }
}
