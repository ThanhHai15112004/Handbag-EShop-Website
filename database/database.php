<?php
require_once __DIR__ . '/../config/env.php';

class Database{
    private static $conn = null;
    private static $lastError = null;
    
    /* Hàm lấy kết nối CSDL */
    public static function GetConnection(){
        if(self::$conn ==null){
            try{
                $host = $_ENV->DB_HOST;
                $dbname = $_ENV->DB_NAME;
                $user = $_ENV->DB_USER;
                $pass = $_ENV->DB_PASS;

                self::$conn = new mysqli($host,$user,$pass,$dbname);

                if(self::$conn->connect_error){
                    self::$lastError = 'Kết nối CSDL thất bại: ' . self::$conn->connect_error;
                    error_log(self::$lastError);

                    self::logError('Lỗi kết nối CSDL', self::$conn->connect_error);

                    throw new Exception(self::$lastError);
                }
                if(!self::$conn->set_charset("utf8mb4")){
                    self::logError('Lỗi thiết lập charset', self::$conn->error);
                }
            }catch(Exception $e){
                self::$lastError ='Lỗi không xác định khi kết nối CSDL: ' . $e->getMessage();
                error_log(self::$lastError);
                self::$conn = null;
                throw $e;
            }
        }
        return self::$conn;
    }

    /* Hàm lấy lỗi cuối cùng nó xãy ra */
    public static function getLastError(){
        return self::$lastError;
    }
    
    // Hàm ghi lỗi vào file log
    public static function logError($type, $details){
        $logMsg = date('[Y-m-d H:i:s]') . " - $type: $details" . PHP_EOL;
        $logDir = __DIR__ . '/../storage/logs';
        if(!file_exists($logDir)){
            mkdir($logDir, 0777, true);
        }
        file_put_contents($logDir . '/database_errors.log', $logMsg, FILE_APPEND);
    }

    // Kiểm tra kết nối CSDL có còn hoạt động không
    public static function isConnected(){
        return self::$conn !== null && self::$conn->ping();
    }

    // Hàm đóng kết nối CSDL
    public static function closeConnection(){
        if(self::$conn !== null){
            $closed = self::$conn->close();
            if($closed){
                self::$conn = null;
            }
            return $closed;
        }
        return true;
    }
    
    
}
