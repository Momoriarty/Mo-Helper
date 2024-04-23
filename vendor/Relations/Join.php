<?php

class Join extends Relation
{
    private $table;
    private $primaryKey;
    private $foreignKey;

    public function __construct($table, $primaryKey, $foreignKey)
    {
        if (!is_string($table) || !is_string($primaryKey) || !is_string($foreignKey)) {
            throw new InvalidArgumentException('Table name, primary key, and foreign key must be strings.');
        }

        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->foreignKey = $foreignKey;
    }

    public function getJoinQuery($type = 'INNER')
    {
        $validTypes = ['INNER', 'LEFT', 'RIGHT', 'FULL OUTER'];
        if (!in_array(strtoupper($type), $validTypes)) {
            throw new InvalidArgumentException('Invalid join type.');
        }

        // Mengembalikan query join dengan penanganan jenis join
        return "$type JOIN $this->table ON $this->primaryKey = $this->foreignKey";
    }
}

?>