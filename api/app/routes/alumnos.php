      <?php

        class Alumnos
        {
            public static  function alumnosCrud()
            {
                $opcion = Flight::request()->data->opcion;
                $databaseService = new DatabaseService();
                $db = $databaseService->getConnection();
                $id_seccion = Flight::request()->data->id_seccion;
                switch ($opcion) {
                    case 1:
                        $respuesta = $db->fetchAll("  
                       SELECT `alumnos`.`id`,
                            `alumnos`.`id_seccion`,
                            `alumnos`.`cedula`,
                            `alumnos`.`nombres`,
                            `alumnos`.`apellidos`,
                            `alumnos`.`sexo`,
                            `alumnos`.`fechanac`,
                            YEAR (CURDATE()) - YEAR (`fechanac`) + IF (DATE_FORMAT(CURDATE(), '%m-%d'
                                ) > DATE_FORMAT(`fechanac`, '%m-%d'), 0, - 1) AS `edad`,
                            `alumnos`.`foto`,
                            `estados`.`estado`,
                            `ciudades`.`ciudad`,
                            `municipios`.`municipio`,
                            `parroquias`.`parroquia`,
                            `alumnos`.`peso`,
                            `alumnos`.`camisa`,
                            `alumnos`.`pantalon`,
                            `alumnos`.`calzado`,
                            `alumnos`.`media`,
                            `alumnos`.`codigo`,
                            `alumnos`.`pendiente`,
                            `alumnos`.`canaima`,
                            `alumnos`.`ced_repre`,
                            `alumnos`.`nom_repre`,
                            `alumnos`.`ape_repre`,
                            `alumnos`.`direccion`,
                            `alumnos`.`telefono`,
                            `alumnos`.`correo`, 
                            `alumnos`.`cedulae`,
                            `alumnos`.`cedulam`,
                            `alumnos`.`nombrem`,
                            `alumnos`.`apellidom`
                        FROM `estados`
                            INNER JOIN `alumnos` ON (`estados`.`id` = `alumnos`.`estado`)
                            INNER JOIN `ciudades` ON (`alumnos`.`ciudad` = `ciudades`.`id`)
                            INNER JOIN `municipios` ON (`alumnos`.`municipio` = `municipios`.`id`)
                            INNER JOIN `parroquias` ON (`alumnos`.`parroquia` = `parroquias`.`id`)
                        WHERE `alumnos`.`id_seccion` = ?", [$id_seccion]);
                        Flight::json($respuesta, 200);
                        break;
                    case 2:

                        $cedula = Flight::request()->data->cedula;
                        $nombres = Flight::request()->data->nombre;
                        $apellidos = Flight::request()->data->apellido;
                        $sexo = Flight::request()->data->sexo;
                        $fechanac = Flight::request()->data->fechanac;
                        $estado = Flight::request()->data->estado;
                        $ciudad = Flight::request()->data->ciudad;
                        $municipio = Flight::request()->data->municipio;
                        $parroquia = Flight::request()->data->parroquia;
                        $peso = Flight::request()->data->peso;
                        $camisa = Flight::request()->data->camisa;
                        $pantalon = Flight::request()->data->pantalon;
                        $calzado = Flight::request()->data->calzado;
                        $media = Flight::request()->data->media;
                        $codigo = Flight::request()->data->codigo;
                        $pendiente = Flight::request()->data->pendiente;
                        $canaima = (Flight::request()->data->canaima == 'true') ? 1 : 0;
                        $ced_repre = Flight::request()->data->ced_repre;
                        $nom_repre = Flight::request()->data->nom_repre;
                        $ape_repre = Flight::request()->data->ape_repre;
                        $direccion = Flight::request()->data->direccion;
                        $telefono = Flight::request()->data->telefono;
                        $correo = Flight::request()->data->correo;
                        $cedulam = Flight::request()->data->cedulam;
                        $nombrem = Flight::request()->data->nombrem;
                        $apellidom = Flight::request()->data->apellidom;
                        $cedulae = Flight::request()->data->cedulae;


                        // $foto = Flight::request()->data->foto;

                        // $id = Flight::request()->data->id;
                        try {
                            $sql = "INSERT INTO `alumnos` (`id_seccion`,
                            `cedula`,
                            `nombres`,
                            `apellidos`,
                            `sexo`,
                            `fechanac`,
                            `estado`,
                            `ciudad`,
                            `municipio`,
                            `parroquia`,
                            `peso`,
                            `camisa`,
                            `pantalon`,
                            `calzado`,
                            `media`,
                            `codigo`,
                            `pendiente`,
                            `canaima`,
                            `ced_repre`,
                            `nom_repre`,
                            `ape_repre`,
                            `direccion`,
                            `telefono`,
                            `correo`,
                            `cedulae`,
                            `cedulam`,
                            `nombrem`,
                            `apellidom`) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                            $respuesta = $db->runQuery($sql, [
                                $id_seccion,
                                $cedula,
                                $nombres,
                                $apellidos,
                                $sexo,
                                $fechanac,
                                $estado,
                                $ciudad,
                                $municipio,
                                $parroquia,
                                $peso,
                                $camisa,
                                $pantalon,
                                $calzado,
                                $media,
                                $codigo,
                                $pendiente,
                                $canaima,
                                $ced_repre,
                                $nom_repre,
                                $ape_repre,
                                $direccion,
                                $telefono,
                                $correo,
                                $cedulae,
                                $cedulam,
                                $nombrem,
                                $apellidom
                            ]);

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
                        $alumnos = Flight::db()->fetchRow('SELECT * FROM alumnos WHERE id = ?', [$id]);
                        Flight::json($alumnos, 200);

                        break;
                    case 4:
                        $id = Flight::request()->data->id;
                        $cedula = Flight::request()->data->cedula;
                        $nombres = Flight::request()->data->nombre;
                        $apellidos = Flight::request()->data->apellido;
                        $sexo = Flight::request()->data->sexo;
                        $fechanac = Flight::request()->data->fechanac;
                        $estado = Flight::request()->data->estado;
                        $ciudad = Flight::request()->data->ciudad;
                        $municipio = Flight::request()->data->municipio;
                        $parroquia = Flight::request()->data->parroquia;
                        $peso = Flight::request()->data->peso;
                        $camisa = Flight::request()->data->camisa;
                        $pantalon = Flight::request()->data->pantalon;
                        $calzado = Flight::request()->data->calzado;
                        $id_seccion = Flight::request()->data->seccion;
                        $media = Flight::request()->data->media;
                        $codigo = Flight::request()->data->codigo;
                        $pendiente = Flight::request()->data->pendiente;
                        $canaima = (Flight::request()->data->canaima == 'true') ? 1 : 0;
                        $ced_repre = Flight::request()->data->ced_repre;
                        $nom_repre = Flight::request()->data->nom_repre;
                        $ape_repre = Flight::request()->data->ape_repre;
                        $direccion = Flight::request()->data->direccion;
                        $telefono = Flight::request()->data->telefono;
                        $correo = Flight::request()->data->correo;
                        $cedulam = Flight::request()->data->cedulam;
                        $nombrem = Flight::request()->data->nombrem;
                        $apellidom = Flight::request()->data->apellidom;
                        $cedulae = Flight::request()->data->cedulae;
                        try {
                            $sql = 'UPDATE alumnos set id_seccion =?, cedulae=?, cedula =?, nombres =?, apellidos =?, sexo =?, fechanac =?, estado =?, ciudad =?, municipio =?, parroquia =?, peso =?, camisa =?, pantalon =?, calzado =?, media =?, codigo =?, pendiente =?, canaima =?, ced_repre =?, nom_repre =?, ape_repre =?, direccion =?, telefono =?, correo =?, cedulam=?, nombrem=?,apellidom=? WHERE id =?';
                            $respuesta = $db->runQuery($sql, [
                                $id_seccion,
                                $cedulae,
                                $cedula,
                                $nombres,
                                $apellidos,
                                $sexo,
                                $fechanac,
                                $estado,
                                $ciudad,
                                $municipio,
                                $parroquia,
                                $peso,
                                $camisa,
                                $pantalon,
                                $calzado,
                                $media,
                                $codigo,
                                $pendiente,
                                $canaima,
                                $ced_repre,
                                $nom_repre,
                                $ape_repre,
                                $direccion,
                                $telefono,
                                $correo,
                                $cedulam,
                                $nombrem,
                                $apellidom,
                                $id
                            ]);
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
                            $sql = 'DELETE FROM alumnos WHERE id = ?';
                            $respuesta = $db->runQuery($sql, [$id]);
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
                        $id = Flight::request()->data->id_alum;
                        $periodo = Flight::request()->data->periodo;


                        $mpdf = new \Mpdf\Mpdf([
                            'mode' => 'utf-8',
                            //'format' => 'Legal',
                            'format' => 'A4',
                            'orientation' => 'P'
                        ]);

                        $mpdf->useSubstitutions = false;
                        $mpdf->setAutoTopMargin = 'stretch';
                        $mpdf->SetDisplayMode('real');
                        $mpdf->SetAuthor('Jose Meneses');
                        $mpdf->SetDefaultBodyCSS('background', "url('../imagenes/ENCABEZADO.png')"); // poner la imagend e fondo
                        $mpdf->SetDefaultBodyCSS('background-image-resize', 6);

                        $cabecera = '<br/><br/><br/><br/>
                        <table width="100%" style="font-weight: bold; font-size:9pt; " >
                        <tr>
                        <td width="33%" align="center" >REPÚBLICA BOLIVARIANA DE VENEZUELA <br/> 
                        MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN <br/>
                        COMPLEJO EDUCATIVO “HUGO RAFAEL CHAVÉZ FRÍAS” <br/>
                        CÓDIGO DEA: OD15070304 <br/>
                        BARCELONA- EDO. ANZOÁTEGUI"  <br/>  <img src="../imagenes/111.png" width="80" height="80" alt="Logo"><br/><br/> 
                        <strong><u>BOLETÍN DE CALIFICACIONES</u></strong> 
                        </td>
                        </tr></table> ';
                        $mpdf->SetHTMLHeader($cabecera);
                        $mpdf->WriteHTML('');
                        $mpdf->Image('../imagenes/sello.png', 150, 50, 25, 25, 'png', '', true, false);

                        $sql = ('SELECT
                        `alumnos`.`id`,
                        `alumnos`.`cedulae`,
                        `alumnos`.`cedula`,
                        `alumnos`.`nombres`,
                        `alumnos`.`apellidos`,
                        `secciones`.`grado`,
                        `secciones`.`descripcion`,
                        `secciones`.`seccion`,
                        `alumnos`.`media`
                        FROM
                        `alumnos`
                        INNER JOIN `secciones` ON (`alumnos`.`id_seccion` = `secciones`.`id`)
                        where  `alumnos`.`id` = ?');

                        $row = $db->fetchRow($sql, [$id]);
                        $cedula = $row['cedula'] ? $row['cedula'] : $row['cedulae'];
                        $nombres = $row['nombres'];
                        $apellidos = $row['apellidos'];
                        $grado = $row['grado'];
                        $descripcion = $row['descripcion'];
                        $seccion = $row['seccion'];
                        $media = $row['media'];

                        $mpdf->Ln(15);

                        if ($media == 'BACHILLER') {
                            $mpdf->WriteHTML('<p style="text-align: justify; font-size:9pt;"><b>Nombres: </b> <u>' . $nombres . '</u> <b>Apellidos: </b> <u>' . $apellidos . ' </u><b> C.I:</b> <u>' . $cedula . '</u> <b>Grado: </b> <u>' . $grado . '</u> <b>Sección: </b> <u>' . $seccion . ' </u>Periodo Escolar: <b><u>' . $periodo . '</u></b> Plan de Estudio: Media General, Sin mencion. Código: <b> (47000) </b></p>');
                        } else {
                            $mpdf->WriteHTML('<p style="text-align: justify; font-size:9pt;"><b>Nombres: </b> <u>' . $nombres . '</u> <b>Apellidos: </b> <u>' . $apellidos . ' </u><b> C.I:</b> <u>' . $cedula . '</u> <b>Grado: </b> <u>' . $grado . '</u> <b>Sección: </b> <u>' . $seccion . ' </u>Periodo Escolar: <b><u>' . $periodo . '</u></b> Plan de Estudio: Educación Técnica, Código: <b> (31059) </b></p>');
                        }

                        $mpdf->Ln(2);
                        $stylesheet = file_get_contents('../css/tablas2.css'); // la ruta a tu css
                        $mpdf->WriteHTML($stylesheet, 1);

                        $htmlTabla = "";

                        $htmlTabla .= ' <table align="center" width="100%" class="styled-table table-striped table-bordered table-hover" style="font-size: 11px;">';
                        $htmlTabla .= '<tr>';
                        $htmlTabla .= '<td style="border: none; border-collapse: collapse;"></td>';
                        $htmlTabla .= ' <td style= "background-color: #e0e3e6; font-weight: bold;" COLSPAN=3>CALIFICACIONES</td>';
                        $htmlTabla .= ' <td style= "background-color: #e0e3e6; font-weight: bold;" COLSPAN=3>INASISTENCIAS</td>';
                        $htmlTabla .= '<td style="border: none; border-collapse: collapse;"></td>';
                        $htmlTabla .= '</tr>';

                        $htmlTabla .= '<tr>';
                        $htmlTabla .= '<td style="border: none; border-collapse: collapse;"></td>';
                        $htmlTabla .= ' <td style= "background-color: #e0e3e6; font-weight: bold;" COLSPAN=3>LAPSOS PEDAGÓGICOS</td>';
                        $htmlTabla .= ' <td style= "background-color: #e0e3e6; font-weight: bold;" COLSPAN=3>LAPSOS PEDAGÓGICOS</td>';
                        $htmlTabla .= '<td style="border: none; border-collapse: collapse;"></td>';
                        $htmlTabla .= '</tr>';

                        $htmlTabla .= '<tr>';
                        $htmlTabla .=  '<th  width="60%">ÁREAS DE FORMACIÓN</th>';
                        $htmlTabla .=  '<th >I</th>';
                        $htmlTabla .=  '<th >II</th>';
                        $htmlTabla .=  '<th >III</th>';
                        $htmlTabla .=  '<th >I</th>';
                        $htmlTabla .=  '<th >II</th>';
                        $htmlTabla .=  '<th >III</th>';
                        $htmlTabla .=  '<th >DEF.</th>';
                        $htmlTabla .=  '</tr>';
                        $sql2 = 'SELECT `materias`.`materia`,
                        `notas`.`lapso1`,
                        `notas`.`lapso2`,
                        `notas`.`lapso3`
                            FROM `notas`
                                INNER JOIN `alumnos` ON (`notas`.`id_alumno` = `alumnos`.`id`)
                                INNER JOIN `asignaturas` ON (`notas`.`id_asignatura` = `asignaturas`.`id`)
                                INNER JOIN `materias` ON (`asignaturas`.`id_materia` = `materias`.`id`)
                            WHERE `alumnos`.`id` = ?';
                        $count = $db->fetchField("SELECT COUNT(`materias`.`materia`)
                                FROM `notas`
                                INNER JOIN `alumnos` ON (`notas`.`id_alumno` = `alumnos`.`id`)
                                INNER JOIN `asignaturas` ON (`notas`.`id_asignatura` = `asignaturas`.`id`)
                                INNER JOIN `materias` ON (`asignaturas`.`id_materia` = `materias`.`id`)
                            WHERE `alumnos`.`id` = ?", [$id]);
                        $promedio = 0;
                        $lapso1 = 0;
                        $lapso2 = 0;
                        $lapso3 = 0;

                        $notas = $db->runQuery($sql2, [$id]);

                        while ($row = $notas->fetch()) {
                            $promedio = ($row['lapso1'] + $row['lapso2'] + $row['lapso3']) / 3;
                            $lapso1 += $row['lapso1'];
                            $lapso2 += $row['lapso2'];
                            $lapso3 += $row['lapso3'];


                            $htmlTabla .=  '<tbody>';
                            $htmlTabla .=  '<tr>';
                            $htmlTabla .=   '<td width="60%" style="padding: 1px;" align="center">' . $row['materia'] . '</td>';
                            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center">' . round($row['lapso1']) . '</td>';
                            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center">' .  round($row['lapso2']) . '</td>';
                            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center">' .  round($row['lapso3']) . '</td>';
                            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center"></td>';
                            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center"></td>';
                            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center"></td>';
                            $htmlTabla .=   '<td width="33%" style="padding: 1px;" align="center"></td>';
                            $htmlTabla .=  '</tr>';
                            $htmlTabla .=  '</tbody>';
                        }
                        $htmlTabla .= '<tfoot>
                                   <tr>
                                   <th>TOTAL PROMEDIO POR LAPSO</th>
                                   <th>' . round($lapso1 / $count) . '</th>
                                   <th>' . round($lapso2 / $count) . '</th>
                                   <th>' . round($lapso3 / $count) . '</th>
                                   <th></th>
                                   <th></th>
                                   <th></th>
                                    <th></th>
                                   </tr>
                                   </tfoot>';
                        $htmlTabla .=  '</table>';
                        $mpdf->WriteHTML($htmlTabla);
                        $mpdf->WriteHTML('<div style=" width: 100%; border: 1.5px solid black; background-color: #e0e3e6; text-align: center; justify-content: center; align-items: center; font-size:10pt;">
                        ÁREAS DE FORMACIÓN PENDIENTE
                        </div>');

                        $mpdf->WriteHTML('<table class="styled-table table-striped table-bordered table-hover" width="100%" style="font-size:9pt;" ><tr>
         <td width="88%" align="center" style= "background-color: #e0e3e6; font-weight: bold;" COLSPAN=2>ÁREAS DE FORMACIÓN </td>
         <td width="33%" align="center" style= "background-color: #e0e3e6; font-weight: bold; font-size:9pt;" COLSPAN=2>1er Momento </td>
         <td width="33%" align="center" style= "background-color: #e0e3e6; font-weight: bold; font-size:9pt;" COLSPAN=2>2do Momento </td>
         <td width="33%" align="center" style= "background-color: #e0e3e6; font-weight: bold; font-size:9pt;" COLSPAN=2>3er Momento</td>
         <td width="33%" align="center" style= "background-color: #e0e3e6; font-weight: bold; font-size:9pt;" COLSPAN=2>4to Momento</td>
         <td width="13%" align="center" style= "background-color: #e0e3e6; font-weight: bold; font-size:9pt;" >DEF</td>
         </tr>
         <tr>
        <th >1</th>
          <td width="88%"> </td>
          <td> </td>
          <td> </td>
          <td> </td>
          <td> </td>
          <td> </td>
          <td> </td>
          <td> </td>
          <td> </td>
          <td> </td>
        </tr>
         <tr>
        <th >2</th>
          <td width="88%"> </td>
          <td> </td>
          <td> </td>
          <td> </td>
          <td> </td>
          <td> </td>
          <td> </td>
          <td> </td>
          <td> </td>
          <td> </td>
        </tr>
         </table>');
                        $mpdf->ln(2);
                        $mpdf->WriteHTML('<p style="text-align: justify; font-size:11pt;">Constancia que se expide a petición de la parte interesada el día_____ del mes ________ del año ______.</p>');
                        $mpdf->ln(15);
                        $mpdf->WriteHTML('');
                        $mpdf->SetY(248);
                        $mpdf->WriteHtml('<table width="100%" style="font-size:11pt;"  ><tr>
        <td width="33%" align="center" >_____________________________ <br/>
        MSc. YONNY RAMÍREZ<br/>
        C.I. V-14764553<br/>
        <b> Director</b>
        </td>
        </tr></table>');

                        $mpdf->Output('filename.pdf', \Mpdf\Output\Destination::INLINE);
                }
            }
        }
