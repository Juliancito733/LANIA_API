<?php
require_once __DIR__ . '/../../config/database.php';
use Config\Database;

class Candidato {
    public static function getCandidatos() {
        $conn = Database::connect(); 

        $sql = "SELECT
            c.id_candidato,
            CONCAT(c.nombres, ' ', c.primer_apellido, ' ', IFNULL(c.segundo_apellido, '')) AS nombre_completo,
            c.correo,
            c.telefono,
            g.descripcion AS genero,
            r.descripcion AS rango_edad,
            ti.descripcion AS tipo_identificacion,
            ne.descripcion AS nivel_estudio,
            gi.descripcion AS giro,
            ic.nombre_empresa_institucion,
            ic.motivo_examen,
            ic.calificacion_servicio,
            ic.consentimiento_pub AS consentimiento_publicidad,
            pa.nombre AS pais,
            es.nombre AS estado,
            mu.nombre AS municipio,
            co.nombre AS colonia,
            e.id_examen,
            e.nombre_examen,
            c.fecha_entrada,
            c.fecha_salida
        FROM candidato c
        JOIN info_candidatos ic ON c.id_candidato = ic.id_candidato
        JOIN genero g ON c.id_genero = g.id_genero
        JOIN rango_edad r ON c.id_rango_edad = r.id_rango_edad
        JOIN tipo_identificacion ti ON c.id_tipo_id = ti.id_tipo_id
        JOIN examen e ON c.id_examen = e.id_examen
        JOIN nivel_estudio ne ON ic.id_nivel = ne.id_nivel
        JOIN giro gi ON ic.id_giro = gi.id_giro
        JOIN paises pa ON ic.id_pais = pa.id
        LEFT JOIN estados es ON ic.id_estado = es.id
        LEFT JOIN municipios mu ON ic.id_municipio = mu.id
        LEFT JOIN colonias co ON ic.id_colonia = co.id;";

        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}


?>