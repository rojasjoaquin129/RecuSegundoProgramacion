<?php
namespace App\Controllers;

use \Firebase\JWT\JWT;

use App\Controllers\UserController;
use App\Models\Mascota;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MascotaController
{
    
    public function addMascota($request,$response, $args)
    {
        $mascota = new Mascota();

        $datos = $request->getParsedBody();

        $mascota->id=$datos['id'];
        $mascota->tipo=$datos['tipo'];
        $mascota->precio=$datos['precio'];

        if($mascota->tipo != "huron" || $mascota->tipo || "perro" || $mascota->tipo != "gato")
        {
            $response->getBody()->write(json_encode("El tipo de mascota es invalido"));
        }
        else
        {
            $mascota->save();
            $response->getBody()->write(json_encode("Mascota guardada correctamente"));
        }

        return $response;
    
    }

    public function GeneradorId()
    {
        
    }

    public function getAll($request, $response, $args)
    {
        $token=$request->getHeaderLine('token');
        if(!isset($token) || $token == "")
        {
            $response->getBody()->write(json_encode("Debe estar logueado para realizar esa accion"));
        }
        else
        {
            $mascotas = Mascota::get();
        
            $response->getBody()->write(json_encode($mascotas));
        }
        
        return $response;
    }
}