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
    
    require_once "../modelos/Ingreso_producto.php";
    require_once "../modelos/Persona.php";
    require_once "../modelos/Producto.php";

    $compra_producto = new Ingreso_producto();
    $proveedor = new Persona();
    $insumos = new Producto();      
    
    date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");
    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_integra/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');


    // :::::::::::::::::::::::::::::::::::: D A T O S   C O M P R A ::::::::::::::::::::::::::::::::::::::
    $idcompra_producto  = isset($_POST["idcompra_producto"]) ? limpiarCadena($_POST["idcompra_producto"]) : "";
    $idproveedor        = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
    $fecha_compra       = isset($_POST["fecha_compra"]) ? limpiarCadena($_POST["fecha_compra"]) : "";
    $tipo_comprobante   = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";    
    $serie_comprobante  = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
    $val_igv            = isset($_POST["val_igv"]) ? limpiarCadena($_POST["val_igv"]) : "";
    $descripcion        = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
    $subtotal_compra    = isset($_POST["subtotal_compra"]) ? limpiarCadena($_POST["subtotal_compra"]) : "";
    $tipo_gravada       = isset($_POST["tipo_gravada"]) ? limpiarCadena($_POST["tipo_gravada"]) : "";    
    $igv_compra         = isset($_POST["igv_compra"]) ? limpiarCadena($_POST["igv_compra"]) : "";
    $total_venta        = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";
    // $estado_detraccion  = isset($_POST["estado_detraccion"]) ? limpiarCadena($_POST["estado_detraccion"]) : "";
//$idcompra_producto,$idproveedor,$fecha_compra,$tipo_comprobante,$serie_comprobante,$val_igv,$descripcion,$subtotal_compra,$tipo_gravada,$igv_compra,$total_venta
    // :::::::::::::::::::::::::::::::::::: D A T O S   P A G O   C O M P R A ::::::::::::::::::::::::::::::::::::::
    $beneficiario_pago  = isset($_POST["beneficiario_pago"]) ? limpiarCadena($_POST["beneficiario_pago"]) : "";
    $forma_pago         = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
    $tipo_pago          = isset($_POST["tipo_pago"]) ? limpiarCadena($_POST["tipo_pago"]) : "";
    $cuenta_destino_pago = isset($_POST["cuenta_destino_pago"]) ? limpiarCadena($_POST["cuenta_destino_pago"]) : "";
    $banco_pago         = isset($_POST["banco_pago"]) ? limpiarCadena($_POST["banco_pago"]) : "";
    $titular_cuenta_pago = isset($_POST["titular_cuenta_pago"]) ? limpiarCadena($_POST["titular_cuenta_pago"]) : "";
    $fecha_pago         = isset($_POST["fecha_pago"]) ? limpiarCadena($_POST["fecha_pago"]) : "";
    $monto_pago         = isset($_POST["monto_pago"]) ? limpiarCadena($_POST["monto_pago"]) : "";
    $numero_op_pago     = isset($_POST["numero_op_pago"]) ? limpiarCadena($_POST["numero_op_pago"]) : "";
    $descripcion_pago   = isset($_POST["descripcion_pago"]) ? limpiarCadena($_POST["descripcion_pago"]) : "";
    $idcompra_producto_p = isset($_POST["idcompra_producto_p"]) ? limpiarCadena($_POST["idcompra_producto_p"]) : "";
    $idpago_compras     = isset($_POST["idpago_compras"]) ? limpiarCadena($_POST["idpago_compras"]) : ""; 
    $idproveedor_pago   = isset($_POST["idproveedor_pago"]) ? limpiarCadena($_POST["idproveedor_pago"]) : "";
    $imagen1            = isset($_POST["doc3"]) ? limpiarCadena($_POST["doc3"]) : "";

    // :::::::::::::::::::::::::::::::::::: D A T O S   C O M P R O B A N T E ::::::::::::::::::::::::::::::::::::::
    $id_compra_proyecto = isset($_POST["id_compra_proyecto"]) ? limpiarCadena($_POST["id_compra_proyecto"]) : "";
    $idfactura_compra_insumo = isset($_POST["idfactura_compra_insumo"]) ? limpiarCadena($_POST["idfactura_compra_insumo"]) : "";
    $doc_comprobante               = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";
    $doc_old_1          = isset($_POST["doc_old_1"]) ? limpiarCadena($_POST["doc_old_1"]) : "";

    // :::::::::::::::::::::::::::::::::::: D A T O S   M A T E R I A L E S ::::::::::::::::::::::::::::::::::::::
    $idproducto_p     = isset($_POST["idproducto_p"]) ? limpiarCadena($_POST["idproducto_p"]) : "" ;
    $unidad_medida_p  = isset($_POST["unidad_medida_p"]) ? limpiarCadena($_POST["unidad_medida_p"]) : "" ;
    $color_p          = isset($_POST["color_p"]) ? limpiarCadena($_POST["color_p"]) : "" ;
    $categoria_insumos_af_p    = isset($_POST["categoria_insumos_af_p"]) ? limpiarCadena($_POST["categoria_insumos_af_p"]) : "" ;
    $idgrupo          = isset($_POST["idtipo_tierra_concreto"]) ? limpiarCadena($_POST["idtipo_tierra_concreto"]) : "";
    $nombre_p         = isset($_POST["nombre_p"]) ? encodeCadenaHtml($_POST["nombre_p"]) : "" ;
    $modelo_p         = isset($_POST["modelo_p"]) ? encodeCadenaHtml($_POST["modelo_p"]) : "" ;
    $serie_p          = isset($_POST["serie_p"]) ? limpiarCadena($_POST["serie_p"]) : "" ;
    $marca_p          = isset($_POST["marca_p"]) ? encodeCadenaHtml($_POST["marca_p"]) : "" ;
    $estado_igv_p     = isset($_POST["estado_igv_p"]) ? limpiarCadena($_POST["estado_igv_p"]) : "" ;
    $precio_unitario_p= isset($_POST["precio_unitario_p"]) ? limpiarCadena($_POST["precio_unitario_p"]) : "" ;      
    $precio_sin_igv_p = isset($_POST["precio_sin_igv_p"]) ? limpiarCadena($_POST["precio_sin_igv_p"]) : "" ;
    $precio_igv_p     = isset($_POST["precio_igv_p"]) ? limpiarCadena($_POST["precio_igv_p"]) : "" ;
    $precio_total_p   = isset($_POST["precio_total_p"]) ? limpiarCadena($_POST["precio_total_p"]) : "" ;      
    $descripcion_p    = isset($_POST["descripcion_p"]) ? encodeCadenaHtml($_POST["descripcion_p"]) : "" ; 
    $img_pefil_p      = isset($_POST["foto2"]) ? limpiarCadena($_POST["foto2"]) : "" ;
    $ficha_tecnica_p  = isset($_POST["doc2"]) ? limpiarCadena($_POST["doc2"]) : "" ;

    // :::::::::::::::::::::::::::::::::::: D A T O S   P R O V E E D O R ::::::::::::::::::::::::::::::::::::::
    $idproveedor_prov		= isset($_POST["idproveedor_prov"])? limpiarCadena($_POST["idproveedor_prov"]):"";
    $nombre_prov 		    = isset($_POST["nombre_prov"])? limpiarCadena($_POST["nombre_prov"]):"";
    $tipo_documento_prov= isset($_POST["tipo_documento_prov"])? limpiarCadena($_POST["tipo_documento_prov"]):"";
    $num_documento_prov	= isset($_POST["num_documento_prov"])? limpiarCadena($_POST["num_documento_prov"]):"";
    $direccion_prov		  = isset($_POST["direccion_prov"])? limpiarCadena($_POST["direccion_prov"]):"";
    $telefono_prov		  = isset($_POST["telefono_prov"])? limpiarCadena($_POST["telefono_prov"]):"";
    $c_bancaria_prov		= isset($_POST["c_bancaria_prov"])? limpiarCadena($_POST["c_bancaria_prov"]):"";
    $cci_prov		    	  = isset($_POST["cci_prov"])? limpiarCadena($_POST["cci_prov"]):"";
    $c_detracciones_prov= isset($_POST["c_detracciones_prov"])? limpiarCadena($_POST["c_detracciones_prov"]):"";
    $banco_prov			    = isset($_POST["banco_prov"])? limpiarCadena($_POST["banco_prov"]):"";
    $titular_cuenta_prov= isset($_POST["titular_cuenta_prov"])? limpiarCadena($_POST["titular_cuenta_prov"]):"";

    switch ($_GET["op"]) {   
      
      // :::::::::::::::::::::::::: S E C C I O N   M A T E R I A L E S ::::::::::::::::::::::::::
      case 'guardar_y_editar_materiales':
        // imgen
        if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {
    
          $img_pefil_p = $_POST["foto2_actual"];
    
          $flat_img1 = false;
    
        } else {
    
          $ext1 = explode(".", $_FILES["foto2"]["name"]);
    
          $flat_img1 = true;
    
          $img_pefil_p = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
    
          move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/docs/material/img_perfil/" . $img_pefil_p);
        }
    
        // ficha técnica
        if (!file_exists($_FILES['doc2']['tmp_name']) || !is_uploaded_file($_FILES['doc2']['tmp_name'])) {
    
          $ficha_tecnica_p = $_POST["doc_old_2"];
    
          $flat_ficha1 = false;
    
        } else {
    
          $ext1 = explode(".", $_FILES["doc2"]["name"]);
    
          $flat_ficha1 = true;
    
          $ficha_tecnica_p = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
    
          move_uploaded_file($_FILES["doc2"]["tmp_name"], "../dist/docs/material/ficha_tecnica/" . $ficha_tecnica_p);
        }
    
        if (empty($idproducto_p)) {
          //var_dump($idproyecto,$idproveedor);
          $rspta = $insumos->insertar( $categoria_insumos_af_p, $idgrupo,$nombre_p, $modelo_p, $serie_p, $marca_p,$precio_unitario_p, $descripcion_p,$img_pefil_p,$ficha_tecnica_p,
          $estado_igv_p, $precio_igv_p, $precio_sin_igv_p,$unidad_medida_p, $color_p, $precio_total_p   );
          
          echo json_encode($rspta, true);
    
        } else {
    
          // validamos si existe LA IMG para eliminarlo
          if ($flat_img1 == true) {
    
            $datos_f1 = $insumos->obtenerImg($idproducto_p);    
            $img1_ant = (empty($datos_f1['data']) ? '' : $datos_f1['data']['imagen']);
    
            if (validar_url_completo($scheme_host. "dist/docs/material/img_perfil/" . $img1_ant)  == 200) {    
              unlink("../dist/docs/material/img_perfil/" . $img1_ant);
            }
          }
          
          $rspta = $insumos->editar($idproducto_p, $categoria_insumos_af_p, $idgrupo,$nombre_p, $modelo_p, $serie_p, $marca_p,$precio_unitario_p, $descripcion_p,$img_pefil_p,$ficha_tecnica_p,
          $estado_igv_p, $precio_igv_p, $precio_sin_igv_p,$unidad_medida_p, $color_p, $precio_total_p);
          //var_dump($idactivos_fijos,$idproveedor);
          echo json_encode($rspta, true);
        }
    
      break;
    
      case 'mostrar_materiales':
    
        $rspta = $insumos->mostrar($idproducto_p);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
    
      break;
        
      // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
      // case 'guardar_proveedor':
    
      //   if (empty($idproveedor_prov)){
    
      //     $rspta=$proveedor->insertar($nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
      //     $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
          
      //     echo json_encode($rspta, true);
      //   }else{
      //     $rspta=$proveedor->editar($idproveedor_prov, $nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
      //     $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
          
      //     echo json_encode($rspta, true);
      //   }
    
      // break;

      case 'mostrar_editar_proveedor':
        $rspta = $proveedor->mostrar($_POST["idproveedor"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
      break;
    
      // :::::::::::::::::::::::::: S E C C I O N   C O M P R A  ::::::::::::::::::::::::::
      case 'guardaryeditarcompra':

        if (empty($idcompra_producto)) {
          // $idcompra_producto,$idproveedor,$fecha_compra,$tipo_comprobante,$serie_comprobante,$val_igv,$descripcion,$subtotal_compra,$tipo_gravada,$igv_compra,$total_venta
          $rspta = $compra_producto->insertar($idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
          $total_venta, $subtotal_compra, $igv_compra,  $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["categoria"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST['precio_venta'], $_POST["descuento"], 
          $tipo_gravada);

          echo json_encode($rspta, true);
        } else {

          $rspta = $compra_producto->editar( $idcompra_producto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
          $total_venta, $subtotal_compra, $igv_compra,  $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["categoria"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
          $tipo_gravada);
    
          echo json_encode($rspta, true);
        }
    
      break;      
      
      case 'anular':
        $rspta = $compra_producto->desactivar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'eliminar_compra':

        $rspta = $compra_producto->eliminar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'tbla_principal':
        $rspta = $compra_producto->tbla_principal($_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
        
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status'] == true) {
          foreach ($rspta['data'] as $key => $reg) {

            $data[] = [
              "0" => $cont,
              "1" => '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg['idcompra_producto'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                    ' <button class="btn btn-warning btn-sm" onclick="mostrar_compra(' . $reg['idcompra_producto'] . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>' .                  
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar_compra(' . $reg['idcompra_producto'] .', \''.encodeCadenaHtml('<del><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</del> <del>'.$reg['nombres'].'</del>'). '\')"><i class="fas fa-skull-crossbones"></i> </button>',
                 
              "2" => $reg['fecha_compra'],
              "3" => '<span class="text-primary font-weight-bold" >' . $reg['nombres'] . '</span>',
              "4" =>'<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</span>',
              "5" => $reg['total'],
              "6" => $reg['descripcion'],
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
    
      case 'listar_compraxporvee':
        
        $rspta = $compra_producto->listar_compraxporvee();
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
              "3" => "<center>$reg->cantidad</center>",
              "4" => $reg->celular,
              "5" => number_format($reg->total, 2, '.', ','),
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
    
      case 'listar_detalle_compraxporvee':
        
        $rspta = $compra_producto->listar_detalle_comprax_provee($_GET["idproveedor"]);
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => '<center><button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg->idcompra_producto . ')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>',
              "2" => $reg->fecha_compra,
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
    
      case 'ver_detalle_compras':
        
        $rspta = $compra_producto->ver_compra($_GET['id_compra']);
        $rspta2 = $compra_producto->ver_detalle_compra($_GET['id_compra']);

        $subtotal = 0;    $ficha = ''; 

        $inputs = '<!-- Tipo de Empresa -->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="idproveedor">Proveedor</label>
              <h5 class="form-control form-control-sm" >'.$rspta['data']['nombres'].'</h5>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Fecha </label>
              <span class="form-control form-control-sm"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;&nbsp;'.format_d_m_a($rspta['data']['fecha_compra']).' </span>
            </div>
          </div>
          <!-- Tipo de comprobante -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="tipo_comprovante">Tipo Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['tipo_comprobante'])) ? '- - -' :  $rspta['data']['tipo_comprobante'])  .' </span>
            </div>
          </div>
          <!-- serie_comprovante-->
          <div class="col-lg-2">
            <div class="form-group">
              <label class="font-size-15px" for="serie_comprovante">N° de Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['serie_comprobante'])) ? '- - -' :  $rspta['data']['serie_comprobante']).' </span>
            </div>
          </div>
          <!-- IGV-->
          <div class="col-lg-1 " >
            <div class="form-group">
              <label class="font-size-15px" for="igv">IGV</label>
              <span class="form-control form-control-sm"> '.$rspta['data']['val_igv'].' </span>                                 
            </div>
          </div>
          <!-- Descripcion-->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="descripcion">Descripción </label> <br />
              <textarea class="form-control form-control-sm" readonly rows="1">'.((empty($rspta['data']['descripcion'])) ? '- - -' :$rspta['data']['descripcion']).'</textarea>
            </div>
        </div>';

        $tbody = ""; $cont = 1;

        while ($reg = $rspta2['data']->fetch_object()) {

          empty($reg->ficha_tecnica) ? ($ficha = '<i class="far fa-file-pdf fa-lg text-gray-50"></i>') : ($ficha = '<a target="_blank" href="../dist/docs/material/ficha_tecnica/' . $reg->ficha_tecnica . '"><i class="far fa-file-pdf fa-lg text-primary"></i></a>');
          $img_product = '../dist/docs/material/img_perfil/'. (empty($reg->imagen) ? 'producto-sin-foto.svg' : $reg->imagen );
          $tbody .= '<tr class="filas">
            <td class="text-center p-6px">' . $cont++ . '</td>
            <td class="text-center p-6px">' . $ficha . '</td>
            <td class="text-left p-6px">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="'.$img_product.'" alt="user image" onclick="ver_img_material(\''.$img_product.'\', \'' . encodeCadenaHtml( $reg->nombre) . '\', null)" onerror="this.src=\'../dist/svg/404-v2.svg\';" >
                <span class="username"><p class="mb-0 ">' . $reg->nombre . '</p></span>
                <span class="description"><b>Color: </b>' . $reg->color . '</span>
              </div>
            </td>
            <td class="text-left p-6px">' . $reg->unidad_medida . '</td>
            <td class="text-center p-6px">' . $reg->cantidad . '</td>		
            <td class="text-right p-6px">' . number_format($reg->precio_sin_igv, 2, '.',',') . '</td>
            <td class="text-right p-6px">' . number_format($reg->igv, 2, '.',',') . '</td>
            <td class="text-right p-6px">' . number_format($reg->precio_con_igv, 2, '.',',') . '</td>
            <td class="text-right p-6px">' . number_format($reg->descuento, 2, '.',',') . '</td>
            <td class="text-right p-6px">' . number_format($reg->subtotal, 2, '.',',') .'</td>
          </tr>';
        }         

        $tabla_detalle = '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover" id="tabla_detalle_factura">
            <thead style="background-color:#ff6c046b">
              <tr class="text-center hidden">
                <th class="p-10px">Proveedor:</th>
                <th class="text-center p-10px" colspan="9" >'.$rspta['data']['razon_social'].'</th>
              </tr>
              <tr class="text-center hidden">                
                <th class="text-center p-10px" colspan="2" >'.((empty($rspta['data']['tipo_comprobante'])) ? '' :  $rspta['data']['tipo_comprobante']). ' ─ ' . ((empty($rspta['data']['serie_comprobante'])) ? '' :  $rspta['data']['serie_comprobante']) .'</th>
                <th class="p-10px">Fecha:</th>
                <th class="text-center p-10px" colspan="3" >'.format_d_m_a($rspta['data']['fecha_compra']).'</th>
                <th class="p-10px">Glosa:</th>
                <th class="text-center p-10px" colspan="3" >'.$rspta['data']['glosa'].'</th>
              </tr>
              <tr class="text-center">
                <th class="text-center p-10px" >#</th>
                <th class="text-center p-10px">F.T.</th>
                <th class="p-10px">Material</th>
                <th class="p-10px">U.M.</th>
                <th class="p-10px">Cant.</th>
                <th class="p-10px">V/U</th>
                <th class="p-10px">IGV</th>
                <th class="p-10px">P/U</th>
                <th class="p-10px">Desct.</th>
                <th class="p-10px">Subtotal</th>
              </tr>
            </thead>
            <tbody>'.$tbody.'</tbody>          
            <tfoot>
              <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h6 class="mt-1 mb-1 mr-1">'.$rspta['data']['tipo_gravada'].'</h6> </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['subtotal'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1">IGV('.( ( empty($rspta['data']['val_igv']) ? 0 : floatval($rspta['data']['val_igv']) )  * 100 ).'%)</h6>
                  </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['igv'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h5 class="mt-1 mb-1 mr-1 font-weight-bold">TOTAL</h5> </td>
                  <td class="p-0 text-right">
                    <h5 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['total'], 2, '.',',') . '</h5>
                  </td>
                </tr>
            </tfoot>
          </table>
        </div> ';
        $retorno = ['status' => true, 'message' => 'todo oka', 'data' => $inputs . $tabla_detalle ,];
        echo json_encode( $retorno, true );

      break;
    
      case 'ver_compra_editar':

        $rspta = $compra_producto->mostrar_compra_para_editar($idcompra_producto);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
    
      break;      

      // ::::::::::::::::::::::::::::::::::::::::: S I N C R O N I Z A R  :::::::::::::::::::::::::::::::::::::::::
      case 'sincronizar_comprobante':

        $rspta = $compra_producto->sincronizar_comprobante();
        //Codificar el resultado utilizando json
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
