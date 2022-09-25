<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['recurso'] == 1) {
      
      require_once "../modelos/Producto.php";

      $materiales = new Producto();

      date_default_timezone_set('America/Lima'); $date_now = date("d-m-Y h.i.s A");

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_integra/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

      $idproducto = isset($_POST["idproducto"]) ? limpiarCadena($_POST["idproducto"]) : "";
      $nombre = isset($_POST["nombre_producto"]) ? limpiarCadena($_POST["nombre_producto"]) : "";
      $unidad_medida = isset($_POST["unidad_medida"]) ? limpiarCadena($_POST["unidad_medida"]) : "";
      $descripcion = isset($_POST["descripcion_material"]) ? encodeCadenaHtml($_POST["descripcion_material"]) : "";   
      $precio_unitario = isset($_POST["precio_unitario"]) ? limpiarCadena($_POST["precio_unitario"]) : "";
      
    

      switch ($_GET["op"]) {

        case 'guardaryeditar':
          // imgen
        

        

          if (empty($idproducto)) {
            
            $rspta = $materiales->insertar($nombre, $precio_unitario, $descripcion, $unidad_medida);
            
            echo json_encode( $rspta, true);

          } else {

            // validamos si existe LA IMG para eliminarlo
            
            $rspta = $materiales->editar($idproducto, $nombre, $precio_unitario, $descripcion, $unidad_medida);
            
            echo json_encode( $rspta, true) ;
          }
        break;
    
        case 'desactivar':

          $rspta = $materiales->desactivar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar':

          $rspta = $materiales->eliminar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;
    
        case 'mostrar':

          $rspta = $materiales->mostrar($idproducto);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;
    
        case 'tbla_principal':
          $rspta = $materiales->tbla_principal();
          //Vamos a declarar un array
          $data = [];
          $imagen_error = "this.src='../dist/svg/404-v2.svg'";
          $cont=1;
          $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {

              
              
              $data[] = [
                "0"=>$cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idproducto . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->idproducto .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>'. 
                ' <button class="btn btn-info btn-sm" onclick="verdatos('.$reg->idproducto.')" data-toggle="tooltip" data-original-title="Ver datos"><i class="far fa-eye"></i></button>' : 
                '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idproducto . ')"><i class="fa fa-pencil-alt"></i></button>',
                "2" => $reg->idproducto,
                "3" => decodeCadenaHtml($reg->nombre) ,
                "4" => number_format($reg->precio_unitario, 2, '.', ''),
                "5" => decodeCadenaHtml($reg->nombre),
                "6" => $reg->descripcion,
              ];
            }
  
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
  
            echo json_encode( $results, true) ;
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
          
        break;
    
        case 'salir':
          //Limpiamos las variables de sesión
          session_unset();
          //Destruìmos la sesión
          session_destroy();
          //Redireccionamos al login
          header("Location: ../index.php");
    
        break;

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;
      }
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }  
  } 
  
  ob_end_flush();
?>
