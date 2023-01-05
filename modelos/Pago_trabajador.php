<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion_v2.php";

  class PagoTrabajador
  {
    //Implementamos nuestro constructor
    public function __construct()
    {
    }
    public function insertar_mes_pago($idpersona,$nombres,$mes,$anio)
    {

      $sql="SELECT idmes_pago_trabajador, idpersona, mes_nombre, anio,estado,estado_delete 
      FROM mes_pago_trabajador 
      WHERE idpersona='$idpersona' AND mes_nombre='$mes' AND anio='$anio' ";

      $buscando = ejecutarConsultaArray($sql); if ($buscando['status'] == false) { return $buscando; }
  
      if ( empty($buscando['data']) ) {
        $sql="INSERT INTO mes_pago_trabajador (idpersona,mes_nombre,anio)
        VALUES ('$idpersona','$mes','$anio')";
        return ejecutarConsulta($sql);
      } else {
        $info_repetida = ''; 
  
        foreach ($buscando['data'] as $key => $value) {
          $info_repetida .= '<li class="text-left font-size-13px">
            <b>Nombre: </b>'.$nombres.'<br>
            <b>Descripción: </b>'. $value['mes_nombre'] . ' del '.$value['anio'].'<br>
            <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .'<br>
            <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
            <hr class="m-t-2px m-b-2px">
          </li>'; 
        }
        $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
        return $sw;
      }   


    }
    public function editar_mes_pago($idmes_pago_trabajador,$idpersona,$mes,$anio)
    {

      $sql = "UPDATE mes_pago_trabajador SET idpersona='$idpersona',mes_nombre='$mes',anio='$anio' 
      WHERE idmes_pago_trabajador='$idmes_pago_trabajador'";
      return ejecutarConsulta($sql);
    }

    public function insertar( $idtrabajador,$fecha_pago, $monto, $descripcion, $comprobante) {
      //var_dump($idtrabajador,$fecha_pago, $monto, $descripcion, $imagen1);die();
      $sw = Array();
      // var_dump($idcargo_trabajador,$nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad,  $email, $banco, $cta_bancaria,  $cci,  $titular_cuenta, $ruc, $imagen1); die();
      
      $sql_0 = "SELECT pt.fecha_pago, pt.monto as monto_pago, pt.descripcion, pt.comprobante, t.idtrabajador, ct.nombre as cargo,
      t.nombres as nombre_trabajador, t.numero_documento, t.sueldo_mensual, t.imagen_perfil, t.tipo_documento, t.sueldo_diario, pt.estado, pt.estado_delete 
      FROM pago_trabajador as pt, trabajador as t, cargo_trabajador as ct
      WHERE pt.idtrabajador= t.idtrabajador AND t.idcargo_trabajador = ct.idcargo_trabajador  AND pt.idtrabajador='$idtrabajador' AND pt.fecha_pago = '$fecha_pago' AND pt.monto = '$monto' ";

      $existe = ejecutarConsultaArray($sql_0); if ($existe['status'] == false) { return $existe;}
      
      if ( empty($existe['data']) ) {

        $sql="INSERT INTO pago_trabajador (idtrabajador, fecha_pago, monto, descripcion, comprobante, user_created)
        VALUES ( '$idtrabajador','$fecha_pago','$monto', '$descripcion', '$comprobante', '" . $_SESSION['idusuario'] . "')";
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

    public function editar($idpago_trabajador, $idtrabajador, $fecha_pago, $monto, $descripcion, $comprobante) {

      $sql = "UPDATE pago_trabajador SET 

      idpago_trabajador = '$idpago_trabajador',
      idtrabajador = '$idtrabajador',      
      fecha_pago = '$fecha_pago',
      monto = '$monto',
      descripcion = '$descripcion',
      comprobante = '$comprobante',
      
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
    //datos trabajador
    public function datos_trabajador($idtrabajador)
    {
      $sql = "SELECT p.idpersona, p.idtipo_persona, p.idbancos, p.idcargo_trabajador, p.nombres, p.tipo_documento, p.numero_documento, 
      p.fecha_nacimiento, p.edad, p.celular, p.direccion, p.correo, p.cuenta_bancaria, p.cci, 
      p.titular_cuenta, p.es_socio, p.sueldo_mensual, p.sueldo_diario, p.foto_perfil, ct.nombre as cargo,b.nombre as banco 
      FROM persona as p , cargo_trabajador as ct, bancos as b
      WHERE p.idcargo_trabajador = ct.idcargo_trabajador AND p.idbancos=b.idbancos AND p.idpersona='$idtrabajador';";
      return ejecutarConsultaSimpleFila($sql);
    }
    // Ver pagos trabajador
    public function verdatos($idpago_trabajador) {
      $sql=" SELECT pt.idpago_trabajador, pt.fecha_pago, pt.monto as monto_pago, pt.descripcion, pt.comprobante, t.idtrabajador, ct.nombre as cargo,
      t.nombres as nombre_trabajador, t.numero_documento, t.sueldo_mensual, t.imagen_perfil, t.tipo_documento, t.sueldo_diario, pt.estado
      FROM pago_trabajador as pt, trabajador as t, cargo_trabajador as ct
      WHERE pt.idtrabajador= t.idtrabajador AND t.idcargo_trabajador = ct.idcargo_trabajador  AND pt.idpago_trabajador='$idpago_trabajador' ";
      return ejecutarConsultaSimpleFila($sql);

    }

    public function tbla_mes_pago($idpersona) {
      
      $sql="SELECT idmes_pago_trabajador, mes_nombre, anio FROM mes_pago_trabajador WHERE idpersona='$idpersona'  AND estado=1 AND estado_delete =1";

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
