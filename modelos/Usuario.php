<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Usuario
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($trabajador, $cargo, $login, $clave, $permisos) {

    // insertamos al usuario
    $sql = "INSERT INTO usuario ( idtrabajador, cargo, login, password,user_created) VALUES ('$trabajador', '$cargo', '$login', '$clave','" . $_SESSION['idusuario'] . "')";
    $data_user = ejecutarConsulta_retornarID($sql); if ($data_user['status'] == false){return $data_user; }

    //add registro en nuestra bitacora
    $sql2 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario','" . $data_user['data'] . "','Registrar','" . $_SESSION['idusuario'] . "')";
    $bitacora1 = ejecutarConsulta($sql2); if ( $bitacora1['status'] == false) {return $bitacora1; }

    $num_elementos = 0; $sw = "";

    if ( !empty($permisos) ) {

      while ($num_elementos < count($permisos)) {
        
        $idusuarionew = $data_user['data'];

        $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso, user_created) VALUES('$idusuarionew', '$permisos[$num_elementos]','" . $_SESSION['idusuario'] . "')";

        $sw = ejecutarConsulta_retornarID($sql_detalle);  

        if ( $sw['status'] == false) {return $sw; }

        //add registro en nuestra bitacora
        $sql2 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','" .  $sw['data'] . "','Registrar permisos','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql2);

        if ( $bitacora['status'] == false) {return $bitacora; }

        $num_elementos++;

      }

      return $sw;

    }else{

      return $data_user;

    }

  }

  //Implementamos un método para editar registros
  public function editar($idusuario, $trabajador,$trabajador_old, $cargo, $login, $clave, $permisos) {
    $trab = "";
    if (empty($trabajador)) {$trab = $trabajador_old;}else{$trab = $trabajador; }
    // var_dump($trab);die();
    $update_user = '[]';
    
    //Eliminamos todos los permisos asignados para volverlos a registrar
    $sqldel = "DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
    $delete =  ejecutarConsulta($sqldel); if ( $delete['status'] == false) {return $delete; }   

    $sql = "UPDATE usuario SET 
    idtrabajador='$trab', cargo='$cargo', login='$login', password='$clave', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idusuario='$idusuario'";
    $update_user = ejecutarConsulta($sql); if ($update_user['status'] == false) {return $update_user; }     
    
    //add registro en nuestra bitacora
    $sql5_1 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario', '$idusuario' ,'Editamos los campos del usuario','" . $_SESSION['idusuario'] . "')";
    $bitacora5_1 = ejecutarConsulta($sql5_1); if ( $bitacora5_1['status'] == false) {return $bitacora5_1; }  

    $num_elementos = 0; $sw = "";

    if ($permisos != "") {      

      while ($num_elementos < count($permisos)) {

        $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso,user_created) VALUES('$idusuario', '$permisos[$num_elementos]','" . $_SESSION['idusuario'] . "')";

        $sw = ejecutarConsulta_retornarID($sql_detalle);  

        if ( $sw['status'] == false) {return $sw; }

        //add registro en nuestra bitacora
        $sqlsw = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','" .  $sw['data'] . "','Asigamos nuevos persmisos cuando editamos usuario','" . $_SESSION['idusuario'] . "')";
        $bitacorasw = ejecutarConsulta($sqlsw);

        if ( $bitacorasw['status'] == false) {return $bitacorasw; }

        $num_elementos = $num_elementos + 1;

      }

      return $sw;
    
    }

  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idusuario) {
    $sql = "UPDATE usuario SET estado='0', user_trash= '" . $_SESSION['idusuario'] . "' WHERE idusuario='$idusuario'";

    $desactivar = ejecutarConsulta($sql);
    
    if ( $desactivar['status'] == false) {return $desactivar; }    

    //add registro en nuestra bitacora
    $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','$idusuario','Registro desactivado','" . $_SESSION['idusuario'] . "')";
    $bitacorade = ejecutarConsulta($sqlde);

    if ( $bitacorade['status'] == false) {return $bitacorade; }   

    return $desactivar;
  }

  //Implementamos un método para activar :: !!sin usar ::
  public function activar($idusuario) {
    $sql = "UPDATE usuario SET estado='1', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idusuario='$idusuario'";

    $activar= ejecutarConsulta($sql);
        
    if ( $activar['status'] == false) {return $activar; }    

    //add registro en nuestra bitacora
    $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','$idusuario','Registro activado','" . $_SESSION['idusuario'] . "')";
    $bitacorade = ejecutarConsulta($sqlde);

    if ( $bitacorade['status'] == false) {return $bitacorade; }   

    return $activar;
  }

  //Implementamos un método para eliminar usuario
  public function eliminar($idusuario) {
    $sql = "UPDATE usuario SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idusuario='$idusuario'";

    $eliminar= ejecutarConsulta($sql);
        
    if ( $eliminar['status'] == false) {return $eliminar; }    

    //add registro en nuestra bitacora
    $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','$idusuario','Registro Eliminado','" . $_SESSION['idusuario'] . "')";
    $bitacorade = ejecutarConsulta($sqlde);

    if ( $bitacorade['status'] == false) {return $bitacorade; }   

    return $eliminar;

  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idusuario) {
    $sql = "SELECT u.idusuario, u.idtrabajador, u.cargo, u.login, u.password, u.estado, t.nombres FROM usuario AS u, trabajador AS t WHERE u.idusuario='$idusuario' AND u.idtrabajador = t.idtrabajador;";

    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function listar() {
    $sql = "SELECT u.idusuario, u.last_sesion, t.nombres, t.tipo_documento, t.numero_documento, t.telefono, t.email, u.cargo, u.login, t.imagen_perfil, t.tipo_documento, u.estado
		FROM usuario as u, trabajador as t
		WHERE  u.idtrabajador = t.idtrabajador  AND u.estado=1 AND u.estado_delete=1 ORDER BY t.nombres ASC;";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los permisos marcados
  public function listarmarcados($idusuario) {
    $sql = "SELECT * FROM usuario_permiso WHERE idusuario='$idusuario' ";
    return ejecutarConsulta($sql);
  }

  //Función para verificar el acceso al sistema
  public function verificar($login, $clave) {
    $sql = "SELECT u.idusuario, t.nombres, t.tipo_documento, t.numero_documento, t.telefono, t.email, u.cargo, u.login, t.imagen_perfil, t.tipo_documento
		FROM usuario as u, trabajador as t
		WHERE u.login='$login' AND u.password='$clave' AND t.estado=1 and u.estado=1 and u.estado_delete=1 and u.idtrabajador = t.idtrabajador;";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Función para verificar el acceso al sistema
  public function ultima_sesion($id) {
    $sql = "UPDATE usuario SET last_sesion= current_timestamp() WHERE idusuario = '$id';";
    return ejecutarConsulta($sql);
  }

  //Seleccionar Trabajador Select2
  public function select2_trabajador() {
    $sql = "SELECT t.idtrabajador, t.nombres, t.numero_documento, t.imagen_perfil
    FROM trabajador as t 
    LEFT JOIN usuario as u ON t.idtrabajador=u.idtrabajador WHERE t.estado =1 AND t.estado_delete=1 AND u.idtrabajador IS NULL;";
    return ejecutarConsulta($sql);
  }

  public function mostrar_cargo_trabajador($id_trabajador)
  {
    $sql = "SELECT t.idtrabajador, ct.nombre as cargo FROM trabajador as t, cargo_trabajador as ct WHERE t.idcargo_trabajador= ct.idcargo_trabajador AND t.idtrabajador='$id_trabajador';";
    return ejecutarConsultaSimpleFila($sql);
  }
  
}

?>
