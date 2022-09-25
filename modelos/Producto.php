<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Producto
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar( $nombre, $precio_unitario, $descripcion)
  {
    $sql = "SELECT p.nombre, p.estado, p.descripcion,  p.estado, p.estado_delete
		FROM producto p
    WHERE p.nombre='$nombre' ";
    $buscando = ejecutarConsultaArray($sql);
    if ($buscando['status'] == false) { return $buscando; }

    if ( empty($buscando['data']) ) {
      $sql = "INSERT INTO producto (nombre, precio_unitario, descripcion, user_created) 
      VALUES ('$nombre', '$precio_unitario','$descripcion','" . $_SESSION['idusuario'] . "')";
     
      $intertar =  ejecutarConsulta_retornarID($sql); 
      if ($intertar['status'] == false) {  return $intertar; } 

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','".$intertar['data']."','Nuevo producto registrado','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

      return $intertar;

    } else {
      $info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Nombre: </b>'.$value['nombre'].'<br
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .'<br>
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
      return $sw;
    }      
    
  }

  //Implementamos un método para editar registros
  public function editar($idproducto, $nombre, $precio_unitario, $descripcion)
  {
   
    $sql = "UPDATE producto SET 
		
		nombre='$nombre', 
		precio_unitario='$precio_unitario', 
		descripcion='$descripcion', 
		
    user_updated= '" . $_SESSION['idusuario'] . "'

		WHERE idproducto='$idproducto'";

    $editar =  ejecutarConsulta($sql);
    if ( $editar['status'] == false) {return $editar; } 

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','$idproducto','Producto editado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  

    return $editar;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idproducto)
  {
    $sql = "UPDATE producto SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idproducto ='$idproducto'";
    $desactivar= ejecutarConsulta($sql);

    if ($desactivar['status'] == false) {  return $desactivar; }
    
    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','".$idproducto."','Producto desactivado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
    
    return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function activar($idproducto)
  {
    $sql = "UPDATE producto SET estado='1' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar($idproducto)
  {
    $sql = "UPDATE producto SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idproducto ='$idproducto'";
    $eliminar =  ejecutarConsulta($sql);
    if ( $eliminar['status'] == false) {return $eliminar; }  
    
    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','$idproducto','Producto Eliminado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
    
    return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idproducto)
  {
    $data = Array();

    $sql = "SELECT p.idproducto, p.nombre,	p.descripcion, p.precio_unitario, 
    p.estado, um.nombre_medida
		FROM producto p
		WHERE p.idproducto ='$idproducto'";

    $producto = ejecutarConsultaSimpleFila($sql);

    if ($producto['status'] == false) {  return $producto; }

    $data = array(
      'idproducto'      => ( empty($producto['data']['idproducto']) ? '' : $producto['data']['idproducto']),
      
      'nombre'          => ( empty($producto['data']['nombre']) ? '' :decodeCadenaHtml($producto['data']['nombre'])),
      'precio_unitario' => ( empty($producto['data']['precio_unitario']) ? 0 : number_format($producto['data']['precio_unitario'], 2, '.',',') ),
      'descripcion'     => ( empty($producto['data']['descripcion']) ? '' : decodeCadenaHtml($producto['data']['descripcion'])),
      'estado'          => ( empty($producto['data']['estado']) ? '' : $producto['data']['estado']),
      //'nombre_medida'   => ( empty($producto['data']['nombre_medida']) ? '' : $producto['data']['nombre_medida']),
    );

    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];    
  }

  //Implementar un método para listar los registros
  public function tbla_principal() {
    $sql = "SELECT p.idproducto,  p.nombre, p.descripcion,	p.precio_unitario, p.estado    
    FROM producto p
    WHERE  p.estado='1' AND p.estado_delete='1' ORDER BY p.nombre ASC";
    return ejecutarConsulta($sql);
  }
  
  //Seleccionar Trabajador Select2
 
  
  //Seleccionar una ficha tecnica
  
}

?>
