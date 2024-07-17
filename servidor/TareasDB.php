<?php
    class TareasDB {
    
        protected $mysqli;
        const LOCALHOST = 'localhost'; // 127.0.0.1
        const USER = 'root';
        const PASSWORD = '';
        const DATABASE = 'agenda';
        /**
        * Constructor de clase Inicializa la variable mysqli
        */
        public function __construct() {
            try{
                $this->mysqli = new mysqli(self::LOCALHOST, self::USER, self::PASSWORD, self::DATABASE);
            } catch (mysqli_sql_exception $e) {
                http_response_code(500);
                exit;
            }
        }
       
        public function dameUnoPorUsuario($user=""){ //función que retorna un registro por medio de un user
            $stmt = $this->mysqli->prepare("SELECT * FROM usuarios WHERE user=? ; "); // se prepara la consulta con prepare por medio de la conexión que tenemos
            $stmt->bind_param('s', $user); // en lugar de la interrogación, coloque el valor de la variable user
            $stmt->execute();
            $result = $stmt->get_result();
            $tarea = $result->fetch_assoc();
            $stmt->close();
            return $tarea;
        }
       
        public function guarda($user, $password){ //esta función guarda un registro
            $stmt = $this->mysqli->prepare("INSERT INTO usuarios(user, password) VALUES(?, ?)");
            $stmt->bind_param('ss', $user, $password);
            $r = $stmt->execute();
            $stmt->close();
            return $r;
        }        
    }
?>