<?php

namespace Devgo;

use PDO;

/**
 * Class responsible for the db connection
 */
class Database
{

    private $host   = DB_HOST;
    private $user   = DB_USER;
    private $pass   = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $error;
    private $stmt;

    public function __construct()
    {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT         => true,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_EMULATE_PREPARES   => false
            );
        // Create a new PDO instanace
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        // Catch any errors
        catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                $type = PDO::PARAM_INT;
                break;
                case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
                case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
                default:
                $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function resultset()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }

    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }

    public function interpolateQuery($query, $params)
    {
        $keys = array();
        $values = $params;

        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:'.$key.'/';
            } else {
                $keys[] = '/[?]/';
            }

            if (is_string($value)) {
                $values[$key] = "'" . $value . "'";
            }

            if (is_array($value)) {
                $values[$key] = "'" . implode("','", $value) . "'";
            }

            if (is_null($value)) {
                $values[$key] = 'NULL';
            }
        }

        $query = preg_replace($keys, $values, $query, 1, $count);

        return $query;
    }

    public function errorInfo()
    {
        return $this->stmt->errorInfo();
    }
}


/*
------------------------------------------
use Devgo\Database as Database;
$database = new Database();

// inserir um
$database->query('INSERT INTO tabela (campo1, campo2) VALUES (:valor_campo1, :valor_campo2)');
$database->bind(':valor_campo1', 'qwe');
$database->bind(':valor_campo2', 'asd');
$database->execute();
//echo $database->lastInsertId();


// selecionar um
$database->query('SELECT campo1, campo2 FROM tabela WHERE campo1 = :valor_campo1');
$database->bind(':valor_campo1', 'zxc');
$row = $database->single();
echo "<pre>";
print_r($row);
echo "</pre>";


// selecionar varios
$database->query('SELECT campo1, campo2 FROM tabela WHERE campo2 = :valor_campo2');
$database->bind(':valor_campo2', 'qweasd');
$rows = $database->resultset();
echo "<pre>";
print_r($rows);
echo "</pre>";
//echo $database->rowCount();

// update
$this->db->query('UPDATE qwe SET produto_quantidade = :produto_quantidade WHERE  id = :carrinho_linha_id');
$this->db->bind(':produto_quantidade', $produto_qtd);
$this->db->bind(':carrinho_linha_id', $carrinho_linha_id);
$resultado = $this->db->execute();

// delete
$this->db->query("DELETE FROM qwe WHERE id_pai = :carrinho_id AND produto_id = :id_produto ");
$this->db->bind(':carrinho_id', $carrinho_id);
$this->db->bind(':id_produto', $id_produto);
$resultado = $this->db->execute();
------------------------------------------
*/;
