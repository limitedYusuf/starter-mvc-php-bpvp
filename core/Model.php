<?php

class Model extends PDO
{
    // Made By : Yusuf Limited
    public function __construct()
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT;
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

        try {
            $conn = getDatabaseConnection();
            parent::__construct($dsn, DB_USER, DB_PASS, $options);
            $this->exec("SET NAMES 'utf8mb4'");
            // force to remove ONLY_FULL_GROUP_BY
            $stmt = $conn->prepare("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            die();
        }
    }

    public function getData($table, $filters, $relationships = [])
    {
        $conditions = [];
        $params = [];

        foreach ($filters as $field => $value) {
            if ($field === 'orderby' || $field === 'relationships') {
                continue;
            }
            $conditions[] = "$field = :$field";
            $params[":$field"] = $value;
        }

        $whereClause = implode(' AND ', $conditions);
        $orderBy = '';

        if (isset($filters['orderby'])) {
            $orderByField = $filters['orderby'];
            $orderByDirection = $filters['order'] ?? 'ASC';
            $orderBy = "ORDER BY $orderByField $orderByDirection";
        }

        $query = "SELECT * FROM $table WHERE $whereClause $orderBy";

        if (!empty($relationships)) {
            foreach ($relationships as $relationship) {
                $query .= " JOIN $relationship[0] ON $relationship[1]";
            }
        }

        $stmt = $this->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAll($table, $orderBy = null, $orderDirection = 'ASC', $relationships = [])
    {
        $query = "SELECT * FROM $table";

        if (!empty($relationships)) {
            foreach ($relationships as $relationship) {
                $query .= " JOIN $relationship[0] ON $relationship[1]";
            }
        }

        if ($orderBy !== null) {
            $query .= " ORDER BY $orderBy $orderDirection";
        }

        $stmt = $this->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->prepare($query);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $this->beginTransaction();
        try {
            $stmt->execute();
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function update($table, $data, $conditions = [])
    {
        $setColumns = [];
        foreach ($data as $key => $value) {
            $setColumns[] = "$key = :$key";
        }

        $setClause = implode(', ', $setColumns);

        $whereConditions = [];
        foreach ($conditions as $field => $value) {
            $whereConditions[] = "$field = :cond_$field";
            $data[":cond_$field"] = $value;
        }

        $whereClause = implode(' AND ', $whereConditions);

        $query = "UPDATE $table SET $setClause WHERE $whereClause";
        $stmt = $this->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $this->beginTransaction();
        try {
            $stmt->execute();
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function delete($table, $conditions = [])
    {
        if (empty($conditions)) {
            throw new Exception("No conditions provided for delete operation.");
        }

        $whereConditions = [];
        $params = [];
        foreach ($conditions as $field => $value) {
            $whereConditions[] = "$field = :cond_$field";
            $params[":cond_$field"] = $value;
        }

        $whereClause = implode(' AND ', $whereConditions);
        $query = "DELETE FROM $table WHERE $whereClause";

        $stmt = $this->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $this->beginTransaction();
        try {
            $stmt->execute();
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            throw $e;
        }
    }
}
