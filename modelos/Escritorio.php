<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Escritorio
{

  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  
  // optenemos el total de PROYECTOS, PROVEEDORES, TRABAJADORES, SERVICIO
  public function tablero()  {
    $sql = "SELECT COUNT(p.idproducto) AS cant_producto FROM producto AS p WHERE p.estado = '1' AND p.estado_delete = '1';";
    $sql2 = "SELECT COUNT(p.idpersona) AS cant_agricultor FROM persona AS p WHERE p.idtipo_persona = '2' AND p.estado = '1' AND p.estado_delete = '1';";
    $sql3 = "SELECT COUNT(p.idpersona) AS cant_trabajador FROM persona AS p WHERE p.idtipo_persona = '2' AND p.estado = '1' AND p.estado_delete = '1';";
    $sql4 = "SELECT SUM(vp.total) AS cant_venta_producto FROM venta_producto AS vp WHERE vp.estado = '1' AND vp.estado_delete = '1';";

    $data1 = ejecutarConsultaSimpleFila($sql); if ($data1['status'] == false) { return $data1; }
    $data2 = ejecutarConsultaSimpleFila($sql2); if ($data2['status'] == false) { return $data2; }
    $data3 = ejecutarConsultaSimpleFila($sql3); if ($data3['status'] == false) { return $data3; }
    $data4 = ejecutarConsultaSimpleFila($sql4); if ($data4['status'] == false) { return $data4; }

    $results = [
      "status" => true,
      "data" => [
        "cant_producto"  => (empty($data1['data']) ? 0 : (empty($data1['data']['cant_producto']) ? 0 : floatval($data1['data']['cant_producto']) ) ),
        "cant_agricultor" => (empty($data2['data']) ? 0 : (empty($data2['data']['cant_agricultor']) ? 0 : floatval($data2['data']['cant_agricultor']) ) ),
        "cant_trabajador"=> (empty($data3['data']) ? 0 : (empty($data3['data']['cant_trabajador']) ? 0 : floatval($data3['data']['cant_trabajador']) ) ),
        "cant_venta_producto"  => (empty($data4['data']) ? 0 : (empty($data4['data']['cant_venta_producto']) ? 0 : floatval($data4['data']['cant_venta_producto'])  ) ),
      ],
      "message"=> 'Todo oka'
    ];
    
    return $results;
  }


}

?>
