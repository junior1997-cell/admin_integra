<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion_v2.php";

  class PagoTrabajador
  {
    //Implementamos nuestro constructor
    public function __construct()
    {
    }

    public function insertar( $idtrabajador,$fecha_pago, $monto, $descripcion, $imagen1) {
      $sw = Array();
      // var_dump($idcargo_trabajador,$nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad,  $email, $banco, $cta_bancaria,  $cci,  $titular_cuenta, $ruc, $imagen1); die();
      
      $sql_0 = "SELECT pt.fecha_pago, pt.monto, pt.descripcion, pt.comprobante, t.nombres as nombre_trabajador, t.sueldo_mensual, tp.estado, tp.estado_delete 
      FROM pago_trabajador as pt, trabajador as t
      WHERE t.idtrabajador = pt.idtrabajador AND pt.idtrabajador = $idtrabajador";

      $existe = ejecutarConsultaArray($sql_0); if ($existe['status'] == false) { return $existe;}
      
      if ( empty($existe['data']) ) {

        $sql="INSERT INTO pago_trabajador (idtrabajador, fecha_pago, monto, descripcion, comprobante, user_created)
        VALUES ( '$idtrabajador','$fecha_pago','$monto', '$descripcion', '$imagen1', '" . $_SESSION['idusuario'] . "')";
        $new_trabajador = ejecutarConsulta_retornarID($sql);

        if ($new_trabajador['status'] == false) { return $new_trabajador;}

        //add registro en nuestra bitacora
        $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador','".$new_trabajador['data']."','Registro Nuevo Trabajador','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
        
        $sw = array( 'status' => true, 'message' => 'noduplicado', 'data' => $new_trabajador['data'], 'id_tabla' =>$new_trabajador['id_tabla'] );

      } else {
        $info_repetida = ''; 

        foreach ($existe['data'] as $key => $value) {
          $info_repetida .= '<li class="text-left font-size-13px">
            <span class="font-size-15px text-danger"><b>Nombre: </b>'.$value['nombre_trabajador'].'</span><br>
            <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
            <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
            <hr class="m-t-2px m-b-2px">
          </li>'; 
        }
        $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
      }      
      
      return $sw;        
    }

    public function editar($idpago_trabajador, $idtrabajador, $fecha_pago, $monto, $descripcion, $imagen1) {

      $sql = "UPDATE pago_trabajador SET 

      idtrabajador = '$idtrabajador',
      fecha_pago = '$fecha_pago',
      monto = '$monto',
      descripcion = '$descripcion',
      comprobante = '$imagen1',
      
      user_updated= '" . $_SESSION['idusuario'] . "'
      WHERE idpago_trabajador='$idpago_trabajador'";
      
      $editar =  ejecutarConsulta($sql);
      if ( $editar['status'] == false) {return $editar; } 

      //add registro en nuestra bitacora
      $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_trabajador','".$idpago_trabajador."','Editamos el registro Trabajador','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
      
      return $editar;      
    }

    public function desactivar($idpago_trabajador) {
      $sql="UPDATE trabajador SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idpago_trabajador='$idpago_trabajador'";
      $desactivar =  ejecutarConsulta($sql);

      if ( $desactivar['status'] == false) {return $desactivar; }  

      //add registro en nuestra bitacora
      $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_trabajador','.$idpago_trabajador.','Desativar el registro Trabajador','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

      return $desactivar;
    }

    public function eliminar($idpago_trabajador) {
      $sql="UPDATE trabajador SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idpago_trabajador='$idpago_trabajador'";
      $eliminar =  ejecutarConsulta($sql);
      
      if ( $eliminar['status'] == false) {return $eliminar; }  

      //add registro en nuestra bitacora
      $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_trabajador','.$idpago_trabajador.','Eliminar registro Trabajador','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

      return $eliminar;
    }

    public function mostrar($idpago_trabajador) {
      $sql="SELECT * FROM pago_trabajador WHERE idpago_trabajador='$idpago_trabajador'";
      return ejecutarConsultaSimpleFila($sql);

    }

    public function verdatos($idtrabajador) {
      $sql=" SELECT t.idtrabajador, t.idcargo_trabajador, t.idbancos, ct.nombre as cargo,b.nombre as banco, t.nombres, t.tipo_documento, 
      t.numero_documento, t.ruc, t.fecha_nacimiento, t.edad, t.cuenta_bancaria, t.cci, t.titular_cuenta, t.sueldo_mensual, t.sueldo_diario, 
      t.direccion, t.telefono, t.email, t.imagen_perfil, t.estado, b.alias, b.formato_cta,b.formato_cci,b.icono 
      FROM trabajador as t, cargo_trabajador as ct, bancos as b 
      WHERE t.idcargo_trabajador= ct.idcargo_trabajador AND t.idbancos=b.idbancos  AND t.idtrabajador='$idtrabajador' ";
      return ejecutarConsultaSimpleFila($sql);

    }

    public function tbla_principal() {
      
      $sql="SELECT pt.idpago_trabajador, pt.fecha_pago, pt.monto, pt.descripcion, pt.comprobante, t.idtrabajador, 
      t.nombres as nombre_trabajador, t.numero_documento, t.sueldo_mensual, pt.estado
      FROM pago_trabajador as pt, trabajador as t
      WHERE pt.idtrabajador= t.idtrabajador AND pt.estado =1 AND pt.estado_delete=1 ORDER BY  t.nombres ASC ;";

      $trabajdor = ejecutarConsultaArray($sql); if ($trabajdor['status'] == false) { return  $trabajdor;}

      return $trabajdor;
      // var_dump($trabajdor);die();

    }

    public function obtenerImg($idtrabajador) {

      $sql = "SELECT imagen_perfil FROM trabajador WHERE idtrabajador='$idtrabajador'";

      return ejecutarConsultaSimpleFila($sql);
    }

    public function formato_banco($idbanco){
      $sql="SELECT nombre, formato_cta, formato_cci, formato_detracciones FROM bancos WHERE estado='1' AND idbancos = '$idbanco';";
      return ejecutarConsultaSimpleFila($sql);		
    }

    /* =========================== S E C C I O N   R E C U P E R A R   B A N C O S =========================== */

    public function recuperar_banco(){
      $sql="SELECT idtrabajador, idbancos, cuenta_bancaria_format, cci_format FROM trabajador;";
      $bancos_old = ejecutarConsultaArray($sql);
      if ($bancos_old['status'] == false) { return $bancos_old;}	
      
      $bancos_new = [];
      foreach ($bancos_old['data'] as $key => $value) {
        $id = $value['idtrabajador']; 
        $idbancos = $value['idbancos']; 
        $cuenta_bancaria_format = $value['cuenta_bancaria_format']; 
        $cci_format = $value['cci_format'];

        $sql2="INSERT INTO cuenta_banco_trabajador( idtrabajador, idbancos, cuenta_bancaria, cci, banco_seleccionado) 
        VALUES ('$id','$idbancos','$cuenta_bancaria_format','$cci_format', '1');";
        $bancos_new = ejecutarConsulta($sql2);
        if ($bancos_new['status'] == false) { return $bancos_new;}
      } 
      
      return $bancos_new;
    }

  }

?>
