
<?php
/**
 * Script para generar las claves pública y privada necesarias para OAuth 2.0
 */

// Directorio de la aplicación
$appDir = dirname(__DIR__);

echo "Generando clave privada...\n";

// Generar clave privada RSA
$privateKey = openssl_pkey_new([
    'private_key_bits' => 2048,      // Tamaño de la clave
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
]);

// Guardar la clave privada en un archivo
openssl_pkey_export_to_file($privateKey, $appDir . '/private.key');

echo "Clave privada generada en: " . $appDir . "/private.key\n";

// Obtener los detalles de la clave privada para generar la clave pública
$keyDetails = openssl_pkey_get_details($privateKey);

// Guardar la clave pública en un archivo
file_put_contents($appDir . '/public.key', $keyDetails['key']);

echo "Clave pública generada en: " . $appDir . "/public.key\n";

echo "¡Listo! Las claves se han generado correctamente.\n";
?>