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
      $imagen_error = "this.src='../dist/svg/404-v2.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_integra/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');
      
      $idproducto     = isset($_POST["idproducto"]) ? limpiarCadena($_POST["idproducto"]) : "" ;
      $idcategoria_producto  = isset($_POST["categoria_producto"]) ? limpiarCadena($_POST["categoria_producto"]) : "" ;
      $unidad_medida          = isset($_POST["unidad_medida"]) ? limpiarCadena($_POST["unidad_medida"]) : "" ;
      $nombre         = isset($_POST["nombre_producto"]) ? encodeCadenaHtml($_POST["nombre_producto"]) : "" ;
      $marca         = isset($_POST["marca"]) ? encodeCadenaHtml($_POST["marca"]) : "" ;
      $contenido_neto          = isset($_POST["contenido_neto"]) ? limpiarCadena($_POST["contenido_neto"]) : "" ;
      $precio_unitario= isset($_POST["precio_unitario"]) ? limpiarCadena($_POST["precio_unitario"]) : "" ;      
      $stock = isset($_POST["stock"]) ? limpiarCadena($_POST["stock"]) : "" ;     
      $descripcion    = isset($_POST["descripcion"]) ? encodeCadenaHtml($_POST["descripcion"]) : "" ;

      $imagen1 = isset($_POST["foto1"]) ? limpiarCadena($_POST["foto1"]) : "" ;

      switch ($_GET["op"]) {

        case 'guardaryeditar':
          // imgen
          if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {

            $imagen1 = $_POST["foto1_actual"];

            $flat_img1 = false;

          } else {

            $ext1 = explode(".", $_FILES["foto1"]["name"]);

            $flat_img1 = true;

            $imagen1 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/material/img_perfil/" . $imagen1);
          }

          if (empty($idproducto)) {
           
            $rspta = $materiales->insertar($idcategoria_producto, $unidad_medida, $nombre, $marca, $contenido_neto, quitar_formato_miles($precio_unitario), $stock, $descripcion, $imagen1 );
            
            echo json_encode( $rspta, true);

          } else {

            // validamos si existe LA IMG para eliminarlo
          
            if ($flat_img1 == true) {

              $datos_f1 = $materiales->obtenerImg($idproducto);

              $img1_ant = $datos_f1['data']->fetch_object()->imagen;

              if ( validar_url_completo($scheme_host. "dist/docs/material/img_perfil/" . $img1_ant)  == 200) {
                unlink("../dist/docs/material/img_perfil/" . $img1_ant);
              }
            }
            
            $rspta = $materiales->editar($idproducto, $idcategoria_producto, $unidad_medida, $nombre, $marca, $contenido_neto, quitar_formato_miles($precio_unitario), $stock, $descripcion, $imagen1 );
            
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

              $imagen = (empty($reg->imagen) ? 'producto-sin-foto.svg' : $reg->imagen );
              $clas_stok = "";

              if ($reg->stock == 0 && $reg->stock <= 0) {
                $clas_stok = 'badge-danger';
              }else if ($reg->stock > 0 && $reg->stock <= 10) {
                $clas_stok = 'badge-warning';
              }else if ($reg->stock > 10) {
                $clas_stok = 'badge-success';
              }
              
              $data[] = [
                "0"=>$cont++,
                "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idproducto . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->idproducto .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>'. 
                ' <button class="btn btn-info btn-sm" onclick="verdatos('.$reg->idproducto.')" data-toggle="tooltip" data-original-title="Ver datos"><i class="far fa-eye"></i></button>' : 
                '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idproducto . ')"><i class="fa fa-pencil-alt"></i></button>',
                "2" => $reg->idproducto,
                "3" => '<div class="user-block">'.
                  '<img class="profile-user-img img-responsive img-circle cursor-pointer" src="../dist/docs/material/img_perfil/' . $imagen . '" alt="user image" onerror="'.$imagen_error.'" onclick="ver_perfil(\'../dist/docs/material/img_perfil/' . $imagen . '\', \''.encodeCadenaHtml($reg->nombre_medida).'\');" data-toggle="tooltip" data-original-title="Ver imagen">
                  <span class="username"><p class="mb-0">' . $reg->nombre . '</p></span>
                  <span class="description"><b>Marca: </b>' . $reg->marca . '</span>
                </div>',
                "4" =>  $reg->categoria,
                "5" => $reg->nombre_medida,     
                "6" => $reg->precio_unitario,
                "7" =>  '<span class="badge '.$clas_stok.' font-size-14px">'.$reg->stock.'</span>',
                "8" => $reg->contenido_neto,
                "9" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',

                "10" => $reg->nombre,
                "11" => $reg->marca,
                
                
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
        case 'lista_de_categorias':

          $rspta = $producto->lista_de_categorias();
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true);

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
