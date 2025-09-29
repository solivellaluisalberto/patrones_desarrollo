<?php

abstract class Model
{
    protected $db;
    protected $table;
    protected $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all()
    {
        return $this->db->select($this->table);
    }

    public function find($id)
    {
        $results = $this->db->select($this->table, ['id' => $id]);
        return !empty($results) ? $results[0] : null;
    }

    public function where($conditions)
    {
        return $this->db->select($this->table, $conditions);
    }

    public function create($data)
    {
        // Filtrar solo los campos permitidos
        $filteredData = [];
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $filteredData[$field] = $data[$field];
            }
        }

        $id = $this->db->insert($this->table, $filteredData);
        return $this->find($id);
    }

    public function update($id, $data)
    {
        // Filtrar solo los campos permitidos
        $filteredData = [];
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $filteredData[$field] = $data[$field];
            }
        }

        $success = $this->db->update($this->table, $id, $filteredData);
        return $success ? $this->find($id) : null;
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, $id);
    }

    public function validate($data, $rules)
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $ruleArray = explode('|', $rule);
            
            foreach ($ruleArray as $singleRule) {
                if ($singleRule === 'required' && empty($data[$field])) {
                    $errors[$field] = "El campo {$field} es requerido";
                    break;
                }

                if (strpos($singleRule, 'min:') === 0) {
                    $min = (int)substr($singleRule, 4);
                    if (isset($data[$field]) && strlen($data[$field]) < $min) {
                        $errors[$field] = "El campo {$field} debe tener al menos {$min} caracteres";
                        break;
                    }
                }

                if (strpos($singleRule, 'max:') === 0) {
                    $max = (int)substr($singleRule, 4);
                    if (isset($data[$field]) && strlen($data[$field]) > $max) {
                        $errors[$field] = "El campo {$field} no puede tener más de {$max} caracteres";
                        break;
                    }
                }

                if ($singleRule === 'numeric' && isset($data[$field]) && !is_numeric($data[$field])) {
                    $errors[$field] = "El campo {$field} debe ser numérico";
                    break;
                }
            }
        }

        return $errors;
    }
}
