
<?php

require_once __DIR__ . "/../config/Database.php";

class BaseDao {

    protected $connection;
    protected $table;
    protected $primaryKey;

    public function __construct($table, $primaryKey) {
        $this->connection = Database::connect();
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    // Get all records
    public function getAll() {
        $stmt = $this->connection->prepare(
            "SELECT * FROM {$this->table}"
        );

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get one record by ID
    public function getById($id) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM {$this->table}
             WHERE {$this->primaryKey} = ?"
        );

        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insert a new record
    public function insert($data) {
        $columns = array_keys($data);

        $columnNames = implode(", ", $columns);
        $placeholders = implode(", ", array_fill(0, count($columns), "?"));

        $stmt = $this->connection->prepare(
            "INSERT INTO {$this->table} ($columnNames)
             VALUES ($placeholders)"
        );

        $stmt->execute(array_values($data));

        return $this->connection->lastInsertId();
    }

    // Update an existing record
    public function update($id, $data) {
        $columns = array_keys($data);

        $setParts = [];

        foreach ($columns as $column) {
            $setParts[] = "$column = ?";
        }

        $setClause = implode(", ", $setParts);

        $stmt = $this->connection->prepare(
            "UPDATE {$this->table}
             SET $setClause
             WHERE {$this->primaryKey} = ?"
        );

        $values = array_values($data);
        $values[] = $id;

        return $stmt->execute($values);
    }

    // Delete a record
    public function delete($id) {
        $stmt = $this->connection->prepare(
            "DELETE FROM {$this->table}
             WHERE {$this->primaryKey} = ?"
        );

        return $stmt->execute([$id]);
    }
}
