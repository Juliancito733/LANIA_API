<?php
require_once __DIR__ . '/../models/Candidato.php';

class CandidatoService {
    public static function getCandidatos () {
        $rawCandidatos = Candidato::getCandidatos();
        $candidatos = [];

        foreach ($rawCandidatos as $candidato) {
            $candidatos[] = [
                "id_candidato" => $candidato["id_candidato"],
                "nombre_completo" => $candidato["nombre_completo"],
                "contacto" => [
                    "correo" => $candidato["correo"],
                    "telefono" => $candidato["telefono"]
                ],
                "demograficos" => [
                    "genero" => $candidato["genero"],
                    "rango_edad" => $candidato["rango_edad"],
                    "tipo_identificacion" => $candidato["tipo_identificacion"]
                ],
                "ubicacion" => [
                    "pais" => $candidato["pais"],
                    "estado" => $candidato["estado"],
                    "municipio" => $candidato["municipio"],
                    "colonia" => $candidato["colonia"]
                ],
                "formacion" => [
                    "nivel_estudio" => $candidato["nivel_estudio"],
                    "giro" => $candidato["giro"],
                    "nombre_empresa_institucion" => $candidato["nombre_empresa_institucion"]
                ],
                "examen" => [
                    "id_examen" => $candidato["id_examen"],
                    "nombre_examen" => $candidato["nombre_examen"],
                    "motivo" => $candidato["motivo_examen"]
                ],
                "experiencia_servicio" => [
                    "calificacion_servicio" => (int)$candidato["calificacion_servicio"],
                    "consentimiento_publicidad" => $candidato["consentimiento_publicidad"] == "1"
                ],
                "fechas" => [
                    "entrada" => $candidato["fecha_entrada"],
                    "salida" => $candidato["fecha_salida"]
                ]
            ];
        }

        return $candidatos;
    }
}
?>
