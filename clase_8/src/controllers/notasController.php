<?php
namespace App\Controllers;

use App\Models\Nota;
use App\Models\Materia;
use App\Models\User;

class NotasController
{
    public  function update  ($request,  $response, $args) {
        

        $existMateria =  Materia::where('id',$args['idMateria'] )->first();
        if(empty($existMateria))
        {
            $result =  array("ERROR" => "NO EXISTE LA MATERIA");
            $response->getBody()->write(json_encode($result));
        }else
        {
            $idAlumno= $request->getParsedBody()['idAlumno'] ?? '';
            
            $existAlumno=User::where('id',$idAlumno)->first();
            if(empty($existMateria) )
            {
                $result =  array("ERROR" => "NO EXISTE ALUMNO");
                $response->getBody()->write(json_encode($result));
            }else{
               
                $notas= $request->getParsedBody()['nota'] ?? '';
                if($notas<0 && $notas>10)
                {
                    $result =  array("ERROR" => "Nota fuera de los parametros");
                    $response->getBody()->write(json_encode($result));
                }
                else
                {
                        $nota = new Nota;
                        $nota['idAlumno'] = $request->getParsedBody()['idAlumno'] ?? '';
                        $nota['nota'] = $request->getParsedBody()['nota'] ?? '';
                        $rta=$nota->save();
                        $response->getBody()->write(json_encode($rta));
                    
                    
                    
                }
            }
        }
        return $response;  
    }
}