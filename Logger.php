<?php
class Logger {
    private static $messages = [];

    public static function log($message) {
        self::$messages[] = $message;
    }

    public static function getMessages() {
        return self::$messages;
    }

    public static function clear() {
        self::$messages = [];
    }
}
?>