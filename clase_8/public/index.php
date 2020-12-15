<?php

use App\Controllers\InscripcionController;
use App\Controllers\MascotaController;
use App\Controllers\MateriasController;
use App\Controllers\NotasController;
use App\Controllers\TurnoController;
use \Firebase\JWT\JWT;
use Clases\Usuario;
use App\Middlewares\UserMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\JsonMiddleware;
use App\Controllers\UserController;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\AlumnoMiddleware;
use App\Middlewares\AuthAdminMiddleware;
use App\Middlewares\AuthClienteMiddleWare;
use App\Middlewares\tipoMiddleware;
use App\Middlewares\StringMiddleware;
use App\Middlewares\ClaveMiddleware;
use App\Middlewares\EmailMiddleware;
use App\Middlewares\PandAMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Config\Database;

require __DIR__ . '/../vendor/autoload.php';
$app = AppFactory::create();
$app->setBasePath("/PHP/clase_8/public");
new Database();

$app->group('/users',function(RouteCollectorProxy $group){
    $group->post('[/]', UserController::class.":add")-> add(new tipoMiddleware)
    ->add(new ClaveMiddleware)->add(new EmailMiddleware)->add(new StringMiddleware);
     
});
$app->post('/users', UserController::class.':add');//Punto 1 agregar comprobacion de tipo
$app->post('/login', UserController::class.':login');//Punto 2
$app->post('/mascota', MascotaController::class.':addMascota')->add(new AuthAdminMiddleware);//Punto 3 agregar generador de Id

$app->group('/turno', function (RouteCollectorProxy $group) {
    
    $group->post('[/]',TurnoController::class.":addTurno")->add(new AuthClienteMiddleWare);//Punto 4
    
    $group->get('[/]',TurnoController::class.":getAll")->add(new AuthAdminMiddleware);//Punto 5
    
    $group->get('/{id}',TurnoController::class.":getTurnos");//Punto 6
    
});

$app->get('/factura', TurnoController::class.':getFactura')->add(new AuthAdminMiddleware);//Punto 7

$app->add(new JsonMiddleware);




$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->run();





/*
//punto 1
$app->group('/users',function(RouteCollectorProxy $group){
    $group->post('[/]', UserController::class.":add")-> add(new tipoMiddleware)
    ->add(new ClaveMiddleware)->add(new EmailMiddleware)->add(new StringMiddleware);
     
});

//punto 2
$app->group('/login',function(RouteCollectorProxy $group){
    $group->post('[/]', UserController::class.":login");

});

//punto 3
$app->group('/materia',function(RouteCollectorProxy $group){
    $group->post('[/]', MateriasController::class.":add")->add(new AdminMiddleware);
    $group->get('[/]', MateriasController::class.":getAll");//punto 7
})->add(new AuthMiddleware);
//punto 4
$app->group('/inscripcion',function(RouteCollectorProxy $group){
    $group->post('/{idMateria}', InscripcionController::class.":add")->add(new AlumnoMiddleware);//punto 4
    $group->get('/{idMateria}', InscripcionController::class.":getAll")->add(new PandAMiddleware);//punto 6
})->add(new AuthMiddleware);
$app->group('/notas',function(RouteCollectorProxy $group){
    $group->put('/{idMateria}',NotasController::class.":update")->add(new UserMiddleware);
})->add(new AuthMiddleware);


$app->add(new JsonMiddleware);

$app->run();*/