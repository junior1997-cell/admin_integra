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

    // insertamos al persona
    $sql = "INSERT INTO persona ( idpersona, cargo, login, password,user_created) VALUES ('$trabajador', '$cargo', '$login', '$clave','" . $_SESSION['idpersona'] . "')";
    $data_user = ejecutarConsulta_retornarID($sql); if ($data_user['status'] == false){return $data_user; }

    //add registro en nuestra bitacora
    $sql2 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('persona','" . $data_user['data'] . "','Registrar','" . $_SESSION['idpersona'] . "')";
    $bitacora1 = ejecutarConsulta($sql2); if ( $bitacora1['status'] == false) {return $bitacora1; }

    $num_elementos = 0; $sw = "";

    if ( !empty($permisos) ) {

      while ($num_elementos < count($permisos)) {
        
        $idpersonanew = $data_user['data'];

        $sql_detalle = "INSERT INTO persona_permiso(idpersona, idpermiso, user_created) VALUES('$idpersonanew', '$permisos[$num_elementos]','" . $_SESSION['idpersona'] . "')";

        $sw = ejecutarConsulta_retornarID($sql_detalle);  

        if ( $sw['status'] == false) {return $sw; }

        //add registro en nuestra bitacora
        $sql2 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('persona_permiso','" .  $sw['data'] . "','Registrar permisos','" . $_SESSION['idpersona'] . "')";
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
  public function editar($idpersona, $trabajador,$trabajador_old, $cargo, $login, $clave, $permisos) {
    $trab = "";
    if (empty($trabajador)) {$trab = $trabajador_old;}else{$trab = $trabajador; }
    // var_dump($trab);die();
    $update_user = '[]';
    
    //Eliminamos todos los permisos asignados para volverlos a registrar
    $sqldel = "DELETE FROM persona_permiso WHERE idpersona='$idpersona'";
    $delete =  ejecutarConsulta($sqldel); if ( $delete['status'] == false) {return $delete; }   

    $sql = "UPDATE persona SET 
    idpersona='$trab', cargo='$cargo', login='$login', password='$clave', user_updated= '" . $_SESSION['idpersona'] . "' WHERE idpersona='$idpersona'";
    $update_user = ejecutarConsulta($sql); if ($update_user['status'] == false) {return $update_user; }     
    
    //add registro en nuestra bitacora
    $sql5_1 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('persona', '$idpersona' ,'Editamos los campos del persona','" . $_SESSION['idpersona'] . "')";
    $bitacora5_1 = ejecutarConsulta($sql5_1); if ( $bitacora5_1['status'] == false) {return $bitacora5_1; }  

    $num_elementos = 0; $sw = "";

    if ($permisos != "") {      

      while ($num_elementos < count($permisos)) {

        $sql_detalle = "INSERT INTO persona_permiso(idpersona, idpermiso,user_created) VALUES('$idpersona', '$permisos[$num_elementos]','" . $_SESSION['idpersona'] . "')";

        $sw = ejecutarConsulta_retornarID($sql_detalle);  

        if ( $sw['status'] == false) {return $sw; }

        //add registro en nuestra bitacora
        $sqlsw = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('persona_permiso','" .  $sw['data'] . "','Asigamos nuevos persmisos cuando editamos persona','" . $_SESSION['idpersona'] . "')";
        $bitacorasw = ejecutarConsulta($sqlsw);

        if ( $bitacorasw['status'] == false) {return $bitacorasw; }

        $num_elementos = $num_elementos + 1;

      }

      return $sw;
    
    }

  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idpersona) {
    $sql = "UPDATE persona SET estado='0', user_trash= '" . $_SESSION['idpersona'] . "' WHERE idpersona='$idpersona'";

    $desactivar = ejecutarConsulta($sql);
    
    if ( $desactivar['status'] == false) {return $desactivar; }    

    //add registro en nuestra bitacora
    $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('persona_permiso','$idpersona','Registro desactivado','" . $_SESSION['idpersona'] . "')";
    $bitacorade = ejecutarConsulta($sqlde);

    if ( $bitacorade['status'] == false) {return $bitacorade; }   

    return $desactivar;
  }

  //Implementamos un método para activar :: !!sin usar ::
  public function activar($idpersona) {
    $sql = "UPDATE persona SET estado='1', user_updated= '" . $_SESSION['idpersona'] . "' WHERE idpersona='$idpersona'";

    $activar= ejecutarConsulta($sql);
        
    if ( $activar['status'] == false) {return $activar; }    

    //add registro en nuestra bitacora
    $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('persona_permiso','$idpersona','Registro activado','" . $_SESSION['idpersona'] . "')";
    $bitacorade = ejecutarConsulta($sqlde);

    if ( $bitacorade['status'] == false) {return $bitacorade; }   

    return $activar;
  }

  //Implementamos un método para eliminar persona
  public function eliminar($idpersona) {
    $sql = "UPDATE persona SET estado_delete='0',user_delete= '" . $_SESSION['idpersona'] . "' WHERE idpersona='$idpersona'";

    $eliminar= ejecutarConsulta($sql);
        
    if ( $eliminar['status'] == false) {return $eliminar; }    

    //add registro en nuestra bitacora
    $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('persona_permiso','$idpersona','Registro Eliminado','" . $_SESSION['idpersona'] . "')";
    $bitacorade = ejecutarConsulta($sqlde);

    if ( $bitacorade['status'] == false) {return $bitacorade; }   

    return $eliminar;

  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idpersona) {
    $sql = "SELECT u.idpersona, u.idpersona, u.cargo, u.login, u.password, u.estado, t.nombres FROM persona AS u, trabajador AS t WHERE u.idpersona='$idpersona' AND u.idpersona = t.idpersona;";

    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function listar() {
    $sql = "SELECT u.idpersona, u.last_sesion, t.nombres, t.tipo_documento, t.numero_documento, t.telefono, t.email, u.cargo, u.login, t.imagen_perfil, t.tipo_documento, u.estado
		FROM persona as u, trabajador as t
		WHERE  u.idpersona = t.idpersona  AND u.estado=1 AND u.estado_delete=1 ORDER BY t.nombres ASC;";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los permisos marcados
  public function listarmarcados($idpersona) {
    $sql = "SELECT * FROM persona_permiso WHERE idpersona='$idpersona' ";
    return ejecutarConsulta($sql);
  }

  //Función para verificar el acceso al sistema
  public function verificar($login, $clave) {
    $sql = "SELECT u.idpersona, t.nombres, t.tipo_documento, t.numero_documento, t.telefono, t.email, u.cargo, u.login, t.imagen_perfil, t.tipo_documento
		FROM persona as u, trabajador as t
		WHERE u.login='$login' AND u.password='$clave' AND t.estado=1 and u.estado=1 and u.estado_delete=1 and u.idpersona = t.idpersona;";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Función para verificar el acceso al sistema
  public function ultima_sesion($id) {
    $sql = "UPDATE persona SET last_sesion= current_timestamp() WHERE idpersona = '$id';";
    return ejecutarConsulta($sql);
  }

  //Seleccionar Trabajador Select2
  public function select2_trabajador() {
    $sql = "SELECT t.idpersona, t.nombres, t.numero_documento, t.imagen_perfil
    FROM trabajador as t 
    LEFT JOIN persona as u ON t.idpersona=u.idpersona WHERE t.estado =1 AND t.estado_delete=1 AND u.idpersona IS NULL;";
    return ejecutarConsulta($sql);
  }

  public function mostrar_cargo_trabajador($id_trabajador)
  {
    $sql = "SELECT t.idpersona, ct.nombre as cargo FROM trabajador as t, cargo_trabajador as ct WHERE t.idcargo_trabajador= ct.idcargo_trabajador AND t.idpersona='$id_trabajador';";
    return ejecutarConsultaSimpleFila($sql);
  }
  
}

?>
