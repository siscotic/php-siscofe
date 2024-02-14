<?php
class Conexion extends mysqli {
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;

    public function __construct(){
        $listadatos = $this->datosConexion();
        foreach ($listadatos as $key => $value) {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }

        // Llama al constructor de la clase mysqli para inicializar la conexión
        parent::__construct($this->server, $this->user, $this->password, $this->database, $this->port);

        if($this->connect_errno){
            // Redirect to error page if connection fails
            header("Location: error.php");
            exit;
        }
    }

    private function datosConexion(){
        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents($direccion . "/" . "confdeploy");
        return json_decode($jsondata, true);
    }

    public function executeQuery($sqlstr){
        $results = $this->query($sqlstr);
        $resultArray = array();
        foreach ($results as $key) {
            $resultArray[] = $key;
        }
        return $this->convertirUTF8($resultArray);
    }

    public function executeUpdate($sqlstr){
        $results = $this->query($sqlstr);
        return $this->affected_rows;
    }

    //encriptar
    protected function encriptar($string){
        return md5($string);
    }

    private function convertirUTF8($array){
        array_walk_recursive($array,function(&$item,$key){
            if(!mb_detect_encoding($item,'utf-8',true)){
                $item = utf8_encode($item);
            }
        });
        return $array;
    }
}
?>
