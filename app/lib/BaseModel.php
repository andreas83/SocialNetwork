<?php
namespace SocialNetwork\app\lib;
use SocialNetwork\app\lib\database\ConnectionManager;

/**
 * Class BaseModel
 */
abstract class BaseModel
{


    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $className;


    protected $dbh;


    /**
     * BaseModel constructor.
     */
    public function __construct()
    {
        $this->db=  ConnectionManager::getInstance();
        $this->dbh = $this->db->getConnection();
        
        $this->table = $this->getSource();
        $this->className = get_class($this);
    }

    /**
     * @return string
     */
    abstract public function getSource();

    /**
     * @return string
     */
    abstract public function getPrimary();

    /**
     * @return mixed
     */
    abstract public function getBackendConfiguration();

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        $primary = $this->getPrimary();

        $sql = "SELECT * FROM " . $this->table . " WHERE $primary = $id";

        $stmt = $this->dbh->query($sql);
        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, $this->className);
        return $obj[0];
    }

    /**
     * @return mixed
     */
    public function getAll()
    {

        $sql = "SELECT * FROM " . $this->table;
        
        $stmt = $this->dbh->query($sql);
        
        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, $this->className);
        
        return $obj;
    }

    /**
     * @param bool $page
     * @param bool $term
     * @param int $show
     * @return mixed
     */
    function getPages($page=false, $term=false, $show=5)
    {
        
        $searchSQL="1=1";

        if($term) {
            /**
             * @var BaseModel $model
             */
            $model= new $this->className();
            $configuration=$model->getBackendConfiguration();
            
            $searchSQL=array();
            foreach($configuration->searchable as $prop) {
                $searchSQL[]=  $prop." LIKE :".$prop;
            }
            $searchSQL=  implode(" OR ", $searchSQL);
        }
         
        $sql="SELECT *, (SELECT COUNT(id) AS cnt FROM " . $this->table ." WHERE $searchSQL) AS cnt FROM " . $this->table ." WHERE ".$searchSQL;

       
        $sql.=" ORDER BY id DESC ";
        
        
        if ($page) {
            $page_offset = (int) ($page * $show) - $show;
            $sql.=" LIMIT $show OFFSET $page_offset";
        } else  {
            $sql.=" LIMIT $show";
        }


        $stmt = $this->dbh->prepare($sql);

        if ($term) {
            foreach($configuration->searchable as $prop) {
                $stmt->bindValue(':'.$prop, "%".$term."%", \PDO::PARAM_STR);
            }
        }
        
        
        $stmt->execute();
        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, $this->className);

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
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        
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
            $stmt->bindValue(':' . $key, $val, \PDO::PARAM_STR);
        }

        $stmt->execute();
        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, $this->className);
        return $obj;
    }

    
    /**
     * @return mixed
     */
    public function save()
    {
        $primary = $this->getPrimary();

        $tmp = get_class_vars($this->className);
        unset($tmp["dbh"], $tmp["dbobject"], $tmp[$primary], $tmp['container'], $tmp['table'], $tmp['className']);
        $columns = $tmp;

        if (empty($this->{$primary}))
        {
            $sql = 'INSERT IGNORE INTO ' . $this->table . '
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
            $stmt->bindValue(':' . $primary, $this->{$primary}, \PDO::PARAM_INT);
        }

        foreach ($columns as $key => $value) {
            if ($this->{$key} == "NULL") {
                $stmt->bindValue(':' . $key, null, \PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':' . $key, $this->{$key}, \PDO::PARAM_STR);
            }
        }

        $stmt->execute();

        if (empty($this->{$primary})) {
            return $this->dbh->lastInsertId();
        }

        // default value
        return 0;
    }
}

