<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'azuredb';
    private $username = 'root';
    private $password = '';
    public $conn;

    // Hàm kết nối đến cơ sở dữ liệu
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Lỗi kết nối: ' . $e->getMessage();
        }

        return $this->conn;
    }

    // Hàm đăng nhập
    public function login_admin($ad_name, $ad_pass) {
        $query = 'SELECT * FROM admin WHERE username = :username AND password = :password';
        $stmt = $this->conn->prepare($query);

        $password = md5($ad_pass);

        $stmt->bindParam(':username', $ad_name);
        $stmt->bindParam(':password', $ad_pass);

        $stmt->execute();

        return $stmt;
    }
}


?>
