<?php
namespace DataBase;

use PDO;
use PDOException;

class DataBase
{
    private $connection;
    private $option = array(PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC);


    private $dbHost= "localhost";
    private $dbName= "php-cms";
    private $dbUsername="root";
    private $dbPassword = "";

    function __construct()
    {
        try{
            $this->connection = new PDO("mysql:host=" . $this->dbHost .";dbname=" . $this->dbName,$this->dbUsername,
            $this->dbPassword,$this->option);
        }
        catch (PDOException $e){
            echo "<div style='color:red;'> Il y a un problème de connexion :</div>". $e->getMessage();
        }

    }


    public function select($sql, $values = NULL)
    {
        try{

            if ($values == null){

                return $this->connection->query($sql);
            }

            else{

                $stmt= $this->connection->prepare($sql);

                $stmt->execute($values);

                $result=$stmt;

                return $result;
            }
        }
        catch (PDOException $e){

            echo "<div style='color:red;'> Il y a un problème de connexion :</div>". $e->getMessage();
            
            return false;
        }
    }



    public function insert($tableName,$fields,$values)
    {
        try{

            $stmt= $this->connection->prepare("INSERT INTO " . $tableName . "(".implode(', ',$fields)." , created_at) VALUES ( :" . implode(', :',$fields) . " , now() );");

            $stmt->execute(array_combine($fields,$values));

            return true;
        }
        catch (PDOException $e){

            echo "<div style='color:red;'> Il y a un problème de connexion :</div>". $e->getMessage();
            
            return false;
        }
    }


    public function update($tableName, $id, $fields, $values)
    {
        $sql = "UPDATE `" . $tableName . "` SET";

        foreach (array_combine($fields, $values) as $field => $value) {

            if ($value)
            
                $sql .= " `" . $field . "`= ? ,";

            else
            
                $sql .= " `" . $field . "` = NULL,";

        }

        $sql .= " `updated_at` = now()";

        $sql .= " WHERE `id` = ?";

        try {

            $stmt = $this->connection->prepare($sql);

            $affectedrows = $stmt->execute(array_merge(array_filter(array_values($values)), [$id]));

            if (isset($affectedrows)) {

                // echo "Les enregistrements sont mis à jour";
            }

            return true;

        } catch (PDOException $e) {

            echo "<div style='color:red;'> Il y a un problème de connexion :</div>" . $e->getMessage();

            return false;
        }
    }




    public function delete($tableName, $id)
    {

        $sql="DELETE FROM " . $tableName . " WHERE `id` = ? ;";

        try{

            $stmt= $this->connection->prepare($sql);

            $affectedrows = $stmt->execute([$id]);

            if (isset($affectedrows)) {

                echo "Les enregistrements sont supprimés";
            }
            
            return true;
        }
        catch (PDOException $e) {

            echo "<div style='color:red;'> Il y a un problème de connexion :</div>" . $e->getMessage();

            return false;
        }
    }



    public function createTable($sql)
    {
        try{
            $this->connection->exec($sql);

            return true;
        }
        catch (PDOException $e){
            echo "<div style='color:red;'> Il y a un problème de connexion :</div>". $e->getMessage();
            return false;
        }


    }

}










