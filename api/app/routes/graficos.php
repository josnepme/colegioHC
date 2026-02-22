<?php

class Graficos
{

    public static  function graficos()
    {
        $opcion = Flight::request()->data->opcion;
        $databaseService = new DatabaseService();
        $db = $databaseService->getConnection();

        switch ($opcion) {
            case 1:

                $respuesta = $db->fetchAll('SELECT 
                `personal`.`tipo_personal`, COUNT(*) as cantidad
                FROM
                `personal`
                GROUP BY
                `personal`.`tipo_personal`');
                Flight::json($respuesta, 200);


                break;
            case 2:
                $respuesta = $db->fetchAll('SELECT 
                COUNT(`alumnos`.`id`) AS `cantidad`,
                `secciones`.`descripcion`
                FROM
                `alumnos`
                INNER JOIN `secciones` ON (`alumnos`.`id_seccion` = `secciones`.`id`)
                GROUP BY
                `secciones`.`descripcion`
                ORDER BY
                `secciones`.`descripcion`');
                Flight::json($respuesta, 200);
                break;
            case 3:
                $respuesta = $db->fetchAll("SELECT
                    tipo_personal,
                    COUNT(CASE WHEN sexo = 'F' THEN 1 END) AS cantidad_f,
                    COUNT(CASE WHEN sexo = 'M' THEN 1 END) AS cantidad_m
                    FROM personal
                    GROUP BY tipo_personal
                    ORDER BY tipo_personal");

                Flight::json($respuesta, 200);

                break;
            case 4:
                $count = $db->fetchField("SELECT COUNT(*) as cantidad
                FROM
                `alumnos`");
                Flight::json($count, 200);
                break;
            case 5:
                $count = $db->fetchField("SELECT COUNT(*) as cantidad
                FROM
                `personal`");
                Flight::json($count, 200);
                break;
            case 6:
                $count = $db->fetchField("SELECT 
                COUNT(*) AS cantidad
                FROM
                personal
                WHERE
                tipo_personal = 'DOCENTE'");
                Flight::json($count, 200);
                break;
        }
    }
}
