<?php
$dotenv = Dotenv\Dotenv::createImmutable('../../');
$dotenv->load();

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Seguridad
{
  public static  function autenticacionFunc()
  {
    $opcion = Flight::request()->data->opcion;
    $secret_key = $_ENV['JWT_SECRET_KEY'];
    switch ($opcion) {
      case 1:
        $usuario = secure_data(Flight::request()->data->usuario);
        $password = secure_data(Flight::request()->data->password);

        $databaseService = new DatabaseService();
        $db = $databaseService->getConnection();

        $estatus = $db->runQuery('SELECT * FROM usuarios where usuario = ? and estatus = "ACTIVO"', [$usuario]);
        if ($estatus->rowCount() == 0) {
          Flight::json(array(
            'mensaje' => 'Usuario Inactivo o no Encontrado'
          ));
          return;
        }

        $users = $db->runQuery('SELECT * FROM usuarios where usuario = ? ', [$usuario]);
        $filas_afectadas = $users->rowCount();

        if ($filas_afectadas > 0) {
          while ($row = $users->fetch()) {
            // o echo $usuario->nombre;
            $passwordhash = $row['password'];
            if (password_verify($password, $passwordhash)) {

              $issuer_claim = "COLEGIO"; //NOMBRE DEL SERVIDOR
              $issuedat_claim = time(); //EMITIDO
              $notbefore_claim = $issuedat_claim; //NO ANTES DE
              $expire_claim = $issuedat_claim + 60 * 1440; //TIEMPO DE EXPIRACION

              $payload = [
                "iss" => $issuer_claim,
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => [
                  "id" => $row['id'],
                  "usuario" => $row['usuario'],
                  "nombre" => $row['nombre'],
                  "rol" => $row['rol'],
                  "foto" => $row['foto'],
                ],

              ];

              $jwt = JWT::encode($payload, $secret_key, 'HS256');
              Flight::json(array(
                'mensaje' => 'ok',
                'token' =>  $jwt
              ));
            } else {

              Flight::json(array(
                'mensaje' => 'Contraseña Incorrecta'
              ));
            }
          }
        } else {
          Flight::json(array(
            'mensaje' => 'Usuario Incorrecto o no Encontrado'
          ));
        }

        break;

      case 2:
        $password = Flight::request()->data->password;
        $usuario = Flight::request()->data->id;
        // echo $password . ' ' . $usuario;

        $databaseService = new DatabaseService();
        $db = $databaseService->getConnection();
        $users = $db->runQuery('SELECT * FROM usuarios where id = ? ', [$usuario]);
        $filas_afectadas = $users->rowCount();

        if ($filas_afectadas > 0) {

          while ($row = $users->fetch()) {
            $passwordhash = $row['password'];

            if (password_verify($password, $passwordhash)) {
              Flight::json(array(
                'mensaje' => 'Contraseña Correcta'
              ));
            } else {
              echo json_encode(array(
                "mensaje" => "Contraseña Incorrecta"
              ));
            }
          }
        }
        break;

      case 3:
        $databaseService = new DatabaseService();
        $db = $databaseService->getConnection();
        $password = Flight::request()->data->password;
        $id = Flight::request()->data->id;
        $password_hash = password_hash(secure_data($password), PASSWORD_BCRYPT);

        $db->runQuery("UPDATE usuarios SET password = ? WHERE id = ?", [$password_hash, $id]);
        Flight::json(array(
          'mensaje' => 'ok'
        ));
        break;
    }
  }

  public static  function usuariosCrud()
  {
    $databaseService = new DatabaseService();
    $db = $databaseService->getConnection();
    $opcion = Flight::request()->data->opcion;

    switch ($opcion) {
      case 1:

        $respuesta = $db->fetchAll('SELECT * FROM usuarios');
        Flight::json($respuesta, 200);

        break;

      case 2:
        $cedula = Flight::request()->data->cedula;
        $usuario = secure_data(Flight::request()->data->usuario);
        $password = trim(Flight::request()->data->password);
        $nombre = secure_data(Flight::request()->data->nombre);
        $password_hash = password_hash(secure_data($password), PASSWORD_BCRYPT);
        $rol = secure_data(Flight::request()->data->rol);
        $foto = Flight::request()->files->foto;
        $nombreimgExt = null;
        if ($foto) {
          $foto_nombre =  $foto['name'];
          $imgExt   = strtolower(pathinfo($foto_nombre, PATHINFO_EXTENSION));
          $nombreimgExt           = $usuario . '.' . $imgExt;
          $foto_ruta = '../public/fotos/' .   $nombreimgExt;
          move_uploaded_file($foto['tmp_name'], $foto_ruta);
        } else {
          $nombreimgExt = null;
        }
        try {
          $respuesta = $db->runQuery('INSERT INTO usuarios (cedula,nombre ,usuario, password, rol, foto, estatus) VALUES (?,?,?,?,?,?,?)', [$cedula, $nombre, $usuario, $password_hash, $rol, $nombreimgExt, 'ACTIVO']);
          Flight::json(array(
            'mensaje' => 'ok'
          ), 200);
        } catch (Exception $e) {
          echo json_encode(array(
            "mensaje" => "Access Denegado",
            "error" => $e->getMessage()
          ));
        }

        break;
      case 3:
        $upload_location = '../public/fotos/';
        $id = Flight::request()->data->id;
        $usuario = Flight::request()->data->usuario;
        foreach (glob($upload_location . $usuario . '*') as $fileName) {
          unlink($fileName);
        }
        try {
          Flight::db()->runQuery("DELETE FROM usuarios WHERE id = ?", [$id]);

          Flight::json(array(
            "mensaje" => "Borrado"
          ), 200);
        } catch (Throwable $e) {
          Flight::json(array(
            "mensaje" => "Access denegado",
            "error" => Flight::error($e)
          ), 401);
        }
        break;

      case 4:
        $id = Flight::request()->data->id;
        $cedula = Flight::request()->data->cedula;
        $usuario = secure_data(Flight::request()->data->usuario);
        $nombre = secure_data(Flight::request()->data->nombre);
        $rol = secure_data(Flight::request()->data->rol);
        $foto = Flight::request()->files->foto;
        $estatus = Flight::request()->data->estatus;
        $nombreimgExt = null;
        $upload_location = '../public/fotos/';

        try {
          if ($foto) {
            foreach (glob($upload_location . $usuario . '*') as $fileName) {
              unlink($fileName);
            }
            $foto_nombre =  $foto['name'];
            $imgExt   = strtolower(pathinfo($foto_nombre, PATHINFO_EXTENSION));
            $nombreimgExt           = $usuario . '_' . time() . '.'  . $imgExt;
            $foto_ruta = $upload_location .   $nombreimgExt;
            move_uploaded_file($foto['tmp_name'], $foto_ruta);
            $db->runQuery("UPDATE usuarios SET cedula=?, usuario = ?, nombre = ?, rol = ?, foto = ? , estatus = ? WHERE id = ?", [$cedula, $usuario, $nombre, $rol, $nombreimgExt, $estatus, $id]);
          } else {
            $db->runQuery("UPDATE usuarios SET cedula=?,  usuario = ?, nombre = ?, rol = ?, estatus = ? WHERE id = ?", [$cedula, $usuario, $nombre, $rol, $estatus, $id]);
          }
          Flight::json(array(
            'mensaje' => 'ok'
          ), 200);
        } catch (Exception $e) {
          echo json_encode(array(
            "mensaje" => "Access Denegado",
            "error" => $e->getMessage()
          ));
        }
        break;
      case 5:
        $id = Flight::request()->data->id;
        $password = '123';
        try {
          $password_hash = password_hash($password, PASSWORD_BCRYPT);
          $db->runQuery("UPDATE usuarios set password=  ? WHERE id = ?", [$password_hash, $id]);

          Flight::json(array(
            'mensaje' => 'ok'
          ), 200);
        } catch (Exception $e) {
          echo json_encode(array(
            "mensaje" => "Access Denegado",
            "error" => $e->getMessage()
          ));
        }
        break;
    }
  }
}

function secure_data($data)
{
  trim($data); //quitar espacio al principio y al final
  strip_tags($data); //Elimina etiquetas HTML y PHP de una cadena
  stripslashes($data); //Quita las comillas de una cadena entre comillas
  htmlspecialchars($data); //Convertir caracteres especiales en entidades HTML
  return $data;
}
