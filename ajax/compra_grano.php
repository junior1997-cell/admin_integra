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
    
    require_once "../modelos/Compra_grano.php";
    //require_once "../modelos/Proveedor.php";
    require_once "../modelos/Producto.php";

    $compra_grano = new Compra_grano();
    //$proveedor = new Proveedor();
    $insumos = new Producto();      
    
    date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");
    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_integra/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');


    // :::::::::::::::::::::::::::::::::::: D A T O S   C O M P R A ::::::::::::::::::::::::::::::::::::::
    $idproyecto         = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
    $idcompra_proyecto  = isset($_POST["idcompra_proyecto"]) ? limpiarCadena($_POST["idcompra_proyecto"]) : "";
    $idproveedor        = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
    $fecha_compra       = isset($_POST["fecha_compra"]) ? limpiarCadena($_POST["fecha_compra"]) : "";
    $glosa              = isset($_POST["glosa"]) ? limpiarCadena($_POST["glosa"]) : "";
    $tipo_comprobante   = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";    
    $serie_comprobante  = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
    $val_igv            = isset($_POST["val_igv"]) ? limpiarCadena($_POST["val_igv"]) : "";
    $descripcion        = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
    $subtotal_compra    = isset($_POST["subtotal_compra"]) ? limpiarCadena($_POST["subtotal_compra"]) : "";
    $tipo_gravada       = isset($_POST["tipo_gravada"]) ? limpiarCadena($_POST["tipo_gravada"]) : "";    
    $igv_compra         = isset($_POST["igv_compra"]) ? limpiarCadena($_POST["igv_compra"]) : "";
    $total_venta        = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";
    $estado_detraccion  = isset($_POST["estado_detraccion"]) ? limpiarCadena($_POST["estado_detraccion"]) : "";

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
    $idcompra_proyecto_p = isset($_POST["idcompra_proyecto_p"]) ? limpiarCadena($_POST["idcompra_proyecto_p"]) : "";
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
        
      // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
      case 'guardar_proveedor':
    
        if (empty($idproveedor_prov)){
    
          $rspta=$proveedor->insertar($nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
          $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
          
          echo json_encode($rspta, true);
        }else{
          $rspta=$proveedor->editar($idproveedor_prov, $nombre_prov, $tipo_documento_prov, $num_documento_prov, $direccion_prov, $telefono_prov,
          $c_bancaria_prov, $cci_prov, $c_detracciones_prov, $banco_prov, $titular_cuenta_prov);
          
          echo json_encode($rspta, true);
        }
    
      break;

      case 'mostrar_editar_proveedor':
        $rspta = $proveedor->mostrar($_POST["idproveedor"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
      break;
    
      // :::::::::::::::::::::::::: S E C C I O N   C O M P R A  ::::::::::::::::::::::::::
      case 'guardaryeditarcompra':

        if (empty($idcompra_proyecto)) {

          $rspta = $compra_grano->insertar( $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
          $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
          $tipo_gravada, $_POST["ficha_tecnica_producto"] );

          echo json_encode($rspta, true);
        } else {

          $rspta = $compra_grano->editar( $idcompra_proyecto, $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, 
          $descripcion, $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $_POST["idproducto"], $_POST["unidad_medida"], 
          $_POST["nombre_color"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST["descuento"], 
          $tipo_gravada, $_POST["ficha_tecnica_producto"] );
    
          echo json_encode($rspta, true);
        }
    
      break;      
      
      case 'anular':
        $rspta = $compra_grano->desactivar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'des_anular':
        $rspta = $compra_grano->activar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;

      case 'eliminar_compra':

        $rspta = $compra_grano->eliminar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'tbla_principal':
        $rspta = $compra_grano->tbla_principal( $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
        
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status'] == true) {
          foreach ($rspta['data'] as $key => $reg) {                          
      
            $data[] = [
              "0" => $cont,
              "1" => $reg['estado'] == '1' ? '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg['idcompra_grano'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                    ' <button class="btn btn-warning btn-sm" onclick="mostrar_compra(' . $reg['idcompra_grano'] . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>' .                  
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar_compra(' . $reg['idcompra_grano'] .', \''.encodeCadenaHtml('<del><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['numero_comprobante']) ?  "" :  '- '.$reg['numero_comprobante']).'</del> <del>'.$reg['nombre_cliente'].'</del>'). '\')"><i class="fas fa-skull-crossbones"></i> </button>'
                  : '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' .  $reg['idcompra_grano'] . ')"data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>' .
                    ' <button class="btn btn-success btn-sm" onclick="des_anular(' . $reg['idcompra_grano'] . ')" data-toggle="tooltip" data-original-title="Recuperar Compra"><i class="fas fa-check"></i></button>',
              "2" => $reg['fecha_compra'],
              "3" => '<span class="text-primary font-weight-bold" >' . $reg['cliente'] . '</span>',
              "4" => $reg['tipo_persona'],
              "5" =>'<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['numero_comprobante']) ?  "" :  '- '.$reg['numero_comprobante']).'</span>',              
              "6" => $reg['total_compra'],              
              "7" => $reg['metodo_pago'],
              "8" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg['descripcion'].'</textarea>',
              "9" => $toltip,
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
    
      case 'tabla_compra_x_cliente':
        
        $rspta = $compra_grano->tabla_compra_x_cliente();
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
              "1" => '<button class="btn btn-info btn-sm" onclick="listar_facuras_cliente(' . $reg->idpersona . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
              "2" => $reg->nombres,
              "3" => "<center>$reg->cantidad</center>",
              "4" => $reg->celular,
              "5" => number_format($reg->total_compra, 2, '.', ','),
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
    
      case 'listar_detalle_compra_x_cliente':
        
        $rspta = $compra_grano->listar_detalle_comprax_provee($_GET["idproyecto"], $_GET["idproveedor"]);
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => '<center><button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg->idcompra_proyecto . ')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>',
              "2" => $reg->fecha_compra,
              "3" => $reg->tipo_comprobante,
              "4" => $reg->serie_comprobante,
              "5" => number_format($reg->total, 2, '.', ','),
              "6" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg->descripcion.'</textarea>',
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
    
      case 'ver_detalle_compras':
        
        $rspta = $compra_grano->ver_compra($_GET['id_compra']);
        $rspta2 = $compra_grano->ver_detalle_compra($_GET['id_compra']);

        $subtotal = 0;    $ficha = ''; 

        $inputs = '<!-- Tipo de Empresa -->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="idproveedor">Proveedor</label>
              <h5 class="form-control form-control-sm" >'.$rspta['data']['razon_social'].'</h5>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Fecha </label>
              <span class="form-control form-control-sm"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;&nbsp;'.format_d_m_a($rspta['data']['fecha_compra']).' </span>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Glosa </label>
              <span class="form-control form-control-sm">'.$rspta['data']['glosa'].' </span>
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

        $rspta = $compra_grano->mostrar_compra_para_editar($idcompra_proyecto);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
    
      break;      
      
      // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  :::::::::::::::::::::::::: 
      case 'tbla_comprobantes_compra':
        $cont_compra = $_GET["num_orden"];
        $id_compra = $_GET["id_compra"];
        $rspta = $compra_grano->tbla_comprobantes( $id_compra );
        //Vamos a declarar un array
        $data = []; $cont = 1;        
        
        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont,
              "1" => '<div class="text-nowrap">'.
              ' <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_editar_comprobante(' . $reg->idfactura_compra_insumo .','.$id_compra.', \''.$reg->comprobante.'\', \''.$cont.'. '.date("d/m/Y h:i:s a", strtotime($reg->updated_at)).'\')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>'.              
              ' <a class="btn btn-info btn-sm " href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'"  download="'.$cont_compra.'·'.$cont.' '.removeSpecialChar((empty($reg->serie_comprobante) ?  " " :  ' ─ '.$reg->serie_comprobante).' ─ '.$reg->razon_social).' ─ '. format_d_m_a($reg->fecha_compra).'" data-toggle="tooltip" data-original-title="Descargar" ><i class="fas fa-cloud-download-alt"></i></a>' .              
              ' <button type="button" class="btn btn-danger btn-sm" onclick="eliminar_comprobante_insumo(' . $reg->idfactura_compra_insumo .', \''.encodeCadenaHtml($cont.'. '.date("d/m/Y h:i:s a", strtotime($reg->updated_at))).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button> 
              </div>'.$toltip,
              "2" => '<a class="btn btn-info btn-sm" href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'" target="_blank" rel="noopener noreferrer"><i class="fas fa-receipt"></i></a>' ,
              "3" => $reg->updated_at,
            ];
            $cont++;
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

      case 'guardaryeditar_comprobante':
        // COMPROBANTE
        if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {

          $doc_comprobante = $_POST["doc_old_1"];
          $flat_comprob = false;

        } else {

          $ext1 = explode(".", $_FILES["doc1"]["name"]);
          $flat_comprob = true;    
          $doc_comprobante = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);    
          move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/compra_insumo/comprobante_compra/" . $doc_comprobante);
        }        

        if ( empty($idfactura_compra_insumo) ) {
          // agregar un documento
          $rspta = $compra_grano->agregar_comprobante($id_compra_proyecto, $doc_comprobante);    
          echo json_encode($rspta, true);
        } else {
          //Borramos el comprobante
          if ($flat_comprob == true) {
            $datos_f1 = $compra_grano->obtener_comprobante($idfactura_compra_insumo);    
            $doc1_ant = $datos_f1['data']->fetch_object()->comprobante;    
            if (!empty($doc1_ant) ) { unlink("../dist/docs/compra_insumo/comprobante_compra/" . $doc1_ant); }
          }
          // editamos un documento existente
          $rspta = $compra_grano->editar_comprobante($idfactura_compra_insumo, $doc_comprobante);    
          echo json_encode($rspta, true);
        }
    
      break;

      case 'eliminar_comprobante':
        $rspta = $compra_grano->eliminar_comprobante($_GET["id_tabla"]);    
        echo json_encode($rspta, true);
      break;

      case 'desactivar_comprobante':
        $rspta = $compra_grano->desactivar_comprobante($_GET["id_tabla"]);    
        echo json_encode($rspta, true);
      break;

      case 'ver_comprobante_compra':

        $rspta = $compra_grano->comprobantes_compra($_POST['id_compra']);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
    
      break; 

      // :::::::::::::::::::::::::: S E C C I O N   P A G O  ::::::::::::::::::::::::::     

      case 'guardaryeditar_pago':
    
        // imgen de perfil
        if (!file_exists($_FILES['doc3']['tmp_name']) || !is_uploaded_file($_FILES['doc3']['tmp_name'])) {
    
          $imagen1 = $_POST["doc_old_3"];
    
          $flat_img1 = false;
    
        } else {
    
          $ext1 = explode(".", $_FILES["doc3"]["name"]);
    
          $flat_img1 = true;
    
          $imagen1 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);
    
          move_uploaded_file($_FILES["doc3"]["tmp_name"], "../dist/docs/compra_insumo/comprobante_pago/" . $imagen1);
        }
    
        if (empty($idpago_compras)) {
    
          $rspta = $compra_grano->insertar_pago( $idcompra_proyecto_p, $idproveedor_pago, $beneficiario_pago, $forma_pago, $tipo_pago, 
          $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 );
    
          echo json_encode($rspta, true);
    
        } else {
    
          // validamos si existe LA IMG para eliminarlo
          if ($flat_img1 == true) {
    
            $datos_f1 = $compra_grano->obtenerComprobanteCompra($idpago_compras);
    
            $img1_ant = $datos_f1['data']->fetch_object()->imagen;
    
            if ($img1_ant != "") {
    
              unlink("../dist/docs/compra_insumo/comprobante_pago/" . $img1_ant);
            }
          }
    
          $rspta = $compra_grano->editar_pago( $idpago_compras, $idcompra_proyecto_p, $idproveedor_pago, $beneficiario_pago, $forma_pago, $tipo_pago, 
          $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 );
    
          echo json_encode($rspta, true);
        }
      break;

      //MOSTRANDO DATOS DE PROVEEDOR
      case 'most_datos_prov_pago':

        $idcompra_proyecto = $_POST["idcompra_proyecto"];
        $rspta = $compra_grano->most_datos_prov_pago($idcompra_proyecto);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);

      break;
    
      case 'desactivar_pagos':

        $rspta = $compra_grano->desactivar_pagos($_GET["idpago_compras"]);

        echo json_encode($rspta, true);

      break;
    
      case 'activar_pagos':

        $rspta = $compra_grano->activar_pagos($_GET["idpago_compras"]);

        echo json_encode($rspta, true);

      break;

      case 'eliminar_pago_compra':
        
        $rspta = $compra_grano->eliminar_pagos($_GET["idpago_compras"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'listar_pagos_proveedor':
        $idcompra_proyecto = $_GET["idcompra_proyecto"];
            
        $rspta = $compra_grano->listar_pagos($idcompra_proyecto);
        //Vamos a declarar un array
          
        $data = []; $cont = 1;
        $suma = 0;
        
        if ($rspta['status'] == true) {
          while ($reg = $rspta['data']->fetch_object()) {
      
            $suma = $suma + $reg->monto;                 
      
            $imagen = (empty($reg->imagen) ? '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>' :  '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher(\'' .  $reg->imagen .  '\', \'' .  $reg->fecha_pago . '\')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
            
            $data[] = [
              "0" =>$cont++,
              "1" => $reg->estado
                ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_compras . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_pago_compra(' . $reg->idpago_compras .', \''.$reg->beneficiario. '\')"><i class="fas fa-skull-crossbones"></i> </button>'      
                  : '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_compras . ')"><i class="fa fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar_pagos(' . $reg->idpago_compras . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->forma_pago,
              "3" => '<div class="user-block">
                <span class="username ml-0"><p class="text-primary m-b-02rem" >'. $reg->beneficiario .'</p></span>
                <span class="description ml-0"><b>'. $reg->banco .'</b>: '. $reg->cuenta_destino .' </span>
                <span class="description ml-0"><b>Titular: </b>: '. $reg->titular_cuenta .' </span>            
              </div>',             
              "4" => $reg->fecha_pago,
              "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
              "6" => $reg->numero_operacion,
              "7" => number_format($reg->monto, 2, '.', ','),
              "8" => $imagen,
              "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
            ];
          }
          //$suma=array_sum($rspta->fetch_object()->monto);
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
            "data" => $data,
            "suma" => $suma,
          ];
          
          echo json_encode($results, true);
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
        
      break;
    
      case 'listar_pagos_compra_prov_con_dtracc':
        
        $idcompra_proyecto = $_GET["idcompra_proyecto"];
    
        $tipo_pago = 'Proveedor';
        
        $rspta = $compra_grano->listar_pagos_compra_prov_con_dtracc($idcompra_proyecto, $tipo_pago);
        //Vamos a declarar un array   
        $data = []; $cont  =1;
    
        $imagen = '';
        
        if ($rspta['status'] == true) {
          while ($reg = $rspta['data']->fetch_object()) {
      
            $imagen = (empty($reg->imagen) ? '<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>' : '<div><center><a type="btn btn-danger" class="" href="#" onclick="ver_modal_vaucher(' . "'" . $reg->imagen . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
      
            $data[] = [
              "0" =>$cont++,
              "1" => $reg->estado
                ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_compras . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_pago_compra(' . $reg->idpago_compras . ')"><i class="fas fa-skull-crossbones"></i> </button>'
                : '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_compras . ')"><i class="fa fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar_pagos(' . $reg->idpago_compras . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->forma_pago,
              "3" => '<div class="user-block">
                <span class="username ml-0" ><p class="text-primary m-b-02rem" >'. $reg->beneficiario .'</p></span>
                <span class="description ml-0" ><b>'. $reg->banco .'</b>: '. $reg->cuenta_destino .' </span> 
                <span class="description ml-0" ><b>Titular: </b>: '. $reg->titular_cuenta .' </span>            
              </div>',
              "4" => $reg->fecha_pago,
              "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
              "6" => $reg->numero_operacion,
              "7" => number_format($reg->monto, 2, '.', ','),
              "8" => $imagen,
              "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
            ];
          }
          //$suma=array_sum($rspta->fetch_object()->monto);
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
    
      case 'listar_pgs_detrac_detracc_cmprs':
        //$_GET["nube_idproyecto"]
        $idcompra_proyecto = $_GET["idcompra_proyecto"];
    
        $tipo_pago = 'Detraccion';
        
        $rspta = $compra_grano->listar_pagos_compra_prov_con_dtracc($idcompra_proyecto, $tipo_pago);
        //Vamos a declarar un array
        
        $data = []; $cont = 1;
        
        if ($rspta['status'] == true) {
          while ($reg = $rspta['data']->fetch_object()) {

            $imagen = (empty($reg->imagen) ? '<div><center><a type="btn btn-danger" class=""><i class="far fa-times-circle fa-2x"></i></a></center></div>' : '<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_vaucher(' . "'" . $reg->imagen . "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
                  
            $data[] = [
              "0" => $cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_compras . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_pago_compra(' . $reg->idpago_compras . ')"><i class="fas fa-skull-crossbones"></i> </button>'
                : '<button class="btn btn-warning btn-sm" onclick="mostrar_pagos(' . $reg->idpago_compras . ')"><i class="fa fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar_pagos(' . $reg->idpago_compras . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->forma_pago,
              "3" => '<div class="user-block">
                <span class="username ml-0" ><p class="text-primary m-b-02rem" >'. $reg->beneficiario .'</p></span>
                <span class="description ml-0" ><b>'. $reg->banco .'</b>: '. $reg->cuenta_destino .' </span> 
                <span class="description ml-0" ><b>Titular: </b>: '. $reg->titular_cuenta .' </span>            
              </div>',
              "4" => $reg->fecha_pago,
              "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.(empty($reg->descripcion) ? '- - -' : $reg->descripcion ).'</textarea>',
              "6" => $reg->numero_operacion,
              "7" => number_format($reg->monto, 2, '.', ','),
              "8" => $imagen,
              "9" => $reg->estado ? '<span class="text-center badge badge-success">Activado</span>' . $toltip : '<span class="text-center badge badge-danger">Desactivado</span>' . $toltip,
            ];
          }
      
          //$suma=array_sum($rspta->fetch_object()->monto);
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
    
      case 'suma_total_pagos':

        $idcompra_proyecto = $_POST["idcompra_proyecto"];
        
        $rspta = $compra_grano->suma_total_pagos($idcompra_proyecto);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);

      break;
    
      //----suma total de pagos con detraccion-----
      case 'suma_total_pagos_prov':

        $idcompra_proyecto = $_POST["idcompra_proyecto"];

        $tipopago = 'Proveedor';
    
        $rspta = $compra_grano->suma_total_pagos_detraccion($idcompra_proyecto, $tipopago);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
    
      break;
    
      case 'suma_total_pagos_detracc':

        $idcompra_proyecto = $_POST["idcompra_proyecto"];

        $tipopago = 'Detraccion';
    
        $rspta = $compra_grano->suma_total_pagos_detraccion($idcompra_proyecto, $tipopago);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
    
      break;
    
      //---- fin suma total de pagos con detraccion-----
      case 'total_costo_parcial_pago':
        $idmaquinaria = $_POST["idmaquinaria"];
        $idproyecto = $_POST["idproyecto"];
    
        $rspta = $compra_grano->total_costo_parcial_pago($idmaquinaria, $idproyecto);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
    
      break;
    
      case 'mostrar_pagos':

        $rspta = $compra_grano->mostrar_pagos($idpago_compras);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);

      break;    
    
      // ::::::::::::::::::::::::::::::::::::::::: S I N C R O N I Z A R  :::::::::::::::::::::::::::::::::::::::::
      case 'sincronizar_comprobante':

        $rspta = $compra_grano->sincronizar_comprobante();
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