<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Ingreso_producto
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  // ::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C O M P R A  ::::::::::::::::::::::::::::::::::::::::: 
  //Implementamos un método para insertar registros
  public function insertar( $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
  $total_compra, $subtotal_compra, $igv_compra,  $idproducto, $unidad_medida, $categoria, $cantidad, $precio_sin_igv, $precio_igv, 
  $precio_con_igv,$precio_venta, $descuento, $tipo_gravada) {

    $sql_1 = "SELECT numero_documento FROM persona WHERE idpersona ='$idproveedor';";
    $proveedor = ejecutarConsultaSimpleFila($sql_1);  if ($proveedor['status'] == false) { return  $proveedor;}

    $ruc = $proveedor['data']['numero_documento'];

    $sql_2 = "SELECT  cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante, cp.igv, cp.total, p.numero_documento, p.tipo_documento,p.nombres
    FROM compra_producto as cp, persona as p 
    WHERE cp.tipo_comprobante ='$tipo_comprobante' AND cp.serie_comprobante = '$serie_comprobante' AND p.numero_documento='$ruc'";
    $compra_existe = ejecutarConsultaArray($sql_2); if ($compra_existe['status'] == false) { return  $compra_existe;}

    if (empty($compra_existe['data']) || $tipo_comprobante == 'Ninguno') {

     
      $sql_3 = "INSERT INTO compra_producto(idpersona, fecha_compra, tipo_comprobante, serie_comprobante, val_igv, subtotal, igv, total,tipo_gravada, descripcion) 
      VALUES ('$idproveedor','$fecha_compra','$tipo_comprobante','$serie_comprobante','$val_igv','$subtotal_compra','$igv_compra','$total_compra','$tipo_gravada','$descripcion')";
      $idventanew = ejecutarConsulta_retornarID($sql_3); if ($idventanew['status'] == false) { return  $idventanew;}

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_producto','".$idventanew['data']."','Nueva compra','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

      $num_elementos = 0;
      $compra_new = "";

      if ( !empty($idventanew['data']) ) {
      
        while ($num_elementos < count($idproducto)) {

          $id = $idventanew['data'];
          $subtotal_producto = (floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos])) - $descuento[$num_elementos];

          $sql_detalle = "INSERT INTO detalle_compra_producto(idcompra_producto, idproducto, unidad_medida, categoria, cantidad, precio_sin_igv, igv, 
          precio_con_igv,precio_venta, descuento, subtotal, user_created) 
          VALUES ('$id','$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]',  '$categoria[$num_elementos]', '$cantidad[$num_elementos]', 
          '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]','$precio_venta[$num_elementos]', '$descuento[$num_elementos]', 
          '$subtotal_producto','" . $_SESSION['idusuario'] . "')";

          $compra_new =  ejecutarConsulta_retornarID($sql_detalle); if ($compra_new['status'] == false) { return  $compra_new;}

          //add registro en nuestra bitacora.
          $sql_bit_d = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('detalle_compra_producto','".$compra_new['data']."','Detalle compra','" . $_SESSION['idusuario'] . "')";
          $bitacora = ejecutarConsulta($sql_bit_d); if ( $bitacora['status'] == false) {return $bitacora; } 

          $num_elementos = $num_elementos + 1;
        }
      }
      return $compra_new;

    } else {

      $info_repetida = ''; 

      foreach ($compra_existe['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b class="font-size-18px text-danger">'.$value['tipo_comprobante'].': </b> <span class="font-size-18px text-danger">'.$value['serie_comprobante'].'</span><br>
          <b>Razón Social: </b>'.$value['razon_social'].'<br>
          <b>'.$value['tipo_documento'].': </b>'.$value['ruc'].'<br>          
          <b>Fecha: </b>'.format_d_m_a($value['fecha_compra']).'<br>
          <b>Total: </b>'.number_format($value['total'], 2, '.', ',').'<br>
          <b>Glosa: </b>'.$value['glosa'].'<br>
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b> 
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );      
    }      
  }

  //Implementamos un método para editar registros
  public function editar($idcompra_producto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, $total_venta, 
  $subtotal_compra, $igv_compra,  $idproducto, $unidad_medida, $categoria, $cantidad, $precio_sin_igv, $precio_igv,  $precio_con_igv, $descuento, $tipo_gravada) {

    // if ( !empty($idcompra_producto) ) {
    //   //Eliminamos todos los permisos asignados para volverlos a registrar
    //   $sqldel = "DELETE FROM detalle_compra WHERE idcompra_producto='$idcompra_producto';";
    //   $delete_compra = ejecutarConsulta($sqldel);
    //   if ($delete_compra['status'] == false) { return $delete_compra; }

    //   $sql = "UPDATE compra_producto SET idproyecto = '$idproyecto', idproveedor = '$idproveedor', fecha_compra = '$fecha_compra',
    //   tipo_comprobante = '$tipo_comprobante', serie_comprobante = '$serie_comprobante', val_igv = '$val_igv', descripcion = '$descripcion',
    //   glosa = '$glosa', total = '$total_venta', subtotal = '$subtotal_compra', igv = '$igv_compra', tipo_gravada = '$tipo_gravada',
    //   estado_detraccion = '$estado_detraccion',user_updated= '" . $_SESSION['idusuario'] . "' WHERE idcompra_producto = '$idcompra_producto'";
    //   $update_compra = ejecutarConsulta($sql); if ($update_compra['status'] == false) { return $update_compra; }

    //   //add registro en nuestra bitacora
    //   $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_producto','$idcompra_producto','Editar compra proyecto','" . $_SESSION['idusuario'] . "')";
    //   $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }

    //   $num_elementos = 0; $detalle_compra = "";

    //   while ($num_elementos < count($idproducto)) {
    //     $subtotal_producto = (floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos])) - $descuento[$num_elementos];
    //     $sql_detalle = "INSERT INTO detalle_compra(idcompra_producto, idproducto, unidad_medida, color, cantidad, precio_sin_igv, igv, precio_con_igv, descuento, subtotal, ficha_tecnica_producto, user_created) 
    //     VALUES ('$idcompra_producto', '$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]', '$nombre_color[$num_elementos]', '$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]', '$descuento[$num_elementos]', '$subtotal_producto', '$ficha_tecnica_producto[$num_elementos]','" . $_SESSION['idusuario'] . "')";
    //     $detalle_compra = ejecutarConsulta_retornarID($sql_detalle); if ($detalle_compra['status'] == false) { return $detalle_compra; }

    //     //add registro en nuestra bitacora.
    //     $sql_bit_d = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('detalle_compra','".$detalle_compra['data']."','Detalle editado compra','" . $_SESSION['idusuario'] . "')";
    //     $bitacora = ejecutarConsulta($sql_bit_d); if ( $bitacora['status'] == false) {return $bitacora; } 

    //     $num_elementos = $num_elementos + 1;
    //   }
    //   return $detalle_compra; 
    // } else { 
    //   return $retorno = ['status'=>false, 'mesage'=>'no hay nada', 'data'=>'sin data', ]; 
    // }
  }

  public function mostrar_compra_para_editar($id_compras_x_proyecto) {

    $sql = "SELECT  cpp.idcompra_producto, cpp.idproyecto, cpp.idproveedor, cpp.fecha_compra, cpp.tipo_comprobante, cpp.serie_comprobante, cpp.val_igv, 
    cpp.descripcion, cpp.glosa, cpp.subtotal, cpp.igv, cpp.total, cpp.estado_detraccion, cpp.estado
    FROM compra_producto as cpp
    WHERE idcompra_producto='$id_compras_x_proyecto';";

    $compra = ejecutarConsultaSimpleFila($sql);
    if ($compra['status'] == false) { return $compra; }

    $sql_2 = "SELECT 	dc.idproducto, dc.ficha_tecnica_producto, dc.cantidad, dc.precio_sin_igv, dc.igv, dc.precio_con_igv,
		dc.descuento,	p.nombre as nombre_producto, p.imagen, dc.unidad_medida, dc.color
		FROM detalle_compra AS dc, producto AS p, unidad_medida AS um, color AS c
		WHERE idcompra_producto='$id_compras_x_proyecto' AND  dc.idproducto=p.idproducto AND p.idcolor = c.idcolor 
    AND p.idunidad_medida = um.idunidad_medida;";

    $producto = ejecutarConsultaArray($sql_2);
    if ($producto['status'] == false) { return $producto;  }

    $results = [
      "idcompra_x_proyecto" => $compra['data']['idcompra_producto'],      
      "idproyecto" => $compra['data']['idproyecto'],
      "idproveedor" => $compra['data']['idproveedor'],
      "fecha_compra" => $compra['data']['fecha_compra'],
      "tipo_comprobante" => $compra['data']['tipo_comprobante'],
      "serie_comprobante" => $compra['data']['serie_comprobante'],
      "val_igv" => $compra['data']['val_igv'],
      "descripcion" => $compra['data']['descripcion'],
      "glosa" => $compra['data']['glosa'],
      "subtotal" => $compra['data']['subtotal'],
      "igv" => $compra['data']['igv'],
      "total" => $compra['data']['total'],
      "estado_detraccion" => $compra['data']['estado_detraccion'],
      "estado" => $compra['data']['estado'],
      "producto" => $producto['data'],
    ];

    return $retorno = ["status" => true, "message" => 'todo oka', "data" => $results] ;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idcompra_producto) {
    $sql = "UPDATE compra_producto SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idcompra_producto='$idcompra_producto'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_producto','".$idcompra_producto."','Compra desactivada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function eliminar($idcompra_producto) {
    $sql = "UPDATE compra_producto SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idcompra_producto='$idcompra_producto'";

		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_producto','$idcompra_producto','Compra Eliminada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idcompra_producto) {
    $sql = "SELECT * FROM compra_producto WHERE idcompra_producto='$idcompra_producto'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal($fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND cp.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND cp.fecha_compra = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND cp.fecha_compra = '$fecha_2'";
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND cp.idpersona = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND cp.tipo_comprobante = '$comprobante'"; } 

    $data = Array();
    $scheme_host=  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_integra/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

    $sql = "SELECT cp.idcompra_producto, cp.idpersona,cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante,cp.total, cp.tipo_gravada,cp.descripcion, p.nombres
    FROM compra_producto as cp, persona as p  
    WHERE cp.idpersona = p.idpersona AND cp.estado= '1' AND cp.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha
		ORDER BY cp.fecha_compra DESC ";

    return ejecutarConsultaArray($sql);

  }

  //Implementar un método para listar los registros x proveedor
  public function listar_compraxporvee() {
    // $idproyecto=2;
    $sql = "SELECT  COUNT(cp.idcompra_producto) as cantidad, SUM(cp.total) as total, 
    cp.idpersona , p.nombres as razon_social, p.celular
		FROM compra_producto as cp, persona as p 
		WHERE  cp.idpersona=p.idpersona AND cp.estado = '1' AND cp.estado_delete = '1'
    GROUP BY cp.idpersona ORDER BY p.nombres ASC";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros x proveedor
  public function listar_detalle_comprax_provee($idproveedor) {

    $sql = "SELECT cp.idcompra_producto, cp.idpersona,cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante,cp.total,cp.descripcion
    FROM compra_producto as cp WHERE cp.idpersona = '$idproveedor' AND cp.estado= '1' AND cp.estado_delete = '1'";

    return ejecutarConsulta($sql);
  }

  //mostrar detalles uno a uno de la factura
  public function ver_compra($idcompra_producto) {

    $sql = "SELECT cpp.idcompra_producto as idcompra_producto, 
		cpp.idproyecto , 
		cpp.idproveedor , 
		p.razon_social , p.tipo_documento, p.ruc, p.direccion, p.telefono, 
		cpp.fecha_compra , 
		cpp.tipo_comprobante , 
		cpp.serie_comprobante , 
    cpp.val_igv,
		cpp.descripcion , 
    cpp.glosa,
		cpp.subtotal, 
		cpp.igv , 
		cpp.total ,
    cpp.tipo_gravada ,
		cpp.estado 
		FROM compra_producto as cpp, proveedor as p 
		WHERE idcompra_producto='$idcompra_producto'  AND cpp.idproveedor = p.idproveedor";

    return ejecutarConsultaSimpleFila($sql);
  }

  //lismatamos los detalles
  public function ver_detalle_compra($id_compra) {

    $sql = "SELECT 
		dp.idproducto as idproducto,
		dp.ficha_tecnica_producto  as ficha_tecnica_old, p.ficha_tecnica as ficha_tecnica_new,
		dp.cantidad ,
    dp.unidad_medida, dp.color,
		dp.precio_sin_igv ,
    dp.igv ,
    dp.precio_con_igv ,
		dp.descuento ,
    dp.subtotal ,
		p.nombre as nombre, p.imagen, um.abreviacion
		FROM detalle_compra  dp, producto as p, unidad_medida as um
		WHERE p.idunidad_medida = um.idunidad_medida AND idcompra_producto='$id_compra' AND  dp.idproducto=p.idproducto";

    return ejecutarConsulta($sql);
  }

  // ::::::::::::::::::::::::::::::::::::::::: S I N C R O N I Z A R  ::::::::::::::::::::::::::::::::::::::::: 
  public function sincronizar_comprobante() {
    $sql = "SELECT idcompra_producto, comprobante FROM compra_producto WHERE comprobante != 'null' AND comprobante != '';";
    $comprobantes = ejecutarConsultaArray($sql);
    if ($comprobantes == false) {  return $comprobantes; }

    foreach ($comprobantes['data'] as $key => $value) {
      $id_compra = $value['idcompra_producto']; $comprobante = $value['comprobante'];
      $sql2 = "INSERT INTO factura_compra_insumo ( idcompra_producto, comprobante ) VALUES ( '$id_compra', '$comprobante')";
      $factura_compra = ejecutarConsulta($sql2);
      if ($factura_compra == false) {  return $factura_compra; }
    }

    $sql3 = "SELECT	idcompra_producto, comprobante FROM factura_compra_insumo ;";
    $factura_compras = ejecutarConsultaArray($sql3);
    if ($factura_compras == false) {  return $factura_compras; }

    return $retorno = ['status'=>true, 'message'=>'todo oka', 'data'=>['comprobante'=>$comprobantes['data'],'factura_compras'=>$factura_compras['data'],], ];
  } 
   
}

?>
