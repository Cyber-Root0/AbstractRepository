<?php
namespace BrunoAlves\abstractmodel\db;
use BrunoAlves\abstractmodel\interfaces\MysqlConnection;
class DB implements MysqlConnection{

    protected $fieldsType = [
        "integer" => \PDO::PARAM_INT,
        "string" => \PDO::PARAM_STR,
        "boolean" => \PDO::PARAM_LOB,
        "double" => \PDO::PARAM_STR,
        "float" => \PDO::PARAM_STR
    ];
    public function __construct(
        protected \PDO $connection 
    ){
    }

    public function prepare(string $abstractSql, array $values = []) : \PDOStatement | false{

       $stmt =  $this->connection->prepare($abstractSql);
        
       $finalValues = array();

       foreach($values as $key => $value){
            
             $stmt->bindValue($key+1, $value, $this->objectType($value));   
       }
       try{   
            $stmt->execute();
            
            return $stmt;
        }catch(\PDOException $e){
            echo $e->getMessage();
            return false;
        }
        
    }

    public function execute(string $sql): \PDOStatement | false{

        try{
            return $this->connection->query($sql);
        }catch(\PDOException $e){
            echo $e->getMessage();
            return false;
        }
       
    }


    private function objectType($var){

        return $this->fieldsType[gettype($var)];

    }

    function __destruct(){
        
        $this->connection = null;
    }
}