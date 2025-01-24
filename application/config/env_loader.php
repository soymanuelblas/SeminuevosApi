<?php
$dotenv_path = FCPATH . '.env';
if (file_exists($dotenv_path)) {
    $variables = parse_ini_file($dotenv_path, false, INI_SCANNER_RAW);
    foreach ($variables as $key => $value) {
        putenv("$key=$value");
    }
}