<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of conexion
 *
 * @author Tatan
 */
class Conexion {
    
    function __construct() {
    }
    /**
    * Permite conectarse a la base de datos con las credenciales especificadas
    * @return retorna la conexion
    */
    public function conectar (){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "radio_cognitiva";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            echo "<script>alert('error');</script>";
            die("Connection failed: " . $conn->connect_error);
        }
    return $conn;
    }
    /**
     * Realiza consultas a la base de datos
     * @param type $sql Recibe la sentencia sql 
     * @return type retorna el resultado para que sea recorrido desde donde lo llamaron
     */
    public function consultar($sql){
//        echo 'conexion:consultar: '.$sql;
        $conn = $this->conectar();
        $result = $conn->query($sql);
        if(!$result){
            printf(mysqli_error($conn));
        }
        $conn->close();
//        echo 'conexion:consultar: '.$result->num_rows;
        return  $result;
    }
    
    public function insertar($sql){
        $conn = $this->conectar();
        if ($conn->query($sql) === TRUE) {
            $conn->close();
            return TRUE;
//            "Registro insertado exitosamente";
        } else {
            $conn->close();
            return FALSE;
//            "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
    public function actualizar($sql){
        $conn = $this->conectar();
        if ($conn->query($sql) === TRUE) {
            $conn->close();
            return TRUE;
//            "Registro actualizado exitosamente";
        } else {
            $conn->close();
            return FALSE;
//            "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
 }

?>

