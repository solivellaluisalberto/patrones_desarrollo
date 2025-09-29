<?php

class Database
{
    private static $instance = null;
    private $connection;
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'mvc_app';

    private function __construct()
    {
        // Para este ejemplo, usaremos un array en memoria
        // En una aplicación real, aquí conectarías a MySQL/PostgreSQL
        $this->connection = [];
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    // Simulación de operaciones de base de datos
    public function select($table, $conditions = [])
    {
        if (!isset($this->connection[$table])) {
            return [];
        }

        $results = $this->connection[$table];

        if (!empty($conditions)) {
            $results = array_filter($results, function($row) use ($conditions) {
                foreach ($conditions as $key => $value) {
                    if (!isset($row[$key]) || $row[$key] != $value) {
                        return false;
                    }
                }
                return true;
            });
        }

        return array_values($results);
    }

    public function insert($table, $data)
    {
        if (!isset($this->connection[$table])) {
            $this->connection[$table] = [];
        }

        $data['id'] = $this->getNextId($table);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $this->connection[$table][] = $data;
        return $data['id'];
    }

    public function update($table, $id, $data)
    {
        if (!isset($this->connection[$table])) {
            return false;
        }

        foreach ($this->connection[$table] as &$row) {
            if ($row['id'] == $id) {
                $data['updated_at'] = date('Y-m-d H:i:s');
                $row = array_merge($row, $data);
                return true;
            }
        }

        return false;
    }

    public function delete($table, $id)
    {
        if (!isset($this->connection[$table])) {
            return false;
        }

        foreach ($this->connection[$table] as $key => $row) {
            if ($row['id'] == $id) {
                unset($this->connection[$table][$key]);
                $this->connection[$table] = array_values($this->connection[$table]);
                return true;
            }
        }

        return false;
    }

    private function getNextId($table)
    {
        if (!isset($this->connection[$table]) || empty($this->connection[$table])) {
            return 1;
        }

        $maxId = max(array_column($this->connection[$table], 'id'));
        return $maxId + 1;
    }
}
