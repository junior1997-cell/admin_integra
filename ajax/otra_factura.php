<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['otra_factura'] == 1) {

      require_once "../modelos/Otra_factura.php";

      $otra_factura = new Otra_factura();

      date_default_timezone_set('America/Lima');   $date_now = date("d-m-Y h.i.s A");

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_integra/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      $idotra_factura   = isset($_POST["idotra_factura"]) ? limpiarCadena($_POST["idotra_factura"]) : "";      
      $idproveedor      = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
      $ruc_proveedor    = isset($_POST["ruc_proveedor"]) ? limpiarCadena($_POST["ruc_proveedor"]) : "";      
      $fecha_emision    = isset($_POST["fecha_emision"]) ? limpiarCadena($_POST["fecha_emision"]) : "";
      $forma_pago       = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
      $nro_comprobante  = isset($_POST["nro_comprobante"]) ? limpiarCadena($_POST["nro_comprobante"]) : "";
      $subtotal         = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
      $igv              = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
      $val_igv          = isset($_POST["val_igv"]) ? limpiarCadena($_POST["val_igv"]) : "";
      $precio_parcial   = isset($_POST["precio_parcial"]) ? limpiarCadena($_POST["precio_parcial"]) : "";
      $descripcion      = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
      $glosa            = isset($_POST["glosa"]) ? limpiarCadena($_POST["glosa"]) : "";
      $tipo_gravada     = isset($_POST["tipo_gravada"]) ? limpiarCadena($_POST["tipo_gravada"]) : "";

      $empresa_acargo   = isset($_POST["empresa_acargo"]) ? limpiarCadena($_POST["empresa_acargo"]) : "";

      $foto2 = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";
      
      switch ($_GET["op"]) {
        case 'guardaryeditar':
          // Comprobante
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
      
            $comprobante = $_POST["doc_old_1"];
      
            $flat_ficha1 = false;
      
          } else {
      
            $ext1 = explode(".", $_FILES["doc1"]["name"]);
      
            $flat_ficha1 = true;
      
            $comprobante = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/otra_factura/comprobante/" . $comprobante);
          }
      
          if (empty($idotra_factura)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $otra_factura->insertar($idproveedor, $empresa_acargo, $ruc_proveedor, $tipo_comprobante, $nro_comprobante, $forma_pago, $fecha_emision, $val_igv, $subtotal, $igv, $precio_parcial, $descripcion, $glosa, $comprobante, $tipo_gravada);
            
            echo json_encode($rspta, true) ;
      
          } else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {
      
              $datos_ficha1 = $otra_factura->ObtnerCompr($idotra_factura);
      
              $ficha1_ant = $datos_ficha1['data']->fetch_object()->comprobante;
      
              if (validar_url_completo($scheme_host. "dist/docs/otra_factura/comprobante/" . $ficha1_ant)  == 200) {  unlink("../dist/docs/otra_factura/comprobante/" . $ficha1_ant); }
            }
      
            $rspta = $otra_factura->editar($idotra_factura, $idproveedor, $empresa_acargo, $tipo_comprobante, $nro_comprobante, $forma_pago, $fecha_emision, $val_igv, $subtotal, $igv, $precio_parcial, $descripcion, $glosa, $comprobante, $tipo_gravada);
            //var_dump($idotra_factura,$idproveedor);
            echo json_encode($rspta, true) ;
          }
        break;
              
        case 'desactivar':
      
          $rspta = $otra_factura->desactivar($_GET["id_tabla"]);
      
          echo json_encode($rspta, true) ;
      
        break;
      
        case 'eliminar':
      
          $rspta = $otra_factura->eliminar($_GET["id_tabla"]);
      
          echo json_encode($rspta, true) ;
      
        break;
      
        case 'mostrar':
      
          $rspta = $otra_factura->mostrar($idotra_factura);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;
      
        break;
      
      
        case 'tbla_principal':
          $rspta = $otra_factura->tbla_principal($_GET["empresa_a_cargo"], $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
          //Vamos a declarar un array
          $data = []; $cont = 1;

          if ($rspta['status']) {
            while ($reg = $rspta['data']->fetch_object()) {
              // empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>':$comprobante='<center><a target="_blank" href="../dist/img/comprob_otro_gasto/'.$reg->comprobante.'"><i class="far fa-file-pdf fa-2x" style="color:#ff0000c4"></i></a></center>';
        
              $comprobante = empty($reg->comprobante) ? ( '<center> <i class="fas fa-file-invoice-dollar fa-2x text-gray-50" data-toggle="tooltip" data-original-title="Vacío"></i></center>') : ( '<center><i class="fas fa-file-invoice-dollar fa-2x cursor-pointer text-blue" onclick="modal_comprobante(\'' . $reg->comprobante . '\', \''.$reg->fecha_emision.'\''. ')" data-toggle="tooltip" data-original-title="Ver Baucher"></i></center>');              
  
              $data[] = [
                "0" => $cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idotra_factura. ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg->idotra_factura. ', \''. $reg->tipo_comprobante.' '.(empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>'. 
                    ' <button class="btn btn-info btn-sm" onclick="verdatos('.$reg->idotra_factura.')" data-toggle="tooltip" data-original-title="Ver datos"><i class="far fa-eye"></i></button>':
                    '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idotra_factura. ')"><i class="fa fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->idotra_factura. ')"><i class="fa fa-check"></i></button>',
                "2" => $reg->fecha_emision,
                "3" => $reg->razon_social,
                "4" => $reg->forma_de_pago,
                "5" =>'<div class="user-block">
                    <span class="username ml-0" > <p class="text-primary m-b-02rem" >' . $reg->tipo_comprobante . '</p> </span>
                    <span class="description ml-0" >N° ' . (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante) . '</span>         
                  </div>',
                "6" => $reg->subtotal,
                "7" => $reg->igv,
                "8" => $reg->costo_parcial,
                "9" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
                "10" => $comprobante . $toltip,
                "11" => $reg->glosa,
                "12" => $reg->tipo_comprobante,
                "13" => $reg->numero_comprobante,
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
      
        case 'total':
      
          $rspta = $otra_factura->total();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
      
        break;

        // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
        case 'guardar_proveedor':
      
          if (empty($idproveedor_prov)){
      
            $rspta=$proveedor->insertar($nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
            $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
            
            echo json_encode($rspta, true);
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
