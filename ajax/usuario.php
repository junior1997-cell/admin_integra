<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  switch ($_GET["op"]) {

    case 'verificar':

      require_once "../modelos/Usuario.php";
      $usuario = new Usuario(); 

      $logina = $_POST['logina'];
      $clavea = $_POST['clavea'];

      //Hash SHA256 en la contraseña
      $clavehash = hash("SHA256", $clavea);

      $rspta = $usuario->verificar($logina, $clavehash);   //$fetch = $rspta->fetch_object();

      if ( $rspta['status'] == true ) {
        if ( !empty($rspta['data']) ) {

          // ultima sesion
          $ultima_sesion = $usuario->ultima_sesion($rspta['data']['idusuario']);

          //Declaramos las variables de sesión
          $_SESSION['idusuario'] = $rspta['data']['idusuario'];
          $_SESSION['nombre'] = $rspta['data']['nombres'];
          $_SESSION['imagen'] = $rspta['data']['foto_perfil'];
          $_SESSION['login'] = $rspta['data']['login'];
          $_SESSION['cargo'] = $rspta['data']['cargo'];
          $_SESSION['tipo_documento'] = $rspta['data']['tipo_documento'];
          $_SESSION['num_documento'] = $rspta['data']['numero_documento'];
          $_SESSION['telefono'] = $rspta['data']['celular'];
          $_SESSION['email'] = $rspta['data']['correo'];

          //Obtenemos los permisos del usuario
          $marcados = $usuario->listarmarcados($rspta['data']['idusuario']);
          
          //Declaramos el array para almacenar todos los permisos marcados
          $valores = [];

          if ($rspta['status']) {
            //Almacenamos los permisos marcados en el array
            foreach ($marcados['data'] as $key => $value) {
              array_push($valores, $value['idpermiso']);
            }
            echo json_encode($rspta);
          }else{
            echo json_encode($marcados);
          }       

          //Determinamos los accesos del usuario
          in_array(1, $valores) ? ($_SESSION['escritorio'] = 1)     : ($_SESSION['escritorio'] = 0);
          in_array(2, $valores) ? ($_SESSION['acceso'] = 1)         : ($_SESSION['acceso'] = 0);
          in_array(3, $valores) ? ($_SESSION['recurso'] = 1)        : ($_SESSION['recurso'] = 0);   
          in_array(4, $valores) ? ($_SESSION['papelera'] = 1)       : ($_SESSION['papelera'] = 0);
          
          // LOGISTICA Y ADQUISICIONES
          in_array(5, $valores) ? ($_SESSION['almacen_abono'] = 1) : ($_SESSION['almacen_abono'] = 0);
          in_array(6, $valores) ? ($_SESSION['venta_abono'] = 1)   : ($_SESSION['venta_abono'] = 0);
          in_array(7, $valores) ? ($_SESSION['compra_grano'] = 1)  : ($_SESSION['compra_grano'] = 0);
          
          // CONTABLE Y FINANCIERO
          in_array(8, $valores) ? ($_SESSION['pago_trabajador'] = 1): ($_SESSION['pago_trabajador'] = 0);         
          in_array(9, $valores) ? ($_SESSION['otro_ingreso'] = 1)   : ($_SESSION['otro_ingreso'] = 0);

        } else {
          echo json_encode($rspta, true);
        }
      }else{
        
        echo json_encode($rspta, true);
      }
      
    break;
    
    case 'salir':
      //Limpiamos las variables de sesión
      session_unset();
      //Destruìmos la sesión
      session_destroy();
      //Redireccionamos al login
      header("Location: index.php?file=".(isset($_GET["file"]) ? $_GET["file"] : ""));
    break;

    // default: 
    //   $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
    // break;
    
  }
 
  require_once "../modelos/Usuario.php";
  require_once "../modelos/Permiso.php";
  require_once "../modelos/Trabajador.php";      

  $usuario = new Usuario();  
  $permisos = new Permiso();
  $alltrabajador = new Trabajador();

  // ::::::::::::::::::::::::::::::::: D A T O S   U S U A R I O S :::::::::::::::::::::::::::::
  $idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
  $trabajador = isset($_POST["trabajador"]) ? limpiarCadena($_POST["trabajador"]) : "";
  $trabajador_old = isset($_POST["trabajador_old"]) ? limpiarCadena($_POST["trabajador_old"]) : "";
  $cargo = isset($_POST["cargo"]) ? limpiarCadena($_POST["cargo"]) : "";
  $login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
  $clave = isset($_POST["password"]) ? limpiarCadena($_POST["password"]) : "";
  $clave_old = isset($_POST["password-old"]) ? limpiarCadena($_POST["password-old"]) : "";
  $permiso = isset($_POST['permiso']) ? $_POST['permiso'] : "";

  // ::::::::::::::::::::::::::::::::: D A T O S   T R A B A J A D O R :::::::::::::::::::::::::::::
  $idtrabajador	  	= isset($_POST["idtrabajador_trab"])? limpiarCadena($_POST["idtrabajador_trab"]):"";
  $nombre 		      = isset($_POST["nombre_trab"])? limpiarCadena($_POST["nombre_trab"]):"";
  $tipo_documento 	= isset($_POST["tipo_documento_trab"])? limpiarCadena($_POST["tipo_documento_trab"]):"";
  $num_documento  	= isset($_POST["num_documento_trab"])? limpiarCadena($_POST["num_documento_trab"]):"";
  $direccion		    = isset($_POST["direccion_trab"])? limpiarCadena($_POST["direccion_trab"]):"";
  $telefono		      = isset($_POST["telefono_trab"])? limpiarCadena($_POST["telefono_trab"]):"";
  $nacimiento		    = isset($_POST["nacimiento_trab"])? limpiarCadena($_POST["nacimiento_trab"]):"";
  $edad		          = isset($_POST["edad_trab"])? limpiarCadena($_POST["edad_trab"]):"";      
  $email			      = isset($_POST["email_trab"])? limpiarCadena($_POST["email_trab"]):"";
  $banco            = isset($_POST["banco_trab"])? $_POST["banco_trab"] :"";     
  $cta_bancaria		  = isset($_POST["cta_bancaria_trab"])?$_POST["cta_bancaria_trab"]:"";
  $cta_bancaria_format= isset($_POST["cta_bancaria_trab"])?$_POST["cta_bancaria_trab"]:"";
  $cci	          	= isset($_POST["cci_trab"])?$_POST["cci_trab"]:"";
  $cci_format      	= isset($_POST["cci_trab"])? $_POST["cci_trab"]:"";
  $titular_cuenta		= isset($_POST["titular_cuenta_trab"])? limpiarCadena($_POST["titular_cuenta_trab"]):"";
  $ruc	          	= isset($_POST["ruc_trab"])? limpiarCadena($_POST["ruc_trab"]):"";
  $idcargo_trabajador = isset($_POST["cargo_trabajador_trab"])? limpiarCadena($_POST["cargo_trabajador_trab"]):"";
  $sueldo_mensual   = isset($_POST["sueldo_mensual_trab"])? limpiarCadena($_POST["sueldo_mensual_trab"]):"";
  $sueldo_diario    = isset($_POST["sueldo_diario_trab"])? limpiarCadena($_POST["sueldo_diario_trab"]):"";

  $imagen1			    = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";

  switch ($_GET["op"]) {

    case 'guardar_y_editar_usuario':

      $clavehash = "";

      if (!empty($clave)) {
        //Hash SHA256 en la contraseña
        $clavehash = hash("SHA256", $clave);
      } else {
        if (!empty($clave_old)) {
          // enviamos la contraseña antigua
          $clavehash = $clave_old;
        } else {
          //Hash SHA256 en la contraseña
          $clavehash = hash("SHA256", "123456");
        }
      }

      if (empty($idusuario)) {

        $rspta = $usuario->insertar($trabajador, $cargo, $login, $clavehash, $permiso);

        echo json_encode($rspta, true);

      } else {

        $rspta = $usuario->editar($idusuario, $trabajador,$trabajador_old, $cargo, $login, $clavehash, $permiso);

        echo json_encode($rspta, true);
      }
    break;

    case 'desactivar':

      $rspta = $usuario->desactivar($_GET["id_tabla"]);

      echo json_encode($rspta, true);

    break;

    case 'activar':

      $rspta = $usuario->activar($_GET["id_tabla"]);

      echo json_encode($rspta, true);

    break;

    case 'eliminar':

      $rspta = $usuario->eliminar($_GET["id_tabla"]);

      echo json_encode($rspta, true);

    break;

    case 'mostrar':

      $rspta = $usuario->mostrar($idusuario);
      //Codificar el resultado utilizando json
      echo json_encode($rspta, true);

    break;
    // $sql = "SELECT u.idusuario, u.last_sesion, p.nombres, p.tipo_documento, p.numero_documento, p.celular, 
    // p.correo, u.cargo, u.login, p.foto_perfil, p.tipo_documento, u.estado


    case 'tbla_principal':

      $rspta = $usuario->listar();
          
      //Vamos a declarar un array
      $data = [];  
      $imagen_error = "this.src='../dist/svg/user_default.svg'"; $cont=1;
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      if ($rspta['status']) {
        foreach ($rspta['data'] as $key => $value) {
          $data[] = [
            "0"=>$cont++,
            "1" => $value['estado'] ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $value['idusuario'] . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ($value['cargo']=='Administrador' ? ' <button class="btn btn-danger btn-sm disabled" data-toggle="tooltip" data-original-title="El administrador no se puede eliminar."><i class="fas fa-skull-crossbones"></i> </button>' : ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $value['idusuario'] .', \''.encodeCadenaHtml($value['nombres']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>' ) :
                '<button class="btn btn-warning  btn-sm" onclick="mostrar(' . $value['idusuario'] . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' . 
                ' <button class="btn btn-primary  btn-sm" onclick="activar(' . $value['idusuario'] . ')" data-toggle="tooltip" data-original-title="Recuperar"><i class="fa fa-check"></i></button>',
            "2" => '<div class="user-block">'. 
              '<img class="img-circle" src="../dist/docs/persona/perfil/' . $value['foto_perfil'] . '" alt="User Image" onerror="' . $imagen_error . '">'.
              '<span class="username"><p class="text-primary m-b-02rem" >' . $value['nombres'] . '</p></span>'. 
              '<span class="description"> - ' . $value['tipo_documento'] .  ': ' . $value['numero_documento'] . ' </span>'.
            '</div>',
            "3" => $value['celular'],
            "4" => $value['login'],
            "5" => $value['cargo'],
            "6" => nombre_dia_semana( date("Y-m-d", strtotime($value['last_sesion'])) ) .', <br>'. date("d/m/Y", strtotime($value['last_sesion'])) .' - '. date("g:i a", strtotime($value['last_sesion'])) ,
            "7" => ($value['estado'] ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
          ];
        }
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
          "data" => $data,
        ];
        echo json_encode($results, true);
      } else {
        echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
      }

    break;

    case 'permisos':
      //Obtenemos todos los permisos de la tabla permisos      
      $rspta = $permisos->listar();

      if ( $rspta['status'] ) {

        //Obtener los permisos asignados al usuario
        $id = $_GET['id'];
        $marcados = $usuario->listarmarcados($id);
        //Declaramos el array para almacenar todos los permisos marcados
        $valores = [];

        if ($marcados['status']) {

          //Almacenar los permisos asignados al usuario en el array
          foreach ($marcados['data'] as $key => $value) {
            array_push($valores, $value['idpermiso']);
          }

          $data = ""; $num = 2;  $stado_close = false;
          //Mostramos la lista de permisos en la vista y si están o no marcados <label for=""></label>
          foreach ($rspta['data'] as $key => $value) {

            $div_open = ''; $div_close = '';

            if ( ($key + 1) == 1 ) {                  
              $div_open = '<ol class="list-unstyled row"><div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">'. 
              '<li class="text-primary"><input class="h-1rem w-1rem" type="checkbox" id="marcar_todo" onclick="marcar_todos_permiso();"> ' .
                '<label for="marcar_todo" class="marcar_todo">Marcar Todo</label>'.
              '</li>';                 
            } else {
              if ( ($key + 1) == $num ) { 
                $div_close = '</div>';
                $num += 3;
                $stado_close = true;
              } else {
                if ($stado_close) {
                  $div_open = '<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">';
                  $stado_close = false; 
                }             
              }
            }               
            
            $sw = in_array($value['idpermiso'], $valores) ? 'checked' : '';

            $data .= $div_open.'<li>'. 
              '<div class="form-group mb-0">'.
                '<div class="custom-control custom-checkbox">'.
                  '<input id="permiso_'.$value['idpermiso'].'" class="custom-control-input permiso h-1rem w-1rem" type="checkbox" ' . $sw . ' name="permiso[]" value="' . $value['idpermiso'] . '"> '.
                  '<label for="permiso_'.$value['idpermiso'].'" class="custom-control-label font-weight-normal" >' .$value['icono'] .' '. $value['nombre'].'</label>' . 
                '</div>'.
              '</div>'.
            '</li>'. $div_close;
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => $data.'</ol>', 
          );

          echo json_encode($retorno, true);

        } else {
          echo json_encode($marcados, true);
        }

      } else {
        echo json_encode($rspta, true);
      }    

    break;    

    case 'select2Trabajador':

      $rspta = $usuario->select2_trabajador();  $data = "";

      if ($rspta['status']) {

        foreach ($rspta['data'] as $key => $value) {
          $data  .= '<option value=' . $value['idpersona'] . ' title="'.$value['celular'].'">' . $value['nombres'] . ' - ' . $value['numero_documento'] . '</option>';
        }
    
        $retorno = array(
          'status' => true, 
          'message' => 'Salió todo ok', 
          'data' => $data, 
        );

        echo json_encode($retorno, true);
      } else {
        echo json_encode($rspta, true);
      }    
    break;    

    case 'select2_cargo_trabajador':
      $rspta=$usuario->select2_cargo_trabajador($_POST['id_persona']);
      echo json_encode($rspta, true);
    break;
    
    // ::::::::::::::::::::::::::::::::: S E C C I O N   T R A B A J A D O R :::::::::::::::::::::::::::::
    case 'guardar_y_editar_trabajador':

      // imgen de perfil
      if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {

        $imagen1=$_POST["foto1_actual"]; $flat_img1 = false;

      } else {

        $ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;						

        $imagen1 = random_int(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

        move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/persona/perfil/" . $imagen1);
        
      }

      if (empty($idtrabajador)){

        $rspta=$alltrabajador->insertar($idcargo_trabajador,$nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad,  $email, $banco, $cta_bancaria_format, $cci_format, $titular_cuenta, $ruc,$sueldo_mensual,$sueldo_diario, $imagen1);
        
        echo json_encode($rspta, true);

      }else {            
        $rspta = array( 'status' => false, 'message' => 'No hay editar usuario en este modulo', );      
        echo json_encode($rspta, true);
      }            

    break;

    // default: 
    //   $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
    // break;
  }
  
  ob_end_flush();
?>
