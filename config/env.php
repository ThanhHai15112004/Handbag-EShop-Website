<?php
// Đọc file .env và lưu vào biến toàn cục
class Env {
    private static $variables = null;
    
    public static function load() {
        if (self::$variables === null) {
            self::$variables = [];
            
            $envFile = __DIR__ . '/../config/.env';
            if (file_exists($envFile)) {
                self::$variables = parse_ini_file($envFile);
            }
        }
        
        return self::$variables;
    }
    public static function get($key, $default = null) {
        $variables = self::load();
        return isset($variables[$key]) ? $variables[$key] : $default;
    }
}

// Tạo đối tượng $_ENV
global $_ENV;
$_ENV = new class {
    public function __get($name) {
        return Env::get($name);
    }
    
    public function __isset($name) {
        $variables = Env::load();
        return isset($variables[$name]);
    }
};