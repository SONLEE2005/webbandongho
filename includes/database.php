<?php
require_once __DIR__ . '/../config.php';

class Database {
    private $connection;

    public function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->connection->error);
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        if ($result) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $data;
        }

        $stmt->close();
        return $this->connection->affected_rows;
    }

    public function getLastInsertId() {
        return $this->connection->insert_id;
    }
    // Trả về 1 dòng dữ liệu (hàng đầu tiên)
    public function fetchOne($sql, $params = []) {
        $result = $this->query($sql, $params);
        return !empty($result) ? $result[0] : null;
    }

    // Trả về toàn bộ dữ liệu
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params);
    }
    public function __destruct() {
        $this->connection->close();
    }
}
