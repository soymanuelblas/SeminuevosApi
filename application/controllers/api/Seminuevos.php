<?php
require APPPATH.'libraries/REST_Controller.php';

class Seminuevos extends REST_Controller{
    public function __construct(){
        parent::__construct();
    }

    // POST: https://seminuevosapi.tubisne.mx/api/Seminuevos
    public function index_post(){
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->user)){
            if(!empty($data->pass)){
                $nameUsuario = $data->user;
                $emailUsuario = $data->pass;

                $this->response(array(
                    "status" => 1,
                    "message" => "EL MENSAJE HA SIDO ENVIADO CON ÉXITO",
                    //'data' => $data,
                ), REST_Controller::HTTP_OK);

            }
            else{
                $this->response(array(
                    "status" => 2,
                    "message" => "NO SE RECIBIÓ LA CONTRASEÑA.",
                ), REST_Controller::HTTP_NOT_FOUND);
            }
        }
        else{
            $this->response(array(
                "status" => 2,
                "message" => "NO SE RECIBIÓ EL USUARIO.",
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    // GET: https://seminuevosapi.tubisne.mx/api/Seminuevos
    public function index_get(){
        $this->response(array(
            "status" => 1,
            "message" => "created by: Manuel Blas",
        ), REST_Controller::HTTP_OK);
    }
}