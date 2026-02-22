<?php
require  '../../vendor/autoload.php';
require  '../config/conexion.php';
require '../routes/seguridad.php';
require '../routes/configuracion.php';
require '../routes/alumnos.php';
require '../routes/graficos.php';
require '../routes/historico.php';
require '../routes/backup.php';


use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\JsonFormatter;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::route('/', function () {
    echo 'Colegio Hugo Chavez Frias';
});



# Log de Errores

Flight::register('log', Monolog\Logger::class, ['Datos'], function (Monolog\Logger $log) { //composer require monolog/monolog

    $dateFormat = "j n Y, g:i a";
    $output = "%datetime% > %channel%.%level_name%: %message%\n";
    $formatter = new LineFormatter($output, $dateFormat);
    $stream = new StreamHandler('../log/errores.log', Level::Error);
    $stream->setFormatter($formatter);
    $log->pushHandler($stream);
    $log->reset();
});

# Graficos
$datos = new Graficos();
Flight::route('POST /graficos', array($datos, 'graficos'));


#Seguridad
$seguridad = new Seguridad();
Flight::route('POST /auth', array($seguridad, 'autenticacionFunc'));
Flight::route('POST /usuarios', array($seguridad, 'usuariosCrud'));

#cOnfiguracion
$configuracion = new Configuracion();
Flight::route('POST /secciones', array($configuracion, 'secciones'));
Flight::route('POST /profesores', array($configuracion, 'profesores'));
Flight::route('POST /materias', array($configuracion, 'materias'));
Flight::route('POST /personal', array($configuracion, 'personal'));
Flight::route('POST /ubicacion', array($configuracion, 'ubicaciones'));
Flight::route('POST /horarios', array($configuracion, 'horarios'));
Flight::route('POST /periodos', array($configuracion, 'periodos'));

#Alumnos
$alumnos = new Alumnos();
Flight::route('POST /alumnos', array($alumnos, 'alumnosCrud'));

#Historico
$historico = new Historico();
Flight::route('POST /historico', array($historico, 'historicoFunc'));

# Respaldo
$backup = new Respaldos();
Flight::route('POST /backup', array($backup, 'respaldo'));


# Habilitamos los errores
Flight::set('flight.log_errors', true);
Flight::map('error', function (Throwable $error) {
    // Handle error
    // echo $error->getTraceAsString();
    //  Flight::json(['Error' => $error->getMessage(), 'Trace ' => $error->getTraceAsString(), 'Codigo: ' => $error->getCode()]);
    //Flight::json(['Error' => $error->getMessage()]);
    Flight::log()->error($error->getMessage());
});

Flight::map('notFound', function () {
    Flight::json(['Mensaje ' => 'PAGINA NO ENCONTRADA', 'Error ' => '404']);
});

Flight::start();
