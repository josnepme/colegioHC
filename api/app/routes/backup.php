<?php
$dotenv = Dotenv\Dotenv::createImmutable('../../');
$dotenv->load();
class Respaldos
{
  public static function respaldo()
  {
    try {
      $opcion = Flight::request()->data->opcion;
      $db_host = $_ENV['db_host'];
      $db_name = $_ENV['db_name'];
      $db_user = $_ENV['db_user'];
      $db_password = $_ENV['db_password'];

      $backupDir = __DIR__ . '/../../backups/';
      if (!file_exists($backupDir)) {
        mkdir($backupDir, 0777, true);
      }

      $mysqldumpPath = 'mysqldump';
      $possiblePaths = [
        'C:\\wamp64\\bin\\mysql\\mysql8.3.0\\bin\\mysqldump.exe',
        'C:\\wamp64\\bin\\mysql\\mysql8.0.27\\bin\\mysqldump.exe',
        'C:\\wamp64\\bin\\mysql\\mysql5.7.36\\bin\\mysqldump.exe',
        'C:\\wamp64\\bin\\mysql\\mysql9.1.0\\bin\\mysqldump.exe',
        'C:\\xampp\\mysql\\bin\\mysqldump.exe',
      ];

      foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
          $mysqldumpPath = $path;
          break;
        }
      }

      switch ($opcion) {
        case 1:
          $fecha = date('Y-m-d_H-i-s');
          $backupFile = $backupDir . $db_name . '_' . $fecha . '.sql';

          if (empty($db_password)) {
            $command = "\"{$mysqldumpPath}\" --user={$db_user} --host={$db_host} {$db_name} > \"{$backupFile}\" 2>&1";
          } else {
            $command = "\"{$mysqldumpPath}\" --user={$db_user} --password=\"{$db_password}\" --host={$db_host} {$db_name} > \"{$backupFile}\" 2>&1";
          }

          exec($command, $output, $result);

          if (file_exists($backupFile) && filesize($backupFile) > 0) {
            Flight::json([
              'success' => true,
              'mensaje' => 'Backup creado exitosamente'
            ]);
          } else {
            Flight::json([
              'success' => false,
              'mensaje' => 'Error: Verifique que mysqldump esté disponible'
            ], 500);
          }
          break;
      }
    } catch (Exception $e) {
      Flight::json([
        'success' => false,
        'mensaje' => 'Error: ' . $e->getMessage()
      ], 500);
    }
  }
}
