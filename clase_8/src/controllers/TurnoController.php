<?php
namespace App\Controllers;

use App\Models\Turno;
use App\Models\User;
use App\Models\Mascota;
use \Firebase\JWT\JWT;
use PDO;


class TurnoController
{
    public function addTurno($request, $response, $args)
    {
        $idCliente=UserController::ObtenerIdToken($request->getHeaderLine('token'));

        $cliente=User::find($idCliente);
        
        if(isset($cliente))
        {
            $turno = new Turno();

            $datos = $request->getParsedBody();

            $turno->tipo=$datos['tipo'];
            $turno->precio=$datos['fecha'];
            $turno->idCliente=$idCliente;
            $turno->save();

            $response->getBody()->write(json_encode("Se ha agregado el turno"));
        }
        else
        {
            $response->getBody()->write(json_encode("No se ha encontrado un cliente con ese id"));
        }
        
        return $response;
    }

    public function getAll($request, $response, $args)
    {
        $datos=Turno:: join('users', 'users.id', '=', 'turnos.idCliente')
        ->join('mascotas', 'mascotas.tipo', '=', 'users.tipo')
        ->select('users.nombre as Nombre','users.tipo as Tipo', 'turnos.fecha as Fecha', 'mascotas.precio as Precio')
        // ->where('materias.id',$idMateria)
        ->get();
        
        $response->getBody()->write(json_encode($datos));

        return $response;
    }
    
    public function getTurnos($request, $response, $args)
    {
        $id = $args['idTurno'];
        
        $turno = Turno::find($id);
        if(!isset($materia))
        {
            $response->getBody()->write(json_encode("Turno no encontrado"));
        }
        else
        {   
            $datos=Turno:: join('users', 'users.id', '=', 'turnos.idCliente')
            ->join('mascotas', 'mascotas.tipo', '=', 'users.tipo')
            ->select('users.nombre as Nombre','users.tipo as Tipo', 'turnos.fecha as Fecha', 'mascotas.precio as Precio')
            ->where('materias.id',$id)
            ->get();

            $response->getBody()->write(json_encode($datos));

        }

        return $response;
    }
    public function getFactura($request, $response, $args)
    {
        $idCliente=UserController::ObtenerIdToken($request->getHeaderLine('token'));
        $cliente=User::find($idCliente);
        
        if(!isset($cliente))
        {
            $response->getBody()->write(json_encode("Cliente no encontrado"));
        }
        else
        {   
            $datos=Turno:: join('users', 'users.id', '=', 'turnos.idCliente')
            ->join('mascotas', 'mascotas.tipo', '=', 'users.tipo')
            ->select('users.nombre as Nombre', 'mascotas.precio as Precio')
            ->where('users.id',$idCliente)
            ->get();

            $response->getBody()->write(json_encode($datos));

        }

        return $response;
    }
}
