<?php
class Database
{
    private $pdo;

    public function __construct($dsn, $username, $password)
    {
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO `$table` ($columns) VALUES ($values)";
        $stmt = $this->pdo->prepare($sql);
        try {
            $stmt->execute($data);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            die("Gagal melakukan penambahan data: " . $e->getMessage());
        }
    }

    public function update($table, $data, $condition)
    {
        $setClause = '';
        foreach ($data as $key => $value) {
            $setClause .= "$key=:$key, ";
        }
        $setClause = rtrim($setClause, ', ');

        $sql = "UPDATE $table SET $setClause WHERE $condition";
        $stmt = $this->pdo->prepare($sql);
        try {
            $stmt->execute($data);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die("Gagal memperbarui data: " . $e->getMessage());
        }
    }

    public function delete($table, $condition)
    {
        $sql = "DELETE FROM $table WHERE $condition";
        $stmt = $this->pdo->prepare($sql);
        try {
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die("Gagal menghapus data: " . $e->getMessage());
        }
    }

    public function where($table, $column, $value)
    {
        return new class ($this->pdo, $table, $column, $value) {
            private $pdo;
            private $table;
            private $column;
            private $value;

            public function __construct($pdo, $table, $column, $value)
            {
                $this->pdo = $pdo;
                $this->table = $table;
                $this->column = $column;
                $this->value = $value;
            }

            public function all()
            {
                $sql = "SELECT * FROM `$this->table` WHERE $this->column = :value";
                $stmt = $this->pdo->prepare($sql);
                try {
                    $stmt->execute(array(':value' => $this->value));
                    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Menggunakan fetchAll() untuk mengambil semua baris yang cocok
                } catch (PDOException $e) {
                    die("Gagal melakukan query: " . $e->getMessage());
                }
            }

            public function first()
            {
                $sql = "SELECT * FROM `$this->table` WHERE $this->column = :value";
                $stmt = $this->pdo->prepare($sql);
                try {
                    $stmt->execute(array(':value' => $this->value));
                    return $stmt->fetchObject(); // Menggunakan fetchObject() untuk mengambil objek
                } catch (PDOException $e) {
                    die("Gagal melakukan query: " . $e->getMessage());
                }
            }

        };
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function query($sql)
    {
        return $this->pdo->query($sql);
    }
}

class Auth
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public static function login($username, $password)
    {
        $instance = new static($GLOBALS['db']);

        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $instance->db->prepare($sql);
        $stmt->execute(array(':username' => $username));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION = $user;
        }
    }

    public static function logout()
    {
        $_SESSION = array();
        session_destroy();
    }
}
