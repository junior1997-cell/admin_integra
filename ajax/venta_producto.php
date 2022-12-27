<?php
ob_start();
if (strlen(session_id()) < 1) {
  session_start(); //Validamos si existe o no la sesión
}

if (!isset($_SESSION["nombre"])) {
  $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
  echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
} else {

  if ($_SESSION['compra_insumos'] == 1) {
    
    require_once "../modelos/Venta_producto.php";
    require_once "../modelos/Persona.php";
    require_once "../modelos/Producto.php";

    $venta_producto = new Venta_producto();
    $proveedor = new Persona();
    $productos = new Producto();      
    
    date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");
    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_integra/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');


    // :::::::::::::::::::::::::::::::::::: D A T O S   V E N T A ::::::::::::::::::::::::::::::::::::::
    $idcompra_producto  = isset($_POST["idcompra_producto"]) ? limpiarCadena($_POST["idcompra_producto"]) : "";
    $idcliente          = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
    $fecha_venta        = isset($_POST["fecha_venta"]) ? limpiarCadena($_POST["fecha_venta"]) : "";
    $tipo_comprobante   = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";    
    $serie_comprobante  = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
    $val_igv            = isset($_POST["val_igv"]) ? limpiarCadena($_POST["val_igv"]) : "";
    $descripcion        = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
    $subtotal_compra    = isset($_POST["subtotal_compra"]) ? limpiarCadena($_POST["subtotal_compra"]) : "";
    $tipo_gravada       = isset($_POST["tipo_gravada"]) ? limpiarCadena($_POST["tipo_gravada"]) : "";    
    $igv_venta         = isset($_POST["igv_venta"]) ? limpiarCadena($_POST["igv_venta"]) : "";
    $total_venta        = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";

    $metodo_pago        = isset($_POST["metodo_pago"]) ? limpiarCadena($_POST["metodo_pago"]) : "";
    $fecha_proximo_pago = isset($_POST["fecha_proximo_pago"]) ? limpiarCadena($_POST["fecha_proximo_pago"]) : "";
    $monto_pago_compra  = isset($_POST["monto_pago_compra"]) ? limpiarCadena($_POST["monto_pago_compra"]) : "";
    
    // :::::::::::::::::::::::::::::::::::: D A T O S   P A G O   V E N T A ::::::::::::::::::::::::::::::::::::::
    $idpago_venta_producto_pv  = isset($_POST["idpago_venta_producto_pv"]) ? limpiarCadena($_POST["idpago_venta_producto_pv"]) : "";
    $idventa_producto_pv       = isset($_POST["idventa_producto_pv"]) ? limpiarCadena($_POST["idventa_producto_pv"]) : "";  
    $forma_pago_pv             = isset($_POST["forma_pago_pv"]) ? limpiarCadena($_POST["forma_pago_pv"]) : "";
    $fecha_pago_pv             = isset($_POST["fecha_pago_pv"]) ? limpiarCadena($_POST["fecha_pago_pv"]) : "";
    $monto_pv                  = isset($_POST["monto_pv"]) ? limpiarCadena($_POST["monto_pv"]) : "";  
    $descripcion_pv            = isset($_POST["descripcion_pv"]) ? limpiarCadena($_POST["descripcion_pv"]) : "";  
     
    // :::::::::::::::::::::::::::::::::::: D A T O S   C O M P R O B A N T E ::::::::::::::::::::::::::::::::::::::
    $id_compra_proyecto = isset($_POST["id_compra_proyecto"]) ? limpiarCadena($_POST["id_compra_proyecto"]) : "";
    $idfactura_compra_insumo = isset($_POST["idfactura_compra_insumo"]) ? limpiarCadena($_POST["idfactura_compra_insumo"]) : "";
    $doc_comprobante               = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";
    $doc_old_1          = isset($_POST["doc_old_1"]) ? limpiarCadena($_POST["doc_old_1"]) : "";

    // :::::::::::::::::::::::::::::::::::: D A T O S   M A T E R I A L E S ::::::::::::::::::::::::::::::::::::::
    $idproducto_p            = isset($_POST["idproducto_p"]) ? limpiarCadena($_POST["idproducto_p"]) : "" ;
    $idcategoria_producto_p  = isset($_POST["categoria_producto_p"]) ? limpiarCadena($_POST["categoria_producto_p"]) : "" ;
    $unidad_medida_p         = isset($_POST["unidad_medida_p"]) ? limpiarCadena($_POST["unidad_medida_p"]) : "" ;
    $nombre_producto_p       = isset($_POST["nombre_producto_p"]) ? encodeCadenaHtml($_POST["nombre_producto_p"]) : "" ;
    $marca_p                 = isset($_POST["marca_p"]) ? encodeCadenaHtml($_POST["marca_p"]) : "" ;
    $contenido_neto_p        = isset($_POST["contenido_neto_p"]) ? limpiarCadena($_POST["contenido_neto_p"]) : "" ;
    $descripcion_p           = isset($_POST["descripcion_p"]) ? encodeCadenaHtml($_POST["descripcion_p"]) : "" ;

    $imagen1 = isset($_POST["foto1"]) ? limpiarCadena($_POST["foto1"]) : "" ;

    // :::::::::::::::::::::::::::::::::::: D A T O S   P R O V E E D O R ::::::::::::::::::::::::::::::::::::::
    $idpersona	  	  = isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
    $id_tipo_persona  = isset($_POST["id_tipo_persona"])? limpiarCadena($_POST["id_tipo_persona"]):"";
    $nombre 		      = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
    $tipo_documento 	= isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
    $num_documento  	= isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
    $input_socio     	= isset($_POST["input_socio"])? limpiarCadena($_POST["input_socio"]):"";
    $direccion		    = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
    $telefono		      = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";     
    $email			      = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
    $banco            = isset($_POST["banco"])? $_POST["banco"] :"";
    $cta_bancaria_format  = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
    $cta_bancaria     = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
    $cci_format      	= isset($_POST["cci"])? $_POST["cci"]:"";
    $cci            	= isset($_POST["cci"])? $_POST["cci"]:"";
    $titular_cuenta		= isset($_POST["titular_cuenta"])? limpiarCadena($_POST["titular_cuenta"]):"";

    switch ($_GET["op"]) {   
      
      // :::::::::::::::::::::::::: S E C C I O N   P R O D U C T O S ::::::::::::::::::::::::::
      case 'guardar_y_editar_productos':
    
        if (empty($idproducto_p)) {
          $rspta = $productos->insertar($idcategoria_producto_p, $unidad_medida_p, $nombre_producto_p, $marca_p, $contenido_neto_p, $descripcion_p, $imagen1 );
            
          echo json_encode( $rspta, true);
    
        } else {
            
          $rspta = $productos->editar($idproducto_p, $idcategoria_producto_p, $unidad_medida_p, $nombre_producto_p, $marca_p, $contenido_neto_p, $descripcion_p, $imagen1 );
            
          echo json_encode( $rspta, true) ;
        }
    
      break;
    
      case 'mostrar_productos':
    
        $rspta = $productos->mostrar($idproducto);
        echo json_encode($rspta, true);
    
      break;
        
      // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
      case 'guardar_proveedor':
    
        if (empty($idpersona)){

          $rspta=$proveedor->insertar($id_tipo_persona,$tipo_documento,$num_documento,$nombre,$input_socio,$email,$telefono,$banco,$cta_bancaria,$cci,$titular_cuenta,$direccion, $imagen1);
                      
          echo json_encode($rspta, true);
          
        }else{
          
            // editamos un persona existente
            $rspta=$proveedor->editar($idpersona,$id_tipo_persona,$tipo_documento,$num_documento,$nombre,$input_socio,$email,$telefono,$banco,$cta_bancaria,$cci,$titular_cuenta,$direccion, $imagen1);
            
            echo json_encode($rspta, true);
        }
    
      break;

      case 'mostrar_editar_proveedor':
        $rspta = $proveedor->mostrar($_POST["idcliente"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
      break;
    
      // :::::::::::::::::::::::::: S E C C I O N   V E N T A  ::::::::::::::::::::::::::
      case 'guardaryeditarcompra':

        if (empty($idcompra_producto)) {
          // $idcompra_producto,$idcliente,$fecha_venta,$tipo_comprobante,$serie_comprobante,$val_igv,$descripcion,$subtotal_compra,$tipo_gravada,$igv_venta,$total_venta
          $rspta = $venta_producto->insertar($idcliente, $fecha_venta,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
          $metodo_pago, $fecha_proximo_pago, $monto_pago_compra,
          $total_venta, $subtotal_compra, $igv_venta,  $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["categoria"], $_POST["cantidad"], $_POST["stock_actual"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
          $tipo_gravada);

          echo json_encode($rspta, true);
        } else {

          $rspta = $venta_producto->editar( $idcompra_producto, $idcliente, $fecha_venta,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
          $metodo_pago, $fecha_proximo_pago, $monto_pago_compra,
          $total_venta, $subtotal_compra, $igv_venta,  $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["categoria"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
          $tipo_gravada);
    
          echo json_encode($rspta, true);
        }
    
      break;      
      
      case 'anular':
        $rspta = $venta_producto->desactivar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'eliminar_compra':

        $rspta = $venta_producto->eliminar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'tbla_principal':
        $rspta = $venta_producto->tbla_principal($_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
        
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status'] == true) {
          foreach ($rspta['data'] as $key => $reg) {
            $saldo = $reg['total'] - $reg['total_pago'];
            
            if ($saldo == $reg['total']) {
              $estado = '<span class="text-center badge badge-danger">Sin pagar</span>';
              $color_btn = "danger"; $nombre = "Pagar"; $icon = "dollar-sign";
            } else if ($saldo < $reg['total'] && $saldo > "0") {              
              $estado = '<span class="text-center badge badge-warning">En proceso</span>';
              $color_btn = "warning"; $nombre = "Pagar"; $icon = "dollar-sign";
            } else if ($saldo <= "0" || $saldo == "0") {              
              $estado = '<span class="text-center badge badge-success">Pagado</span>';
              $color_btn = "success"; $nombre = "Ver"; $icon = "eye";
            } else {
              $estado = '<span class="text-center badge badge-success">Error</span>';               
            }           

            $data[] = [
              "0" => $cont,
              "1" => '<button class="btn btn-info btn-sm" onclick="ver_detalle_ventas(' . $reg['idventa_producto'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                    ' <button class="btn btn-warning btn-sm" onclick="mostrar_compra(' . $reg['idventa_producto'] . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>' .                  
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar_compra(' . $reg['idventa_producto'] .', \''.encodeCadenaHtml('<del><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</del> <del>'.$reg['cliente'].'</del>'). '\')"><i class="fas fa-skull-crossbones"></i> </button>',
                 
              "2" => $reg['fecha_venta'],
              "3" => '<span class="text-primary font-weight-bold" >' . $reg['cliente'] . '</span>',
              "4" => $reg['es_socio'],
              "5" =>'<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</span>',
              "6" => $reg['metodo_pago'], 
              "7" => $reg['total'],
              "8" => '<div class="text-center text-nowrap">'.
                '<button class="btn btn-' . $color_btn . ' btn-xs m-t-2px" onclick="tbla_pago_venta(' . $reg['idventa_producto'] . ', ' . $reg['total'] . ', ' . floatval($reg['total_pago']) .', \''.encodeCadenaHtml($reg['cliente']) .'\')" data-toggle="tooltip" data-original-title="Ingresar a pagos"> <i class="fas fa-' . $icon . ' nav-icon"></i> ' . $nombre . '</button>' . 
                ' <button style="font-size: 14px;" class="btn btn-' . $color_btn . ' btn-sm">' . number_format(floatval($reg['total_pago']), 2, '.', ',') . '</button>'.
              '</div>',
              "9" => $saldo,

              "10" => $reg['tipo_documento'],
              "11" => $reg['numero_documento'],
              "12" => $reg['tipo_comprobante'],
              "13" => $reg['serie_comprobante'],
              "14" => $reg['total_pago'],
            ];
            $cont++;
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "data" => $data,
          ];
          echo json_encode($results, true);
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
    
      break;
    
      case 'listar_compra_x_porveedor':
        
        $rspta = $venta_producto->listar_compra_x_porveedor();
        //Vamos a declarar un array
        $data = []; $cont = 1;
        $c = "info";
        $nombre = "Ver";
        $info = "info";
        $icon = "eye";
        
        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => '<button class="btn btn-info btn-sm" onclick="listar_facuras_proveedor(' . $reg->idpersona . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
              "2" => $reg->razon_social,
              "3" => $reg->numero_documento,
              "4" => $reg->cantidad,
              "5" => $reg->celular,
              "6" => $reg->total,
            ];
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
    
      break;
    
      case 'tabla_detalle_compra_x_porveedor':
        
        $rspta = $venta_producto->listar_detalle_compra_x_proveedor($_GET["idcliente"]);
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => '<center><button class="btn btn-info btn-sm" onclick="ver_detalle_ventas(' . $reg->idventa_producto . ')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>',
              "2" => $reg->fecha_venta,
              "3" => $reg->tipo_comprobante,
              "4" => $reg->serie_comprobante,
              "5" => number_format($reg->total, 2, '.', ','),
              "6" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg->descripcion.'</textarea>'
            ];
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
    
      break;  
      
    
      case 'ver_compra_editar':

        $rspta = $venta_producto->mostrar_compra_para_editar($idcompra_producto);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
    
      break;   
      
      // :::::::::::::::::::::::::: S E C C I O N   P A G O  ::::::::::::::::::::::::::     
      case 'guardar_y_editar_pago_venta':
    
        // imgen de perfil
        if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
          $comprobante_pago = $_POST["doc_old_1"]; $flat_doc1 = false;
        } else {
          $ext1 = explode(".", $_FILES["doc1"]["name"]); $flat_doc1 = true;	
          $comprobante_pago  = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
          move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/venta_producto/comprobante_pago/" . $comprobante_pago );          
        }

        if (empty($idpago_venta_producto_pv)){
          
          $rspta=$venta_producto->crear_pago_compra( $idventa_producto_pv, $forma_pago_pv, $fecha_pago_pv, quitar_formato_miles($monto_pv), $descripcion_pv, $comprobante_pago);          
          echo json_encode($rspta, true);

        }else {

          // validamos si existe LA IMG para eliminarlo
          if ($flat_doc1 == true) {
            $doc_pago = $venta_producto->obtener_doc_pago_compra($idpago_venta_producto_pv);
            $doc_pago_antiguo = $doc_pago['data']['comprobante'];
            if ( !empty($doc_pago_antiguo) ) { unlink("../dist/docs/venta_producto/comprobante_pago/" . $doc_pago_antiguo);  }
          }            

          // editamos un persona existente
          $rspta=$venta_producto->editar_pago_compra( $idpago_venta_producto_pv, $idventa_producto_pv, $forma_pago_pv, $fecha_pago_pv, quitar_formato_miles($monto_pv), $descripcion_pv, $comprobante_pago );          
          echo json_encode($rspta, true);
        }
    
      break;

      case 'tabla_pago_venta':
        
        $rspta = $venta_producto->tabla_pago_compras($_GET["idventa_producto"]);
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status'] == true) {
          while ($reg = $rspta['data']->fetch_object()) {
            $doc = (empty($reg->comprobante) ? '<button class="btn btn-sm btn-outline-info" data-toggle="tooltip" data-original-title="Vacio" ><i class="fa-regular fa-file-pdf fa-2x"></i></button>' : '<a href="#" class="btn btn-sm btn-info" data-toggle="tooltip" data-original-title="Ver documento" onclick="ver_documento_pago(\''.$reg->comprobante. '\', \'' . removeSpecialChar($reg->cliente) . ' - ' .date("d/m/Y", strtotime($reg->fecha_pago)).'\')"><i class="fa-regular fa-file-pdf fa-2x"></i></a>');
            $data[] = [
              "0" => $cont++,
              "1" => ' <button class="btn btn-sm btn-warning" id="btn_monto_pagado_' . $reg->idpago_venta_producto . '" monto_pagado="'.$reg->monto.'" onclick="mostrar_editar_pago(' . $reg->idpago_venta_producto . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>' .
              ' <button class="btn btn-sm btn-danger" onclick="eliminar_pago_venta(' . $reg->idpago_venta_producto .', \''.encodeCadenaHtml( number_format($reg->monto, 2, '.',',')).' - '.date("d/m/Y", strtotime($reg->fecha_pago)).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>',
              "2" => $reg->fecha_pago,
              "3" => $reg->forma_pago,
              "4" => $reg->monto,
              "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg->descripcion.'</textarea>',
              "6" => $doc,
              "7" => $reg->estado == '1' ? '<span class="badge bg-success">Aceptado</span>' : '<span class="badge bg-danger">Anulado</span>',
            ];
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
    
      break;
      
      case 'papelera_pago_venta':
        $rspta = $venta_producto->papelera_pago_venta($_GET["id_tabla"]);    
        echo json_encode($rspta, true);    
      break;    
      
      case 'eliminar_pago_venta':

        $rspta = $venta_producto->eliminar_pago_venta($_GET["id_tabla"]);    
        echo json_encode($rspta, true);
    
      break;

      case 'mostrar_editar_pago':

        $rspta = $venta_producto->mostrar_editar_pago($_POST["idpago_venta"]);    
        echo json_encode($rspta, true);
    
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
