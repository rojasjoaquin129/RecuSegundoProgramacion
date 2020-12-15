<?php
namespace App\Controllers;
use Firebase\JWT\JWT;
use App\Models\Inscripcion;
use App\Models\Materia;
use App\Models\User;
use Slim\Factory\AppFactory;
$app = AppFactory::create();
class InscripcionController
{

    public function getALL( $request, $response, $args) {

        $inscripcion =  Inscripcion::where('idMateria', $args['idMateria'] )->get();

        $alumnos = [];
        for ($i=0; $i < count($inscripcion); $i++) { 
            if($inscripcion[$i] ){
                $nombre =  User::where('id', $inscripcion[$i]->idAlumno)->first();
                array_push($alumnos, $nombre);
            }
        }


        $response->getBody()->write(json_encode($alumnos));
        return $response;
    }


    public  function add ($request, $response, $args) {


        

        $Key = "segundoparcial";
        $token = $_SERVER['HTTP_TOKEN'];
        $decode = JWT::decode($token,$Key,array('HS256'));    
        
        $incripcion = new Inscripcion;

        $existMateria =  Materia::where('id', $args['idMateria'] )->first();
        //echo $existMateria;
        //die();
        if(empty($existMateria)){ // si  existe
            
            $result =  array("ERROR" => "NO EXISTE LA MATERIA");
            $response->getBody()->write(json_encode($result));
        }else{

            if($existMateria->cupos > 0){
                $incripcion->idAlumno = $decode->id;
                $incripcion->idMateria = $existMateria->id;
    
                $incripcion->save();

                $existMateria->cupos --;
                $existMateria->save();
                
                $result = array("True" => $incripcion);
                $response->getBody()->write(json_encode($result));

            }else{
               
                $result= array("ERROR" => "NO HAY CUPOS");
                $response->getBody()->write(json_encode($result));
            }
           
        }
        

        return $response;
    }

}
    

