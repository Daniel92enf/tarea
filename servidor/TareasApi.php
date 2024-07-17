<?php
    require_once "TareasDB.php";

    class TareasAPI {

        public function API(){
            header('Content-Type: application/JSON');
            $method = $_SERVER['REQUEST_METHOD'];
            switch ($method) {
                case 'GET':            
                    $this->procesaListar();// son funciones creadas en la parte de abajo de este archivo
                    break;
                case 'POST':
                    $this->procesaGuardar();// son funciones creadas en la parte de abajo de este archivo
                    break;
                case 'PUT':
                    $this->response(500,"error","Metodo no soportado"); // Enviar respuesta indicando que este metodo no funcina
                    break;
                case 'DELETE':
                    $this->response(500,"error","Metodo no soportado");// Enviar respuesta indicando que este metodo no funcina
                    break;
                default:
                $this->response(500,"error","Metodo no soportado");// Enviar respuesta indicando que este metodo no funcina
                break;
            }
        }

        function response($code=200, $status="", $message="") {
            http_response_code($code);
            if( !empty($status) && !empty($message) ){
                $response = array("status" => $status,"message"=>$message);
                echo json_encode($response, JSON_PRETTY_PRINT);
            }
        }

        function procesaListar(){
        
            if($_GET['action']=='tareas'){ //se verifica la acción y se verifica que actúe sobre la tabla tareas
                $tareasDB = new TareasDB();// aquí se instancia un objeto de la clase tareasdb

                if(isset($_GET['id'])){ // se solicita un registro por id
                    $response = $tareasDB->dameUnoPorId($_GET['id']);
                    echo json_encode($response, JSON_PRETTY_PRINT);// aquí se muestra la información en formato json un registro por id
                } else {
                    $response = $tareasDB->dameLista(); // de lo contrario, manda la lista completa
                    echo json_encode($response, JSON_PRETTY_PRINT); // muestra la lista en formato json
                }
            } else if ($_GET['login']=='tareas'){
                
                $tareasDB = new TareasDB();// aquí se instancia un objeto de la clase tareasdb
                
                if(isset($_GET['id'])){ // se solicita un registro por id
                    $response = $tareasDB->dameUnoPorId($_GET['id']);
                    echo json_encode($response, JSON_PRETTY_PRINT);// aquí se muestra la información en formato json un registro por id
                } else {
                    $response = $tareasDB->dameLista(); // de lo contrario, manda la lista completa
                    echo json_encode($response, JSON_PRETTY_PRINT); // muestra la lista en formato json
                }
            } else {
                $this->response(400);
            }
        }

        function procesaGuardar(){
            if($_GET['action']=='usuarios'){ // se comprueba que trabaja en la tabla usuarios
                $obj = json_decode( file_get_contents('php://input')); // asignar a la variable obj el contenido del cuerpo en la peticion 
                $objArr = (array)$obj;
                if (empty($objArr)){
                    $this->response(422,"error","Debe ingresar usuario y contraseña");
                } else if(isset($obj->user) && isset($obj->password)){                    
                    $tareasDB = new TareasDB();
                    $response = $tareasDB->dameUnoPorUsuario($obj->user);
                    if ($response && isset($response['user'])) {
                        if($response['password'] == $obj->password){
                            $this->response(200, "success", "Sesión iniciada con exito");   
                        } else{
                            $this->response(401, "error", "Contraseña incorrecta");    
                        }                
                    } else {
                        $this->response(404, "error", "Usuario no encontrado");
                    }                   
                } else {
                    $this->response(422,"error","Debe ingresar usuario y contraseña");
                }
            } else if ($_GET['action'] == 'registro'){
                $obj = json_decode( file_get_contents('php://input')); // asignar a la variable obj el contenido del cuerpo en la peticion 
                $objArr = (array)$obj;
                if (empty($objArr)){
                    $this->response(422,"error","Debe ingresar usuario y contraseña");
                } else if(isset($obj->user) && isset($obj->password)){                    
                    $tareasDB = new TareasDB();
                    $response = $tareasDB->guarda($obj->user,$obj->password);                    
                    return $this->response(200,"success","Usuario registrado con exito");
                } else {
                    $this->response(422,"error","Debe ingresar usuario y contraseña");
                }
            } else {
                $this->response(400);
            }
        }
        
    }
?>