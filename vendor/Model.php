<?php
require_once 'app/database/database.php';

class Model
{
    protected $db;
    protected $queryBuilder;
    protected $column;
    protected $value;
    protected $table;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public static function where($column, $value)
    {
        $instance = new static($GLOBALS['db']);
        $instance->column = $column;
        $instance->value = $value;
        return $instance;
    }

    public function get()
    {
        $sql = "SELECT * FROM `$this->table` WHERE $this->column = :value";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute(array(':value' => $this->value));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Query gagal: " . $e->getMessage());
        }
    }

    public function first()
    {
        $stmt = $this->db->prepare($this->queryBuilder);
        $stmt->execute([':value' => $this->value]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function find($id)
    {
        $instance = new static($GLOBALS['db']);

        if (isset($instance->primarykey)) {
            $id_table = $instance->primarykey;
            $escapedIdTableName = "`$id_table`";
            $stmt = $instance->db->prepare("SELECT * FROM `$instance->table` WHERE $escapedIdTableName = :id");
        } else {
            $stmt = $instance->db->prepare("SELECT * FROM `$instance->table` WHERE id = :id");
        }

        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$result) {
            throw new Exception("Data dengan id $id tidak ditemukan.");
        }

        return $result;
    }

    public static function all()
    {
        $instance = new static($GLOBALS['db']);
        $stmt = $instance->db->prepare("SELECT * FROM " . $instance->table);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function insert($data)
    {
        $instance = new static($GLOBALS['db']);
        $table = $instance->table;

        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $stmt = $instance->db->prepare($sql);

        $stmt->execute($data);
        return $instance->db->lastInsertId();
    }

    public static function update($data, $id)
    {
        $instance = new static($GLOBALS['db']);
        $table = $instance->table;

        $escapedTableName = "`$table`";

        if (isset($instance->primarykey)) {
            $id_table = $instance->primarykey;
            $escapedIdTableName = "`$id_table`";
            $stmt = $instance->db->prepare("SELECT COUNT(*) as count FROM $escapedTableName WHERE $escapedIdTableName = :id");
        } else {
            $stmt = $instance->db->prepare("SELECT COUNT(*) as count FROM $escapedTableName WHERE id = :id");
        }
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] == 0) {
            throw new Exception("Data dengan id $id tidak ditemukan.");
        }

        $setClause = '';
        foreach ($data as $key => $value) {
            $setClause .= "$key=:$key, ";
        }
        $setClause = rtrim($setClause, ', ');

        if (isset($escapedIdTableName)) {
            $sql = "UPDATE $escapedTableName SET $setClause WHERE $escapedIdTableName = :id";
        } else {
            $sql = "UPDATE $escapedTableName SET $setClause WHERE id = :id";
        }
        $stmt = $instance->db->prepare($sql);

        $data['id'] = $id;
        $stmt->execute($data);

        if ($table == 'users' || $table == 'user') {
            $sql = "SELECT * FROM $table WHERE id = :id";
            $stmt = $instance->db->prepare($sql);

            $stmt->execute(array(':id' => $id));

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION = $user;
            }
        }
    }

    public static function delete($id)
    {
        $instance = new static($GLOBALS['db']);
        $table = $instance->table;

        if (isset($instance->primarykey)) {
            $id_table = $instance->primarykey;
            $escapedIdTableName = "`$id_table`";
            $stmt = $instance->db->prepare("SELECT COUNT(*) as count FROM $table WHERE $escapedIdTableName = :id");
        } else {
            $stmt = $instance->db->prepare("SELECT COUNT(*) as count FROM $table WHERE id = :id");
        }

        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] == 0) {
            throw new Exception("Data dengan id $id tidak ditemukan.");
        }

        if (isset($instance->primarykey)) {
            $stmt = $instance->db->prepare("DELETE FROM $table WHERE $escapedIdTableName = :id");
        } else {
            $stmt = $instance->db->prepare("DELETE FROM $table WHERE id = :id");
        }
        $stmt->execute([':id' => $id]);
    }
}
?>