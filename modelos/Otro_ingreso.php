<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Otro_ingreso
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  //$idotro_ingreso,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
  //Implementamos un método para insertar registros
  public function insertar($idpersona, $fecha_i, $forma_pago, $tipo_comprobante, $nro_comprobante, $subtotal, $igv, $val_igv, $tipo_gravada, $precio_parcial, $descripcion, $comprobante)
  {

    $sql = "INSERT INTO otro_ingreso( idpersona, fecha_ingreso, tipo_comprobante, numero_comprobante, forma_de_pago, precio_sin_igv, precio_igv, precio_con_igv, tipo_gravada, descripcion, comprobante) 
    VALUES ('$idpersona', '$fecha_i', '$tipo_comprobante', '$nro_comprobante', '$forma_pago', '$subtotal', '$igv', '$precio_parcial', '$tipo_gravada', '$descripcion', '$comprobante')";
    return ejecutarConsulta($sql);

  }

  //Implementamos un método para editar registros
  public function editar($idotro_ingreso,$idpersona, $fecha_i, $forma_pago, $tipo_comprobante, $nro_comprobante, $subtotal, $igv, $val_igv, $tipo_gravada, $precio_parcial, $descripcion,$comprobante)
  {

    $sql = "UPDATE otro_ingreso SET
    idpersona='$idpersona',
    fecha_ingreso='$fecha_i',
    tipo_comprobante='$tipo_comprobante',
    numero_comprobante='$nro_comprobante',
    forma_de_pago='$forma_pago',
    precio_sin_igv='$subtotal',
    precio_igv='$igv',
    precio_con_igv='$precio_parcial',
    tipo_gravada='$tipo_gravada',
    descripcion='$descripcion',
    comprobante='$comprobante'

		WHERE idotro_ingreso='$idotro_ingreso'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idotro_ingreso) {
    $sql = "UPDATE otro_ingreso SET estado='0' WHERE idotro_ingreso ='$idotro_ingreso'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idotro_ingreso) {
    $sql = "UPDATE otro_ingreso SET estado_delete='0' WHERE idotro_ingreso ='$idotro_ingreso'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idotro_ingreso) {
    $sql = "SELECT oi.idotro_ingreso, oi.idproyecto, oi.idproveedor, oi.ruc, oi.razon_social, oi.direccion, oi.tipo_comprobante, 
    oi.numero_comprobante, oi.forma_de_pago, oi.fecha_i, oi.subtotal, oi.igv, oi.costo_parcial, oi.descripcion, oi.glosa, oi.comprobante, 
    oi.val_igv, oi.tipo_gravada, oi.estado, oi.estado_delete, oi.created_at, oi.updated_at, p.razon_social, p.tipo_documento, p.ruc
    FROM otro_ingreso as oi, proveedor as p
    WHERE oi.idproveedor = p.idproveedor and  oi.idotro_ingreso ='$idotro_ingreso'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal() {
    $sql = "SELECT oi.idotro_ingreso, oi.idpersona, oi.fecha_ingreso, oi.tipo_comprobante, oi.numero_comprobante, oi.forma_de_pago, oi.precio_sin_igv, 
    oi.precio_igv, oi.precio_con_igv, oi.tipo_gravada, oi.descripcion, oi.comprobante, p.nombres,p.numero_documento,p.tipo_documento, p.direccion
    FROM otro_ingreso as oi, persona as p 
    WHERE oi.estado=1 AND oi.estado_delete=1 AND oi.idpersona=p.idpersona";
    return ejecutarConsulta($sql);

  }




  //total
  public function total() {
    $sql = "SELECT SUM(precio_con_igv) as precio_parcial FROM otro_ingreso WHERE estado='1' AND estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Seleccionar un comprobante
  public function ficha_tec($idotro_ingreso) {
    $sql = "SELECT comprobante FROM otro_ingreso WHERE idotro_ingreso='$idotro_ingreso'";
    return ejecutarConsulta($sql);
  }

  public function selecct_produc_o_provee()
  {
    $sql = "SELECT p.idpersona, p.idtipo_persona, p.nombres, p.numero_documento, tp.nombre as tipo FROM persona as p, tipo_persona as tp 
    WHERE p.idtipo_persona = tp.idtipo_persona AND p.idtipo_persona BETWEEN '2' and '3'  AND p.estado_delete =1 AND p.estado=1;";
    return ejecutarConsultaArray($sql);
  }

}

?>
