<?php
  ob_start();

  if (strlen(session_id()) < 1) {

    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {     
    
    require_once "../modelos/Ajax_general.php";
    require_once "../modelos/Producto.php";
    require_once "../modelos/Compra_producto.php";
    require_once "../modelos/Venta_producto.php";
    
    $ajax_general = new Ajax_general();
    $compra_insumos = new Producto();
    $compra_producto = new Compra_producto();
    $venta_producto = new Venta_producto();

    $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_integra/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');
    $imagen_error = "this.src='../dist/svg/404-v2.svg'";
    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    switch ($_GET["op"]) {       

      // buscar datos de RENIEC
      case 'reniec':

        $dni = $_POST["dni"];

        $rspta = $ajax_general->datos_reniec($dni);

        echo json_encode($rspta);

      break;
      
      // buscar datos de SUNAT
      case 'sunat':

        $ruc = $_POST["ruc"];

        $rspta = $ajax_general->datos_sunat($ruc);

        echo json_encode($rspta, true);

      break;

      /* ══════════════════════════════════════ C O M P R O B A N T E  ══════════════════════════════════════ */
      case 'autoincrement_comprobante':
        $rspta = $ajax_general->autoincrement_comprobante();    
        echo json_encode($rspta, true);    
      break;
      
      /* ══════════════════════════════════════ T R A B A J A D O R  ══════════════════════════════════════ */
      case 'select2Trabajador': 

        $rspta = $ajax_general->select2_trabajador();  $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {

            $data .= '<option  value=' . $value['id'] . ' title="'.$value['imagen_perfil'].'" cargo_trabajador="'.$value['cargo_trabajador'].'" sueldo_mensual="'. $value['sueldo_mensual'].'" >' . $cont++ . '. ' . $value['nombre'] .' - '. $value['numero_documento'] . '</option>';
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

      case "select2TipoTrabajador":

        $rspta = $ajax_general->select2_tipo_trabajador(); $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {

            $data .= '<option  value=' . $value['idtipo_trabajador']  . '>' . $value['nombre'] . '</option>';
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

      case 'select2CargoTrabajdorId':         
         
        $rspta=$ajax_general->select2_cargo_trabajador_id( $_GET["idtipo"] ); $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {

            $data .= '<option  value=' . $value['idcargo_trabajador']  . '>' . $value['nombre'] .'</option>';
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
         
        $rspta=$ajax_general->select2_cargo_trabajador( ); $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {
            $data .= '<option  value=' . $value['idcargo_trabajador']  . '>' . $value['nombre'] .'</option>';
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

      case 'select2OcupacionTrabajador':         
         
        $rspta=$ajax_general->select2_ocupacion_trabajador( ); $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {

            $data .= '<option  value=' .  $value['id']  . '>' .  $value['nombre'] .'</option>';
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

      /* ══════════════════════════════════════ C L I E N T E  ══════════════════════════════════════ */
      case 'select2Cliente': 

        $rspta = $ajax_general->select2_cliente();  $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {
            $es_socio = $value['es_socio'] ? 'SOCIO': 'NO SOCIO' ;
            $data .= '<option  value=' . $value['idpersona'] . ' title="'.$value['foto_perfil'].'" ruc_dni="'.$value['numero_documento'].'">' . $cont++ . '. ' . $value['nombres'] .' - '. $value['numero_documento'] . ' - ' . $es_socio . '</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option value="1" ruc="">CLIENTES VARIOS</option>' . $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        } 
      break;

      /* ══════════════════════════════════════ TIPO PERSONA  ══════════════════════════════════════ */
      case 'select2TipoPersona': 

        $rspta = $ajax_general->select2_tipo_persona();  $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {
            $data .= '<option  value=' . $value['idpersona'] . ' >' . $value['nombre']  . '</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' =>  $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        } 
      break;

      /* ══════════════════════════════════════ P R O V E E D O R E S --- C L I E N T E S  ══════════════════════════════════════ */
      case 'select2Proveedor_cliente': 
    
        $rspta=$ajax_general->select2_proveedor_cliente($_GET['id']);  $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {  

            $data .= '<option value="' .  $value['idpersona'] . '">' .$cont++.'. '.  $value['nombres'] .' - '.  $value['numero_documento'] . '</option>';      
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option value="1" ruc="">Anónimo - 00000000000</option>' . $data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;    
      
      /* ══════════════════════════════════════ B A N C O  ══════════════════════════════════════ */
      case 'select2Banco': 
    
        $rspta = $ajax_general->select2_banco(); $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {    
            
            $data .= '<option value=' . $value['id'] . ' title="'.$value['icono'].'">' . $value['nombre'] . (empty($value['alias']) ? "" : ' -'. $value['alias'] ) .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option value="1">SIN BANCO</option>'.$data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;

      case 'formato_banco':
               
        $rspta=$ajax_general->formato_banco($_POST["idbanco"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
         
      break;
      
      /* ══════════════════════════════════════ C O L O R ══════════════════════════════════════ */
      case 'select2Color': 
    
        $rspta = $ajax_general->select2_color(); $cont = 1; $data = "";
        
        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {    

            $data .= '<option value=' . $value['id'] . ' title="'.$value['hexadecimal'].'" >' . $value['nombre'] .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option value="1" title="#ffffff00" >SIN COLOR</option>'.$data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;
      
      /* ══════════════════════════════════════ U N I D A D   D E   M E D I D A  ══════════════════════════════════════ */
      case 'select2UnidaMedida': 
    
        $rspta = $ajax_general->select2_unidad_medida(); $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {    

            $data .= '<option value=' . $value['id'] . '>' . $value['nombre'] . ' - ' . $value['abreviatura'] .'</option>';
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
      
      /* ══════════════════════════════════════ C A T E G O R I A ══════════════════════════════════════ */
      case 'select2Categoria': 
    
        $rspta = $ajax_general->select2_categoria(); $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {  

            $data .= '<option value=' . $value['id'] . '>' . $value['nombre'] .'</option>';
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => '<option value="1">NINGUNO</option>'.$data, 
          );
  
          echo json_encode($retorno, true);

        } else {

          echo json_encode($rspta, true); 
        }
      break;

      case 'select2Categoria_all': 
    
        $rspta = $ajax_general->select2_categoria_all(); $cont = 1; $data = "";

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {  

            $data .= '<option value=' . $value['id'] . '>' . $value['nombre'] .'</option>';
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


      /* ══════════════════════════════════════ P R O D U C T O ══════════════════════════════════════ */

      case 'tblaProductos':
          
        $rspta = $ajax_general->tblaProductos(); 

        $datas = []; 
        

        if ($rspta['status'] == true) {

          while ($reg = $rspta['data']->fetch_object()) {

            $img_parametro = ""; $img = "";  $clas_stok = "";
  
            if (empty($reg->imagen)) {
              $img = '../dist/docs/producto/img_perfil/producto-sin-foto.svg';
            } else {
              $img = '../dist/docs/producto/img_perfil/' . $reg->imagen;
              $img_parametro = $reg->imagen;
            }
            if ( $reg->stock <= 0) {
              $clas_stok = 'badge-danger';
            }else if ($reg->stock > 0 && $reg->stock <= 10) {
              $clas_stok = 'badge-warning';
            }else if ($reg->stock > 10) {
              $clas_stok = 'badge-success';
            }

            $datas[] = [
              "0" => '<button class="btn btn-warning" onclick="agregarDetalleComprobante(' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre, ENT_QUOTES) . '\', \'' . $reg->nombre_medida . '\',\'' . $reg->categoria . '\',\'' . $reg->precio_unitario . '\',\'' . $img_parametro . '\',\'' .$reg->stock. '\')" data-toggle="tooltip" data-original-title="Agregar Activo"><span class="fa fa-plus"></span></button>',
              "1" => '<div class="user-block w-250px">'.
                '<img class="profile-user-img img-responsive img-circle cursor-pointer" src="' . $img . '" alt="user image" onerror="' . $imagen_error . '" onclick="ver_img_producto(\'' . $img . '\', \''.encodeCadenaHtml($reg->nombre).'\');">'.
                '<span class="username"><p class="mb-0" >' . $reg->nombre . '</p></span>
                <span class="description"><b>Categoria: </b>' . $reg->categoria . '</span>'.
              '</div>',
              "2" =>'<span class="badge '.$clas_stok.' font-size-14px" stock="'.$reg->stock.'" id="table_stock_'.$reg->idproducto.'">'.$reg->stock.'</span>',
              "3" => number_format($reg->precio_unitario, 2, '.', ','),
              "4" => '<textarea class="form-control textarea_datatable" cols="30" rows="1">' . $reg->descripcion . '</textarea>'. $toltip,
            ];
          }
  
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($datas), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($datas), //enviamos el total registros a visualizar
            "aaData" => $datas,
          ];

          echo json_encode($results, true);
        } else {

          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
    
      break;
      
      /* ══════════════════════════════════════ C O M P R A   D E   P R O D U C T O ════════════════════════════ */

      case 'ver_detalle_compras':
        $id_producto  = isset($_GET["id_producto"]) ? limpiarCadena($_GET["id_producto"]) : "";
        $class_resaltar_producto = ( empty($id_producto) ? "" : "bg-warning") ;

        $rspta = $compra_producto->ver_compra($_GET['id_compra']);
        $subtotal = 0;    $ficha = '';

        $inputs = '<!-- Tipo de Empresa -->
          <div class="col-lg-8">
            <div class="form-group">
              <label class="font-size-15px" for="idproveedor">Proveedor</label>
              <h5 class="form-control form-control-sm" >'.$rspta['data']['compra']['nombres'].'</h5>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-4">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Fecha </label>
              <span class="form-control form-control-sm"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;&nbsp;'.format_d_m_a($rspta['data']['compra']['fecha_compra']).' </span>
            </div>
          </div>
          <!-- Tipo de comprobante -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="tipo_comprovante">Tipo Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['compra']['tipo_comprobante'])) ? '- - -' :  $rspta['data']['compra']['tipo_comprobante'])  .' </span>
            </div>
          </div>
          <!-- serie_comprovante-->
          <div class="col-lg-2">
            <div class="form-group">
              <label class="font-size-15px" for="serie_comprovante">N° de Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['compra']['serie_comprobante'])) ? '- - -' :  $rspta['data']['compra']['serie_comprobante']).' </span>
            </div>
          </div>
          <!-- IGV-->
          <div class="col-lg-1 " >
            <div class="form-group">
              <label class="font-size-15px" for="igv">IGV</label>
              <span class="form-control form-control-sm"> '.$rspta['data']['compra']['val_igv'].' </span>                                 
            </div>
          </div>
          <!-- Descripcion-->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="descripcion">Descripción </label> <br />
              <textarea class="form-control form-control-sm" readonly rows="1">'.((empty($rspta['data']['compra']['descripcion'])) ? '- - -' :$rspta['data']['compra']['descripcion']).'</textarea>
            </div>
        </div>';


        $tbody = ""; $cont = 1;

        foreach ($rspta['data']['detalle'] as $key => $reg) {
          $bg_resaltar = ($id_producto == $reg['idproducto']? $class_resaltar_producto : "" );
          $img_product = '../dist/docs/producto/img_perfil/'. (empty($reg['imagen']) ? 'producto-sin-foto.svg' : $reg['imagen'] );
          $tbody .= '<tr class="filas">
            <td class="text-center p-6px"><span class="'. $bg_resaltar.'">' . $cont++ . '</span></td>
            <td class="text-left p-6px">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer '. $bg_resaltar.'" src="'.$img_product.'" alt="user image" onclick="ver_img_producto(\''.$img_product.'\', \'' . encodeCadenaHtml( $reg['nombre']) . '\', null)" onerror="this.src=\'../dist/svg/404-v2.svg\';" >
                <span class="username '. $bg_resaltar.'"><p class="mb-0 ">' . $reg['nombre'] . '</p></span>
                <span class="description '. $bg_resaltar.'"><b>Categoría: </b>' . $reg['categoria'] . '</span>
              </div>
            </td>
            <td class="text-left p-6px"><span class="'. $bg_resaltar.'">' . $reg['unidad_medida'] . '</span></td>
            <td class="text-center p-6px"><span class="'. $bg_resaltar.'">' . $reg['cantidad'] . '</span></td>		
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['precio_sin_igv'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['igv'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['precio_con_igv'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['precio_venta'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['descuento'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['subtotal'], 2, '.',',') .'</span></td>
          </tr>';
        }   

        $tabla_detalle = '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover" id="tabla_detalle_factura">
            <thead class="bg-color-28a745b5">
              <tr class="text-center hidden">
                <th class="p-10px">Proveedor:</th>
                <th class="text-center p-10px" colspan="9" >'.$rspta['data']['compra']['nombres'].'</th>
              </tr>
              <tr class="text-center hidden">                
                <th class="text-center p-10px" colspan="2" >'.((empty($rspta['data']['compra']['tipo_comprobante'])) ? '' :  $rspta['data']['compra']['tipo_comprobante']). ' ─ ' . ((empty($rspta['data']['compra']['serie_comprobante'])) ? '' :  $rspta['data']['compra']['serie_comprobante']) .'</th>
                <th class="p-10px">Fecha:</th>
                <th class="text-center p-10px" colspan="3" >'.format_d_m_a($rspta['data']['compra']['fecha_compra']).'</th>
              </tr>
              <tr class="text-center">
                <th class="text-center p-10px" >#</th>
                <th class="p-10px">Producto</th>
                <th class="p-10px">U.M.</th>
                <th class="p-10px">Cant.</th>
                <th class="p-10px">V/U</th>
                <th class="p-10px">IGV</th>
                <th class="p-10px">P/U</th>
                <th class="p-10px">P/V</th>
                <th class="p-10px">Desct.</th>
                <th class="p-10px">Subtotal</th>
              </tr>
            </thead>
            <tbody>'.$tbody.'</tbody>          
            <tfoot>
              <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h6 class="mt-1 mb-1 mr-1">'.$rspta['data']['compra']['tipo_gravada'].'</h6> </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['compra']['subtotal'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1">IGV('.( ( empty($rspta['data']['compra']['val_igv']) ? 0 : floatval($rspta['data']['compra']['val_igv']) )  * 100 ).'%)</h6>
                  </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['compra']['igv'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="8"></td>
                  <td class="p-0 text-right"> <h5 class="mt-1 mb-1 mr-1 font-weight-bold">TOTAL</h5> </td>
                  <td class="p-0 text-right">
                    <h5 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['compra']['total'], 2, '.',',') . '</h5>
                  </td>
                </tr>
            </tfoot>
          </table>
        </div> ';

        $retorno = ['status' => true, 'message' => 'todo oka', 'data' => $inputs . $tabla_detalle ,];
        echo json_encode( $retorno, true );

      break;

      /* ══════════════════════════════════════ V E N T A   D E   P R O D U C T O ════════════════════════════ */
      case 'ver_detalle_ventas':
        $id_producto  = isset($_GET["id_producto"]) ? limpiarCadena($_GET["id_producto"]) : "";
        $class_resaltar_producto = ( empty($id_producto) ? "" : "bg-warning") ;

        $rspta = $venta_producto->ver_compra($_GET['idventa_producto']);
        $subtotal = 0;    $ficha = '';

        $inputs = '<!-- Tipo de Empresa -->
          <div class="col-lg-8">
            <div class="form-group">
              <label class="font-size-15px" for="idproveedor">Proveedor</label>
              <h5 class="form-control form-control-sm" >'.$rspta['data']['venta']['nombres'].'</h5>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-4">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Fecha </label>
              <span class="form-control form-control-sm"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;&nbsp;'.format_d_m_a($rspta['data']['venta']['fecha_venta']).' </span>
            </div>
          </div>
          <!-- Tipo de comprobante -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="tipo_comprovante">Tipo Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['venta']['tipo_comprobante'])) ? '- - -' :  $rspta['data']['venta']['tipo_comprobante'])  .' </span>
            </div>
          </div>
          <!-- serie_comprovante-->
          <div class="col-lg-2">
            <div class="form-group">
              <label class="font-size-15px" for="serie_comprovante">N° de Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['venta']['serie_comprobante'])) ? '- - -' :  $rspta['data']['venta']['serie_comprobante']).' </span>
            </div>
          </div>
          <!-- IGV-->
          <div class="col-lg-1 " >
            <div class="form-group">
              <label class="font-size-15px" for="igv">IGV</label>
              <span class="form-control form-control-sm"> '.$rspta['data']['venta']['val_igv'].' </span>                                 
            </div>
          </div>
          <!-- Descripcion-->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="descripcion">Descripción </label> <br />
              <textarea class="form-control form-control-sm" readonly rows="1">'.((empty($rspta['data']['venta']['descripcion'])) ? '- - -' :$rspta['data']['venta']['descripcion']).'</textarea>
            </div>
        </div>';


        $tbody = ""; $cont = 1;

        foreach ($rspta['data']['detalle'] as $key => $reg) {
          $bg_resaltar = ($id_producto == $reg['idproducto']? $class_resaltar_producto : "" );
          $img_product = '../dist/docs/producto/img_perfil/'. (empty($reg['imagen']) ? 'producto-sin-foto.svg' : $reg['imagen'] );
          $tbody .= '<tr class="filas">
            <td class="text-center p-6px"><span class="'. $bg_resaltar.'">' . $cont++ . '</span></td>
            <td class="text-left p-6px">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer '. $bg_resaltar.'" src="'.$img_product.'" alt="user image" onclick="ver_img_producto(\''.$img_product.'\', \'' . encodeCadenaHtml( $reg['nombre']) . '\', null)" onerror="this.src=\'../dist/svg/404-v2.svg\';" >
                <span class="username '. $bg_resaltar.'"><p class="mb-0 ">' . $reg['nombre'] . '</p></span>
                <span class="description '. $bg_resaltar.'"><b>Categoría: </b>' . $reg['categoria'] . '</span>
              </div>
            </td>
            <td class="text-left p-6px"><span class="'. $bg_resaltar.'">' . $reg['unidad_medida'] . '</span></td>
            <td class="text-center p-6px"><span class="'. $bg_resaltar.'">' . $reg['cantidad'] . '</span></td>		
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['precio_sin_igv'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['igv'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['precio_con_igv'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['descuento'], 2, '.',',') . '</span></td>
            <td class="text-right p-6px"><span class="'. $bg_resaltar.'">' . number_format($reg['subtotal'], 2, '.',',') .'</span></td>
          </tr>';
        }   

        $tabla_detalle = '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover" id="tabla_detalle_factura">
            <thead class="bg-color-28a745b5">
              <tr class="text-center hidden">
                <th class="p-10px">Proveedor:</th>
                <th class="text-center p-10px" colspan="9" >'.$rspta['data']['venta']['nombres'].'</th>
              </tr>
              <tr class="text-center hidden">                
                <th class="text-center p-10px" colspan="2" >'.((empty($rspta['data']['venta']['tipo_comprobante'])) ? '' :  $rspta['data']['venta']['tipo_comprobante']). ' ─ ' . ((empty($rspta['data']['venta']['serie_comprobante'])) ? '' :  $rspta['data']['venta']['serie_comprobante']) .'</th>
                <th class="p-10px">Fecha:</th>
                <th class="text-center p-10px" colspan="3" >'.format_d_m_a($rspta['data']['venta']['fecha_venta']).'</th>
              </tr>
              <tr class="text-center">
                <th class="text-center p-10px" >#</th>
                <th class="p-10px">Producto</th>
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
                  <td class="p-0" colspan="7"></td>
                  <td class="p-0 text-right"> <h6 class="mt-1 mb-1 mr-1">'.$rspta['data']['venta']['tipo_gravada'].'</h6> </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['venta']['subtotal'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="7"></td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1">IGV('.( ( empty($rspta['data']['venta']['val_igv']) ? 0 : floatval($rspta['data']['venta']['val_igv']) )  * 100 ).'%)</h6>
                  </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['venta']['igv'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="7"></td>
                  <td class="p-0 text-right"> <h5 class="mt-1 mb-1 mr-1 font-weight-bold">TOTAL</h5> </td>
                  <td class="p-0 text-right">
                    <h5 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['venta']['total'], 2, '.',',') . '</h5>
                  </td>
                </tr>
            </tfoot>
          </table>
        </div> ';

        $retorno = ['status' => true, 'message' => 'todo oka', 'data' => $inputs . $tabla_detalle ,];
        echo json_encode( $retorno, true );

      break;

      default: 
        $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
      break;
    }
      
  }

  ob_end_flush();
?>
