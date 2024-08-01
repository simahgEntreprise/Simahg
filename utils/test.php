<?php
echo "PHP está funcionando correctamente<br>";
echo "Directorio actual: " . __DIR__ . "<br>";
echo "Archivo index.php existe: " . (file_exists('index.php') ? 'SÍ' : 'NO') . "<br>";
echo "CodeIgniter system existe: " . (is_dir('system') ? 'SÍ' : 'NO') . "<br>";
echo "Application existe: " . (is_dir('application') ? 'SÍ' : 'NO') . "<br>";
phpinfo();
?>
