<?php
require '../../vendor/autoload.php';
class Configuracion
{

  public static  function secciones()
  {
    $opcion = Flight::request()->data->opcion;
    $databaseService = new DatabaseService();
    $db = $databaseService->getConnection();
    $opcion = Flight::request()->data->opcion;
    $grado = Flight::request()->data->grado;

    switch ($opcion) {
      case 1:
        $respuesta = $db->fetchAll('SELECT 
                secciones.id,
                secciones.grado,
                secciones.seccion,
                secciones.descripcion
                FROM
                secciones
                WHERE
                secciones.grado = ?', [$grado]);
        Flight::json($respuesta, 200);

        break;
      case 2:
        $respuesta = $db->fetchAll('SELECT 
                secciones.id,
                secciones.grado,
                secciones.seccion,
                secciones.descripcion
               
                FROM
                secciones');
        Flight::json($respuesta, 200);
        break;
      case 3:
        $grado = Flight::request()->data->grado;
        $seccion = Flight::request()->data->seccion;
        $descripcion = Flight::request()->data->descripcion;

        try {
          $respuesta = $db->runQuery('INSERT INTO secciones (grado ,seccion, descripcion) VALUES (?,?,?)', [$grado, $seccion, $descripcion]);
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
      case 4:
        $id = Flight::request()->data->id;
        $grado = Flight::request()->data->grado;
        $seccion = Flight::request()->data->seccion;
        $descripcion = Flight::request()->data->descripcion;
        try {
          $respuesta = $db->runQuery('UPDATE secciones SET grado = ?, seccion = ?, descripcion = ? WHERE id = ?', [$grado, $seccion, $descripcion, $id]);
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
        try {
          $respuesta = $db->runQuery('DELETE FROM secciones WHERE id = ?', [$id]);
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

      case 6:



        break;
      default:
        Flight::json(array(
          'mensaje' => 'Opción no válida'
        ));
    }
  }
  public static  function profesores()
  {

    $opcion = Flight::request()->data->opcion;
    $databaseService = new DatabaseService();
    $db = $databaseService->getConnection();
    $opcion = Flight::request()->data->opcion;


    switch ($opcion) {
      case 1:
        $respuesta = $db->fetchAll("SELECT 
                `personal`.`id`,
                `personal`.`foto`,
                `personal`.`cedula`,
                `personal`.`nombre`,
                `personal`.`apellido`

                FROM
                `personal`
                WHERE
                `personal`.`tipo_personal` = 'DOCENTE'");
        Flight::json($respuesta, 200);

        break;
      case 2:
        $id = Flight::request()->data->idprof;

        $respuesta = $db->fetchAll("SELECT 
                `asignaturas`.`id`,
                `secciones`.`descripcion` ,
                `materias`.`materia`,
                `asignaturas`.`id_seccion`
                FROM
                `asignaturas`
                INNER JOIN `materias` ON (`asignaturas`.`id_materia` = `materias`.`id`)
                INNER JOIN `secciones` ON (`asignaturas`.`id_seccion` = `secciones`.`id`)
                INNER JOIN `personal` ON (`asignaturas`.`id_prof` = `personal`.`id`)
                WHERE
                `asignaturas`.`id_prof` =  ?", [$id]);
        Flight::json($respuesta, 200);

        break;
      case 3:
        $idprof = Flight::request()->data->idprof;
        $idseccion = Flight::request()->data->idseccion;
        $idmateria = Flight::request()->data->idmateria;
        try {

          $asig = $db->runQuery('SELECT * FROM asignaturas
                    WHERE
                    `asignaturas`.`id_seccion` = ? AND
                    `asignaturas`.`id_materia` = ?', [$idseccion, $idmateria]);
          $filas_afectadas = $asig->rowCount();
          if ($filas_afectadas > 0) {
            Flight::json(array(
              'mensaje' => 'La asignatura seleccionada ya esta asignada a esa seccion. Por favor rectifique',
              "error" => ''
            ));
          } else {

            $respuesta = $db->runQuery('INSERT INTO asignaturas (id_prof,id_seccion, id_materia) VALUES (?,?,?)', [$idprof, $idseccion, $idmateria]);
            Flight::json(array(
              'mensaje' => 'ok'
            ), 200);
          }
        } catch (Exception $e) {
          echo json_encode(array(
            "mensaje" => "Access Denegado",
            "error" => $e->getMessage()
          ));
        }
        break;
      case 4:
        $id = Flight::request()->data->id;
        try {
          $respuesta = $db->runQuery('DELETE FROM asignaturas WHERE id = ?', [$id]);
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
        $idprof = Flight::request()->data->idprof;
        $respuesta = $db->fetchAll("SELECT 
                `asignaturas`.`id`,
                CONCAT(`secciones`.`descripcion`,' - ',
                `materias`.`materia`) as asignatura,
                `asignaturas`.`id_seccion`
                FROM
                `secciones`
                INNER JOIN `asignaturas` ON (`secciones`.`id` = `asignaturas`.`id_seccion`)
                INNER JOIN `materias` ON (`asignaturas`.`id_materia` = `materias`.`id`)
                WHERE
                `asignaturas`.`id_prof`= ?", [$idprof]);
        Flight::json($respuesta, 200);

        break;
      case 6:
        $idprof = Flight::request()->data->idprof;
        $respuesta = $db->fetchAll("SELECT * FROM  `horariosprof` where
                id_prof = ?", [$idprof]);
        Flight::json($respuesta, 200);
        break;
      case 7:
        $idprof = Flight::request()->data->idprof;
        $hora = Flight::request()->data->hora;
        $salida = Flight::request()->data->salida;
        $dia = Flight::request()->data->dia;
        $idseccion = Flight::request()->data->idseccion;
        $asignatura = Flight::request()->data->asignatura;

        /* $count = Flight::db()->fetchField("SELECT COUNT(*)
        FROM `horariosprof`
        WHERE hora >= '07:00:00' AND
              salida <= ? and
              id_seccion = ? and
              dia = ?", [$salida, $idseccion, $dia]);


        if ($count > 0) {
          Flight::json(array(
            'mensaje' => 'El horario seleccionado ya existe. Por favor rectifique',
            "error" => ''
          ));
          return;
        }
*/

        try {
          $respuesta = $db->runQuery('INSERT INTO horariosprof (id_prof, id_seccion ,hora,salida, dia, asignatura) VALUES (?,?,?,?,?,?)', [$idprof, $idseccion, $hora, $salida, $dia, $asignatura]);
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
      case 8:
        $id = Flight::request()->data->id;
        try {
          Flight::db()->runQuery("DELETE FROM horariosprof WHERE id = ?", [$id]);
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
      case 9:
        $id = Flight::request()->data->id;
        $respuesta = Flight::db()->fetchRow("SELECT `personal`.`id`,
                       `personal`.`cedula`,
                        `personal`.`nombre`,
                        `personal`.`apellido`
                    FROM `usuarios`
                        INNER JOIN `personal` ON (`usuarios`.`cedula` = `personal`.`cedula`)
                    WHERE `personal`.`tipo_personal` = 'DOCENTE' AND
                        `usuarios`.`id` = ?", [$id]);
        Flight::json($respuesta, 200);
        break;
      case 10:
        // $idprof = Flight::request()->data->idprof;
        $idseccion = Flight::request()->data->idseccion;
        $respuesta = $db->fetchAll("SELECT 
          `alumnos`.`id`,
          `alumnos`.`cedulae`,
          `alumnos`.`cedula`,
          `alumnos`.`nombres`,
          `alumnos`.`apellidos`,
          `alumnos`.`sexo`,
          `alumnos`.`id_seccion`
        FROM
          `alumnos`
        WHERE
          `alumnos`.`id_seccion` = ?", [$idseccion]);
        Flight::json($respuesta, 200);
        break;
      case 11:

        $idseccion = Flight::request()->data->idseccion;
        $respuesta = $db->fetchAll("SELECT `notas`.`id`,
       `notas`.`id_prof`,
       `notas`.`id_asignatura`,
       `notas`.`lapso1`,
       `notas`.`lapso2`,
       `notas`.`lapso3`,
       `notas`.`observaciones`,
       `notas`.`id_alumno`,
       `notas`.`revision`,
       `notas`.`art112`
FROM `notas`
     INNER JOIN `asignaturas` ON (`notas`.`id_asignatura` = `asignaturas`.`id`)
WHERE `asignaturas`.`id` = ? ", [$idseccion]);
        Flight::json($respuesta, 200);
        break;

      case 12:
        $id_seccion = Flight::request()->data->idsecc;
        $id_prof = Flight::request()->data->idprof;
        $id_asignatura = Flight::request()->data->idasig;
        $id_alumno = Flight::request()->data->idalumno;
        $lapso1 = Flight::request()->data->lapso1;
        $lapso2 = Flight::request()->data->lapso2;
        $lapso3 = Flight::request()->data->lapso3;
        $observaciones = Flight::request()->data->observaciones;
        $revision = Flight::request()->data->revision;
        $art112 = Flight::request()->data->art112;
        $id = Flight::request()->data->idnota ? Flight::request()->data->idnota : null;

        $nota = $db->runQuery('SELECT * FROM notas where id = ? ', [$id]);
        $filas_afectadas = $nota->rowCount();

        /* Flight::json(array(
          'mensaje' => 'ok',
          'id' => $id
        ));*/
        if ($filas_afectadas > 0) {

          try {
            $db->runQuery('UPDATE notas SET id_seccion=?, id_prof=?, id_asignatura=?, id_alumno=?,lapso1 = ?, lapso2 = ?, lapso3 = ?, revision = ?, art112 = ?, observaciones = ? WHERE id = ?', [$id_seccion, $id_prof, $id_asignatura, $id_alumno, $lapso1, $lapso2, $lapso3, $revision, $art112, $observaciones,  $id]);
            Flight::json(array(
              'mensaje' => 'ok'
            ), 200);
          } catch (Exception $e) {
            echo json_encode(array(
              "mensaje" => "Access Denegado",
              "error" => $e->getMessage()
            ));
          }
        } else {
          try {
            $db->runQuery('INSERT INTO notas  
            (id_seccion, id_prof, id_asignatura, id_alumno, lapso1, lapso2, lapso3, revision, art112, observaciones)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$id_seccion, $id_prof, $id_asignatura, $id_alumno, $lapso1, $lapso2, $lapso3, $revision, $art112, $observaciones]);
            Flight::json(array(
              'mensaje' => 'ok'
            ), 200);
          } catch (Exception $e) {
            echo json_encode(array(
              "mensaje" => "Access Denegado",
              "error" => $e->getMessage()
            ));
          }
        }
        break;
      case 13:
        $grado = Flight::request()->data->grado;
        $respuesta = $db->fetchAll("SELECT 
  `asignaturas`.`id`,
  `secciones`.`descripcion`,
  `materias`.`materia`,
  `asignaturas`.`id_seccion`,
  `secciones`.`grado`
FROM
  `asignaturas`
  INNER JOIN `materias` ON (`asignaturas`.`id_materia` = `materias`.`id`)
  INNER JOIN `secciones` ON (`asignaturas`.`id_seccion` = `secciones`.`id`)
  INNER JOIN `personal` ON (`asignaturas`.`id_prof` = `personal`.`id`)
WHERE
   `secciones`.`grado` = ?", [$grado]);
        Flight::json($respuesta);

        break;
      case 14:
        $idseccion = Flight::request()->data->idseccion;
        $sql = "SELECT 
        `a`.`cedula`,
        `a`.`nombres`,
        `a`.`apellidos`,
        `m`.`materia`,
        `n`.`lapso1`,
        `n`.`lapso2`,
        `n`.`lapso3`
        
      FROM
        `alumnos` `a`
        INNER JOIN `notas` `n` ON (`a`.`id_seccion` = `n`.`id_seccion`)
        INNER JOIN `asignaturas` `asig` ON (`n`.`id_asignatura` = `asig`.`id`)
        INNER JOIN `materias` `m` ON (`asig`.`id_materia` = `m`.`id`)
        WHERE
        `a`.`id_seccion` = ?
      ORDER BY
        `a`.`cedula`,
        `m`.`materia`";

        $rows = $db->fetchAll($sql, [$idseccion]);

        $pivoted = [];

        foreach ($rows as $row) {
          $cedula = $row['cedula'];


          $pivoted[$cedula] = [
            'cedula' => $row['cedula'],
            'nombres' => $row['nombres'],
            'apellidos' => $row['apellidos']
          ];

          $materia = $row['materia'];
          $pivoted[$cedula]["{$materia}_L1"] = $row['lapso1'];
          $pivoted[$cedula]["{$materia}_L2"] = $row['lapso2'];
          $pivoted[$cedula]["{$materia}_L3"] = $row['lapso3'];
        }
        Flight::json(array($pivoted));

        break;

      case 15:
        $idseccion = Flight::request()->data->idseccion;
        $idalumno = Flight::request()->data->idalumno;

        $respuesta = $db->fetchAll("SELECT DISTINCT 
  `notas`.`id_seccion`,
  `notas`.`id_alumno`,
  `materias`.`materia`,
  `notas`.`lapso1`,
  `notas`.`lapso2`,
  `notas`.`lapso3`,
  `notas`.`revision`,
  `notas`.`art112`,
  `notas`.`observaciones`,
  CONCAT(`personal`.`cedula`, ' ', `personal`.`nombre`, ' ', `personal`.`apellido`) AS `profesor`,
  SUM(`notas`.`lapso1` + `notas`.`lapso2` + `notas`.`lapso3`) / 3 AS `promedio`
FROM
  `notas`
  INNER JOIN `asignaturas` ON (`notas`.`id_asignatura` = `asignaturas`.`id`)
  INNER JOIN `materias` ON (`asignaturas`.`id_materia` = `materias`.`id`)
  INNER JOIN `personal` ON (`asignaturas`.`id_prof` = `personal`.`id`)
WHERE
  `notas`.`id_seccion` = ? AND `notas`.`id_alumno` = ?
GROUP BY
  `notas`.`id_seccion`,
  `notas`.`id_alumno`,
  `materias`.`materia`,
  `notas`.`lapso1`, 
  `notas`.`lapso2`,
  `notas`.`lapso3`,
  `notas`.`revision`,
  `notas`.`art112`,
  `notas`.`observaciones`", [$idseccion, $idalumno]);
        Flight::json($respuesta);
        break;
      case 16:
        $idseccion = Flight::request()->data->idseccion;
        $respuesta = $db->fetchAll("SELECT DISTINCT 
          `notas`.`id_alumno`,
        AVG(`notas`.`lapso1`) as 'lapso1',
        AVG(`notas`.`lapso2`) as 'lapso2',
        AVG(`notas`.`lapso3`) as 'lapso3',
        AVG(`notas`.`lapso1` + `notas`.`lapso2` + `notas`.`lapso3`)/3 AS `promedio`
          
        FROM
          `notas`
        WHERE
          `notas`.`id_seccion` = ?
        GROUP BY
          `notas`.`id_alumno`", [$idseccion]);
        Flight::json($respuesta);

        break;

      default:
        Flight::json(array(
          'mensaje' => 'Opción no válida'
        ));
    }
  }
  public static  function materias()
  {

    $opcion = Flight::request()->data->opcion;
    $databaseService = new DatabaseService();
    $db = $databaseService->getConnection();
    $opcion = Flight::request()->data->opcion;
    switch ($opcion) {
      case 1:
        $respuesta = $db->fetchAll("SELECT 
            `materias`.`id`,
            `materias`.`codigo`,
            `materias`.`materia`,
            `materias`.`tipos`
            FROM
            `materias`");
        Flight::json($respuesta, 200);

        break;
      case 2:

        $materia = Flight::request()->data->materia;
        $tipos = Flight::request()->data->tipo;
        $codigo = '';
        $codigo = str_pad(getCod(), 4, "0", STR_PAD_LEFT);
        try {
          $respuesta = $db->runQuery('INSERT INTO materias (codigo ,materia, tipos) VALUES (?,?,?)', [$codigo, $materia, $tipos]);
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
        $id = Flight::request()->data->id;
        $materia = Flight::request()->data->materia;
        $tipos = Flight::request()->data->tipo;
        try {
          $respuesta = $db->runQuery("UPDATE materias SET materia = ?, tipos=? WHERE id = ?", [$materia, $tipos, $id]);
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
      case 4:
        $id = Flight::request()->data->id;
        try {
          Flight::db()->runQuery("DELETE FROM materias WHERE id = ?", [$id]);
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
      default:
        Flight::json(array(
          'mensaje' => 'Opción no válida'
        ));
    }
  }
  public static  function personal()
  {

    $opcion = Flight::request()->data->opcion;
    $databaseService = new DatabaseService();
    $db = $databaseService->getConnection();
    $opcion = Flight::request()->data->opcion;
    switch ($opcion) {
      case 1:
        $respuesta = $db->fetchAll("SELECT *
            FROM
            `personal`");
        Flight::json($respuesta, 200);

        break;
      case 2:
        $cedula = Flight::request()->data->cedula;
        $nombre = Flight::request()->data->nombre;
        $apellido = Flight::request()->data->apellido;
        $sexo = Flight::request()->data->sexo;
        $cargo = Flight::request()->data->cargo;
        $tipo_personal = Flight::request()->data->tipo_personal;
        $telefono = Flight::request()->data->telefono;
        $direccion = Flight::request()->data->direccion;
        $correo = Flight::request()->data->correo;
        $foto = Flight::request()->files->foto;
        $nombreimgExt = null;
        if ($foto) {
          $foto_nombre =  $foto['name'];
          $imgExt   = strtolower(pathinfo($foto_nombre, PATHINFO_EXTENSION));
          $nombreimgExt           = $cedula . '_' . time() . '.'  . $imgExt;
          $foto_ruta = '../public/fotos/' .   $nombreimgExt;
          move_uploaded_file($foto['tmp_name'], $foto_ruta);
        } else {
          $nombreimgExt = null;
        }
        try {
          $respuesta = $db->runQuery('INSERT INTO 
                `personal`
                (
                `cedula`,
                `nombre`,
                `apellido`,
                `sexo`,
                `cargo`,
                `tipo_personal`,
                `telefono`,
                `direccion`,
                `correo`,
                `foto`) 
                VALUE (?,?,?,?,?,?,?,?,?,?);', [$cedula, $nombre, $apellido, $sexo, $cargo, $tipo_personal, $telefono, $direccion, $correo, $nombreimgExt]);
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
        $id = Flight::request()->data->id;
        $cedula = Flight::request()->data->cedula;
        $nombre = Flight::request()->data->nombre;
        $apellido = Flight::request()->data->apellido;
        $sexo = Flight::request()->data->sexo;
        $cargo = Flight::request()->data->cargo;
        $tipo_personal = Flight::request()->data->tipo_personal;
        $telefono = Flight::request()->data->telefono;
        $direccion = Flight::request()->data->direccion;
        $correo = Flight::request()->data->correo;
        $foto = Flight::request()->files->foto;
        $nombreimgExt = null;
        $upload_location = '../public/fotos/';
        try {

          if ($foto) {
            foreach (glob($upload_location . $cedula . '*') as $fileName) {
              unlink($fileName);
            }
            $foto_nombre =  $foto['name'];
            $imgExt   = strtolower(pathinfo($foto_nombre, PATHINFO_EXTENSION));
            $nombreimgExt           = $cedula . '_' . time() . '.'  . $imgExt;
            $foto_ruta = $upload_location .   $nombreimgExt;
            move_uploaded_file($foto['tmp_name'], $foto_ruta);
            $db->runQuery("UPDATE `personal` SET 
                        `cedula` = ?,
                    `nombre` = ?,
                    `apellido` = ?,
                    `sexo` = ?,
                    `cargo` = ?,
                    `tipo_personal` = ?,
                    `telefono` = ?,
                    `direccion` = ?,
                    `correo` = ?,
                    `foto` = ?  WHERE id = ?", [$cedula, $nombre, $apellido, $sexo, $cargo, $tipo_personal, $telefono, $direccion, $correo, $nombreimgExt, $id]);
          } else {
            $db->runQuery("UPDATE `personal` SET 
                        `cedula` = ?,
                    `nombre` = ?,
                    `apellido` = ?,
                    `sexo` = ?,
                    `cargo` = ?,
                    `tipo_personal` = ?,
                    `telefono` = ?,
                    `direccion` = ?,
                    `correo` = ?  WHERE id = ?", [$cedula, $nombre, $apellido, $sexo, $cargo, $tipo_personal, $telefono, $direccion, $correo, $id]);
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
      case 4:
        $upload_location = '../public/fotos/';
        $id = Flight::request()->data->id;
        $cedula = Flight::request()->data->cedula;
        foreach (glob($upload_location . $cedula . '*') as $fileName) {
          unlink($fileName);
        }
        try {

          Flight::db()->runQuery("DELETE FROM personal WHERE id = ?", [$id]);
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
      default:
        Flight::json(array(
          'mensaje' => 'Opción no válida'
        ));
    }
  }
  public static  function horarios()
  {

    $opcion = Flight::request()->data->opcion;
    $databaseService = new DatabaseService();
    $db = $databaseService->getConnection();
    $opcion = Flight::request()->data->opcion;
    switch ($opcion) {
      case 1:
        $respuesta = $db->fetchAll("SELECT *
            FROM
            `horasprof`");

        Flight::json($respuesta, 200);

        break;
      case 2:
        $id_prof = Flight::request()->data->id_prof;
        $nombre = Flight::request()->data->nombre;

        try {
          $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            //'format' => 'Legal',
            'format' => 'A4',
            'orientation' => 'L'
          ]);

          $mpdf->useSubstitutions = false;
          $mpdf->setAutoTopMargin = 'stretch';
          $mpdf->SetDisplayMode('real');
          $mpdf->SetAuthor('Jose Meneses');

          $cabecera = '
         <table width="100%" style="font-size:8pt;" ><tr>
         <td width="33%" align="center"><img src="../imagenes/111.png" width="100" height="100" alt="Logo"></td>
         </tr></table>
         <br/>
         <table width="100%" style="font-weight: bold;font-size:12pt;" ><tr>
         <td width="33%" align="center" > Horario Profesor: ' . $nombre . '  </td>
         </tr></table>
         <br/>
         <table width="100%" style="font-weight: bold;font-size:10pt; " ><tr>
         <td width="33%" align="center" >COMPLEJO EDUCATIVO "HUGO RAFAEL CHAVEZ FRIAS" </td>
         </tr></table>';
          $mpdf->SetHTMLHeader($cabecera);

          $sql = 'SELECT 
        `horariosprof`.`hora`,
        `horariosprof`.`salida`,
        `horariosprof`.`dia`,
        `horariosprof`.`asignatura`
      FROM
        `horariosprof`
        WHERE
        `horariosprof`.`id_prof` = ?
        ORDER BY
        `horariosprof`.`dia` asc';

          $horario = $db->runQuery($sql, [$id_prof]);

          $mpdf->WriteHTML('');
          $mpdf->ln(2);
          $mpdf->SetFont('Arial', 'B', '11');
          $stylesheet = file_get_contents('../css/tablas.css'); // la ruta a tu css
          $mpdf->WriteHTML($stylesheet, 1);
          $htmlTabla = "";
          $htmlTabla .= ' <table align="center"  class="styled-table table-striped table-bordered table-hover" style="font-size: 10px;">';
          $htmlTabla .= '<tr>';
          $htmlTabla .=  '<th >ENTRADA</th>';
          $htmlTabla .=  '<th >SALIDA</th>';
          $htmlTabla .=  '<th >DIA</th>';
          $htmlTabla .=  '<th >ASIGNATURA</th>';
          $htmlTabla .=  '</tr>';
          while ($row = $horario->fetch()) {

            $htmlTabla .=  '<tbody>';
            $htmlTabla .=  '<tr>';
            $htmlTabla .=   '<td style="padding: 1px;" >' . formatearhora($row['hora']) . '</td>';
            $htmlTabla .=   '<td style="padding: 1px;" >' .  formatearhora($row['salida']) . '</td>';
            $htmlTabla .=   '<td style="padding: 1px;" align="center">' .  $row['dia'] . '</td>';
            $htmlTabla .=   '<td style="padding: 1px;" align="left" >' .  $row['asignatura'] . '</td>';
            $htmlTabla .=  '</tr>';
            $htmlTabla .=  '</tbody>';
          }

          $htmlTabla .=  '</tbody>';
          $htmlTabla .=  '</table>';
          $mpdf->WriteHTML($htmlTabla);


          $mpdf->Output('filename.pdf', \Mpdf\Output\Destination::INLINE);
        } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
          // Process the exception, log, print etc.
          echo $e->getMessage();
        }
        break;
      case 3:
        $id_secc = Flight::request()->data->id_secc;
        $turno = Flight::request()->data->id_turno;
        $sql = '';
        try {
          $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            //'format' => 'Legal',
            'format' => 'A4',
            'orientation' => 'L'
          ]);

          $mpdf->useSubstitutions = false;
          $mpdf->setAutoTopMargin = 'stretch';
          $mpdf->SetDisplayMode('real');
          $mpdf->SetAuthor('Jose Meneses');

          switch ($turno) {
            case 1:
              $cabecera = '
         <table width="100%" style="font-size:8pt;" ><tr>
         <td width="33%" align="center"><img src="../imagenes/111.png" width="100" height="100" alt="Logo"></td>
         </tr></table>
         <br/>
         <table width="100%" style="font-weight: bold;font-size:12pt;" ><tr>
         <td width="33%" align="center" > Horarios </td>
         </tr></table>
         <br/>
            <table width="100%" style="font-weight: bold;font-size:12pt;" ><tr>
         <td width="33%" align="center" > Turno de la Mañana </td>
         </tr></table>
         <table width="100%" style="font-weight: bold;font-size:10pt; " ><tr>
         <td width="33%" align="center" >COMPLEJO EDUCATIVO "HUGO RAFAEL CHAVEZ FRIAS" </td>
         </tr></table>';
              $mpdf->SetHTMLHeader($cabecera);

              $sql = "SELECT 
          hora,
          salida,
          MAX(CASE WHEN dia = 'LUNES' THEN asignatura ELSE '' END) AS lunes,
          MAX(CASE WHEN dia = 'MARTES' THEN asignatura ELSE '' END) AS martes,
          MAX(CASE WHEN dia = 'MIERCOLES' THEN asignatura ELSE '' END) AS miercoles,
          MAX(CASE WHEN dia = 'JUEVES' THEN asignatura ELSE '' END) AS jueves,
          MAX(CASE WHEN dia = 'VIERNES' THEN asignatura ELSE '' END) AS viernes
        FROM horariosprof
        WHERE
        horariosprof.`id_seccion` = ? AND 
        `hora` BETWEEN '00:00:00' AND '11:59:00'
        GROUP BY hora, salida
        ORDER BY hora";

              $sql2 = "SELECT DISTINCT
        `horariosprof`.`asignatura`,
        `personal`.`cedula`,
        `personal`.`nombre`,
        `personal`.`apellido`
      FROM
        `personal`
        INNER JOIN `horariosprof` ON (`personal`.`id` = `horariosprof`.`id_prof`)
      WHERE
        `horariosprof`.`id_seccion` = ? AND 
              `hora` BETWEEN '00:00:00' AND '11:59:00'
      GROUP BY
        `horariosprof`.`asignatura`,
        `personal`.`cedula`,
        `personal`.`nombre`,
        `personal`.`apellido`";

              break;
            case 2:
              $cabecera = '
         <table width="100%" style="font-size:8pt;" ><tr>
         <td width="33%" align="center"><img src="../imagenes/111.png" width="100" height="100" alt="Logo"></td>
         </tr></table>
         <br/>
         <table width="100%" style="font-weight: bold;font-size:12pt;" ><tr>
         <td width="33%" align="center" > Horarios </td>
         </tr></table>
          <br/>
         <table width="100%" style="font-weight: bold;font-size:12pt;" ><tr>
         <td width="33%" align="center" > Turno de la Tarde</td>
         </tr></table>
         <br/>
         <table width="100%" style="font-weight: bold;font-size:10pt; " ><tr>
         <td width="33%" align="center" >COMPLEJO EDUCATIVO "HUGO RAFAEL CHAVEZ FRIAS" </td>
         </tr></table>';
              $mpdf->SetHTMLHeader($cabecera);

              $sql = "SELECT 
          hora,
          salida,
          MAX(CASE WHEN dia = 'LUNES' THEN asignatura ELSE '' END) AS lunes,
          MAX(CASE WHEN dia = 'MARTES' THEN asignatura ELSE '' END) AS martes,
          MAX(CASE WHEN dia = 'MIERCOLES' THEN asignatura ELSE '' END) AS miercoles,
          MAX(CASE WHEN dia = 'JUEVES' THEN asignatura ELSE '' END) AS jueves,
          MAX(CASE WHEN dia = 'VIERNES' THEN asignatura ELSE '' END) AS viernes
        FROM horariosprof
        WHERE
        horariosprof.`id_seccion` = ? AND 
        `hora` BETWEEN '12:00:00' AND '23:59:00'
        GROUP BY hora, salida
        ORDER BY hora";

              $sql2 = "SELECT DISTINCT
        `horariosprof`.`asignatura`,
        `personal`.`cedula`,
        `personal`.`nombre`,
        `personal`.`apellido`
      FROM
        `personal`
        INNER JOIN `horariosprof` ON (`personal`.`id` = `horariosprof`.`id_prof`)
      WHERE
        `horariosprof`.`id_seccion` = ? AND 
              `hora` BETWEEN '12:00:00' AND '23:59:00'
      GROUP BY
        `horariosprof`.`asignatura`,
        `personal`.`cedula`,
        `personal`.`nombre`,
        `personal`.`apellido`";


              break;
            default:
              # code...
              break;
          }
          $horario = $db->runQuery($sql, [$id_secc]);

          $mpdf->WriteHTML('');
          $mpdf->ln(2);
          $mpdf->SetFont('Arial', 'B', '12');
          $stylesheet = file_get_contents('../css/tablas.css'); // la ruta a tu css
          $mpdf->WriteHTML($stylesheet, 1);
          $htmlTabla = "";
          $htmlTabla .= ' <table align="center" width="100%" class="styled-table table-striped table-bordered table-hover" style="font-size: 16px;">';
          $htmlTabla .= '<tr>';
          $htmlTabla .=  '<th >ENTRADA</th>';
          $htmlTabla .=  '<th >SALIDA</th>';
          $htmlTabla .=  '<th >LUNES</th>';
          $htmlTabla .=  '<th >MARTES</th>';
          $htmlTabla .=  '<th >MIERCOLES</th>';
          $htmlTabla .=  '<th >JUEVES</th>';
          $htmlTabla .=  '<th >VIERNES</th>';
          $htmlTabla .=  '</tr>';
          while ($row = $horario->fetch()) {

            $htmlTabla .=  '<tbody>';
            $htmlTabla .=  '<tr>';
            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center">' . formatearhora($row['hora']) . '</td>';
            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center">' .  formatearhora($row['salida']) . '</td>';
            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center">' .  $row['lunes'] . '</td>';
            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center">' .  $row['martes'] . '</td>';
            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center" >' .  $row['miercoles'] . '</td>';
            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center">' .  $row['jueves'] . '</td>';
            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center">' .  $row['viernes'] . '</td>';
            $htmlTabla .=  '</tr>';
            $htmlTabla .=  '</tbody>';
          }
          $htmlTabla .=  '</table>';
          $mpdf->WriteHTML($htmlTabla);
          $mpdf->ln(3);
          $mpdf->WriteHTML('<strong>Profesores:</strong>');
          $profTabla = '';
          $profTabla .= ' <table align="left" width="100%" class=" styled-table table-striped table-bordered table-hover" style="font-size: 10px;">';
          $profTabla .= '<tr>';
          $profTabla .= '<th >ASIGNATURA</th>';
          $profTabla .= '<th >CEDULA</th>';
          $profTabla .= '<th >NOMBRE</th>';
          $profTabla .= '<th >APELLIDO</th>';
          $profTabla .= '</tr>';

          $prof = $db->runQuery($sql2, [$id_secc]);
          while ($row = $prof->fetch()) {
            $profTabla .=  '<tbody>';
            $profTabla .=  ' <tr>';
            $profTabla .=   '<td style="padding: 1px;" align="left">' .  $row['asignatura'] . '</td>';
            $profTabla .=   '<td style="padding: 1px;" align="center">' .  $row['cedula'] . '</td>';
            $profTabla .=   '<td style="padding: 1px;" align="left">' .  $row['nombre'] . '</td>';
            $profTabla .=   '<td style="padding: 1px;" align="left">' .  $row['apellido'] . '</td>';
            $profTabla .=  '</tr>';
            $profTabla .=  '</tbody>';
          }

          $profTabla .= '</table>';

          $mpdf->WriteHTML($profTabla);

          $mpdf->Output('filename.pdf', \Mpdf\Output\Destination::INLINE);
        } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
          // Process the exception, log, print etc.
          echo $e->getMessage();
        }

        break;

      default:
        Flight::json(array(
          'mensaje' => 'Opción no válida'
        ));
    }
  }
  public static  function ubicaciones()
  {

    $opcion = Flight::request()->data->opcion;
    $databaseService = new DatabaseService();
    $db = $databaseService->getConnection();
    $opcion = Flight::request()->data->opcion;
    switch ($opcion) {
      case 1:
        $respuesta = $db->fetchAll("SELECT *
            FROM
            `estados` order by estado asc");
        Flight::json($respuesta, 200);

        break;
      case 2:
        $estado = Flight::request()->data->id_estado;
        $respuesta = $db->fetchAll('SELECT *
            FROM
            `ciudades`
                WHERE
                id_estado = ? order by ciudad asc', [$estado]);
        Flight::json($respuesta, 200);

        break;
      case 3:
        $ciudad = Flight::request()->data->id_ciudad;
        $respuesta = $db->fetchAll('SELECT *
            FROM
            `municipios`
                WHERE
                id_ciudad = ? order by municipio asc', [$ciudad]);
        Flight::json($respuesta, 200);

        break;
      case 4:
        $municipio = Flight::request()->data->id_municipio;
        $respuesta = $db->fetchAll('SELECT *
            FROM
            `parroquias`
                WHERE
                id_municipio = ? order by parroquia asc', [$municipio]);
        Flight::json($respuesta, 200);

        break;
      default:
        Flight::json(array(
          'mensaje' => 'Opción no válida'
        ));
    }
  }

  public static  function periodos()
  {
    $opcion = Flight::request()->data->opcion;
    $databaseService = new DatabaseService();
    $db = $databaseService->getConnection();


    switch ($opcion) {
      case 1:
        $inicio = Flight::request()->data->inicio;
        $cierre = Flight::request()->data->cierre;
        $periodo = Flight::request()->data->periodoAnt;
        try {

          $sql = 'SELECT DISTINCT 
          `alumnos`.`cedulae`,
          `alumnos`.`cedula`,
          `alumnos`.`nombres`,
          `alumnos`.`apellidos`,
          `secciones`.`descripcion`,
          `materias`.`materia`,
          `notas`.`lapso1`,
          `notas`.`lapso2`,
          `notas`.`lapso3`,
          SUM(`notas`.`lapso1` + `notas`.`lapso2` + `notas`.`lapso3`) / 3 AS `promedio`
        FROM
          `notas`
          INNER JOIN `asignaturas` ON (`notas`.`id_asignatura` = `asignaturas`.`id`)
          INNER JOIN `materias` ON (`asignaturas`.`id_materia` = `materias`.`id`)
          INNER JOIN `alumnos` ON (`notas`.`id_alumno` = `alumnos`.`id`)
          INNER JOIN `secciones` ON (`alumnos`.`id_seccion` = `secciones`.`id`)
        GROUP BY
          `materias`.`materia`,
          `notas`.`lapso1`,
          `notas`.`lapso2`,
          `notas`.`lapso3`,
          `secciones`.`descripcion`,
          `alumnos`.`cedulae`,
          `alumnos`.`cedula`,
          `alumnos`.`nombres`,
          `alumnos`.`apellidos`';
          $statement = $db->runQuery($sql);


          while ($row = $statement->fetch()) {
            $db->runQuery("INSERT INTO historico (cedulae, cedula, nombre, apellido, seccion, materia, lapso1,lapso2, lapso3, promedio, periodo ) VALUES (?,?,?,?,?,?,?,?,?,?,?)", [$row['cedulae'], $row['cedula'], $row['nombres'], $row['apellidos'], $row['descripcion'], $row['materia'], $row['lapso1'], $row['lapso2'], $row['lapso3'], $row['promedio'], $periodo]);
          }


          $db->runQuery("DELETE FROM periodos");
          $db->runQuery("INSERT INTO periodos (inicio, cierre) values(?,?)", [$inicio, $cierre]);
          $db->runQuery("DELETE FROM horariosprof");
          $db->runQuery("DELETE FROM alumnos");
          $db->runQuery("DELETE FROM notas");
          $db->runQuery("DELETE FROM asignaturas");
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
      case 2:

        $row = $db->fetchRow("SELECT inicio, cierre FROM periodos");

        Flight::json(array(

          'inicio' => $row['inicio'],
          'cierre' => $row['cierre']
        ), 200);


      default:
        # code...
        break;
    }
  }
}

/********************************** */
function getCod()
{
  $id = null;
  $databaseService = new DatabaseService();
  $db = $databaseService->getConnection();
  $query = "SELECT max(id) FROM materias ";
  try {

    $row = $db->fetchField($query);

    if ($row > 0 && !empty($row)) {
      $id = $row + 1;
    } else {
      $id = 1;
    }
    return $id;
  } catch (Exception $e) {
    echo json_encode(array(
      "mensaje" => "Access Denegado",
      "error" => $e->getMessage()
    ));
  }
}

function formatearhora($hora)
{
  $timestamp = strtotime($hora);
  $hora_formato_12h = date("h:i a", $timestamp);

  return  $hora_formato_12h;
}
