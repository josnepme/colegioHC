<?php
class Historico
{
    public static  function historicoFunc()
    {
        $opcion = Flight::request()->data->opcion;
        $databaseService = new DatabaseService();
        $db = $databaseService->getConnection();
        switch ($opcion) {
            case 1:
                $sql = 'SELECT DISTINCT
                `historico`.`periodo`
                FROM
                `historico`
                GROUP BY
                `historico`.`periodo`';
                $respuesta = $db->fetchAll($sql);
                Flight::json($respuesta, 200);
                break;
            case 2:
                $periodo = Flight::request()->data->periodo;
                $sql = 'SELECT DISTINCT
                `historico`.`seccion`
                FROM
                `historico`
                WHERE
                `periodo` = ?
                    GROUP BY
                `historico`.`seccion`';
                $respuesta = $db->fetchAll($sql, [$periodo]);
                Flight::json($respuesta, 200);

                break;
            case 3:
                $periodo = Flight::request()->data->periodo;
                $seccion = Flight::request()->data->seccion;
                $sql = 'SELECT DISTINCT 
                    `historico`.`cedulae`,
                    `historico`.`cedula`,
                    `historico`.`nombre`,
                    `historico`.`apellido`,
                    `historico`.`seccion`,
                    AVG( `historico`.`lapso1`) as lapso1,
                    AVG(`historico`.`lapso2`) as lapso2,
                    AVG( `historico`.`lapso3`) as lapso3,
                    AVG( `historico`.`promedio`) as promedio,
                    `historico`.`periodo`
                    FROM
                    `historico`
                    WHERE
                    `historico`.`seccion` = ? AND 
                    `historico`.`periodo` = ?
                    GROUP BY
                    `historico`.`cedulae`,
                    `historico`.`cedula`,
                    `historico`.`nombre`,
                    `historico`.`apellido`,
                    `historico`.`seccion`,
                    `historico`.`periodo`';
                $respuesta = $db->fetchAll($sql, [$seccion, $periodo]);
                Flight::json($respuesta, 200);

                break;

            case 4:
                $periodo = Flight::request()->data->periodo;
                $seccion = Flight::request()->data->seccion;
                $cedulae = Flight::request()->data->cedulae;
                $sql = 'SELECT 
                    `historico`.`id`,
                    `historico`.`cedulae`,
                    `historico`.`materia`,
                    `historico`.`lapso1`,
                    `historico`.`lapso2`,
                    `historico`.`lapso3`,
                    `historico`.`promedio`,
                    `historico`.`periodo`,
                    `historico`.`seccion`
                    FROM
                    `historico`
                    WHERE
                    `historico`.`cedulae` = ? AND
                    `historico`.`seccion` = ? AND
                    `historico`.`periodo` = ?';
                $respuesta = $db->fetchAll($sql, [$cedulae, $seccion, $periodo]);
                Flight::json($respuesta, 200);
                break;

            default:
                # code...
                break;
        }
    }
}
