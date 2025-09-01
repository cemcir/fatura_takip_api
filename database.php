<?php
class Database {
    private static $instance = null;
    private $conn;
    private $host = "localhost";
    private $db_name = "fatura_db";
    private $username = "root";
    private $password = "";

    private function __construct() {
        $this->connect();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    private function connect() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e) {
            die("Connection error: " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->query($sql, $data);
    }

    public function lastInsertId($table,$data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->query($sql, $data);
        return $this->conn->lastInsertId();
    }

    public function delete($table, $conditions = []) {
        if (empty($conditions)) {
            throw new Exception("DELETE işlemi için koşul zorunludur!");
        }

        $clauses = [];
        foreach ($conditions as $key => $val) {
            $clauses[] = "$key = :$key";
        }
        $sql = "DELETE FROM $table WHERE " . implode(" AND ", $clauses);
        $this->query($sql, $conditions);
        return $this->conn->lastInsertId();
    }

    public function select($table, $conditions = []) {
        $sql = "SELECT * FROM $table";
        $params = [];

        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $key => $val) {
                $clauses[] = "$key = :$key";
                $params[$key] = $val;
            }
            $sql .= " WHERE " . implode(" AND ", $clauses);
        }

        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectOne($table, $conditions = []) {
        $sql = "SELECT * FROM $table";
        $params = [];

        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $key => $val) {
                $clauses[] = "$key = :$key";
                $params[$key] = $val;
            }
            $sql .= " WHERE " . implode(" AND ", $clauses);
        }

        $stmt = $this->query($sql, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}


