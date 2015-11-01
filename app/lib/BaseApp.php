<?php

/**
 * Class BaseApp
 */
abstract class BaseApp
{
    /**
     * load database trait
     */
    use DBTrait;

    /**
     * @var string
     */
    protected $table;


    public function __construct()
    {
        $this->load_database_handler();
        $this->table = get_class($this);
    }


    /**
     * @return mixed
     */
    abstract public function getPrimary();


    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        $primary = $this->getPrimary();

        $sql = "SELECT * FROM " . $this->table . " WHERE $primary = $id";

        $stmt = $this->dbh->query($sql);
        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, $this->table);
        return $obj[0];
    }

    /**
     * @return mixed
     */
    public function getAll()
    {

        $sql = "SELECT * FROM " . $this->table;

        $stmt = $this->dbh->query($sql);
        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, $this->table);
        return $obj;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        $primary = $this->getPrimary();

        $sql = "DELETE FROM " . $this->table . " WHERE $primary = :id";

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        $obj = $stmt->execute();
        return $obj[0];

    }

    /**
     * @param $arg
     * @return mixed
     */
    public function find($arg)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE 1=1 ";
        if (is_array($arg)) {
            foreach ($arg as $key => $val) {
                $sql .= " AND $key=:$key";
            }
        }
        
        $stmt = $this->dbh->prepare($sql);

        foreach ($arg as $key => $val) {
            $stmt->bindValue(':' . $key, $val, PDO::PARAM_STR);
        }

        $stmt->execute();
        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, $this->table);
        return $obj;
    }

    /**
     * @return mixed
     */
    public function save()
    {
        $primary = $this->getPrimary();

        $tmp = get_class_vars($this->table);
        unset($tmp["dbh"], $tmp["dbobject"], $tmp[$primary], $tmp['container'], $tmp['table']);
        $columns = $tmp;

        if (empty($this->$primary)) {
            $sql = 'INSERT INTO ' . $this->table . '
            (' . implode(",", array_keys($columns)) . ') VALUES
            (:' . implode(",:", array_keys($columns)) . ')';

            $stmt = $this->dbh->prepare($sql);
        } else {
            $data = [];
            $sql = 'UPDATE ' . $this->table . ' SET ';
            foreach ($columns as $key => $value) {
                $data[] = "$key = :$key";
            }
            $sql .= implode(",", $data);
            $sql .= " WHERE $primary = :$primary";
            //echo $sql;
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':' . $primary, $this->$primary, PDO::PARAM_INT);
        }

        foreach ($columns as $key => $value) {
            if ($this->$key == "NULL") {
                $stmt->bindValue(':' . $key, null, PDO::PARAM_NULL);

            } else

                $stmt->bindValue(':' . $key, $this->$key, PDO::PARAM_STR);
        }
        $stmt->execute();
        //var_dump($stmt->errorInfo()); 
        if (empty($this->$primary)) {
            return $this->dbh->lastInsertId();
        }

        // default value
        return 0;
    }
}

