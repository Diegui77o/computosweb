<?php
// Modelo para hacer la conexion a la base de datos
class Conexion
{
    public $dbHost = "localhost";
    public $dbUser = "root";
    public $dbPass = "";
    public $dbName = "computosweb";
    public $db;

    // Conexion
    public function conectar()
    {
        try {
            $this->db = new PDO('mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName, $this->dbUser, $this->dbPass);
            $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            return $this->db;
        } catch (PDOException $e) {
            return $e->getMenssage();
        }
    }

    // Desconexion
    public function desconectar()
    {
        $this->db->disconnect();
    }
}
