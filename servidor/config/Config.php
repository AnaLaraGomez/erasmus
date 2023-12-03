<?php
    // Fuente: https://www.php.net/manual/es/language.constants.php
    class Config { 
        const BASE_URL = 'http://localhost/erasmus';

        public static function baseUrl() {
            return self::BASE_URL;
        }
    }

?>