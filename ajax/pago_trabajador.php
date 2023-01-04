<?php

  ob_start();

  if (strlen(session_id()) < 1) {

    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['pago_trabajador'] == 1) {

      require_once "../modelos/pago_trabajador.php";

      require_once "../modelos/Trabajador.php";

      $trabajador = new Trabajador();

      $pago_trabajador = new PagoTrabajador;

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $imagen_error = "this.src='../dist/svg/user_default.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      
      $idpago_trabajador	  	= isset($_POST["idpago_trabajador"])? limpiarCadena($_POST["idpago_trabajador"]):"";
      $idtrabajador 		      = isset($_POST["nombre_trabajador"])? limpiarCadena($_POST["nombre_trabajador"]):"";
      $fecha_pago		      = isset($_POST["fecha_pago"])? limpiarCadena($_POST["fecha_pago"]):"";
      $monto		    = isset($_POST["monto_pago"])? limpiarCadena($_POST["monto_pago"]):"";
      $descripcion		    = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
      
      //$imagen1			    = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";
      $comprobante			    = isset($_POST["comprobante"])? limpiarCadena($_POST["comprobante"]):"";
      switch ($_GET["op"]) {

        case 'guardaryeditar':
          
          // imgen de perfil
          if (!file_exists($_FILES['comprobante']['tmp_name']) || !is_uploaded_file($_FILES['comprobante']['tmp_name'])) {

						$imagen1=$_POST["comprobante_actual"]; $flat_img1 = false;

					} else {

						$ext1 = explode(".", $_FILES["comprobante"]["name"]); $flat_img1 = true;						

            $imagen1 = $date_now .' '. rand(0, 20) . round(microtime(true)) . rand(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["comprobante"]["tmp_name"], "../dist/docs/persona/perfil/" . $imagen1);
						
					}

          if (empty($idpago_trabajador)){
            
            $rspta=$pago_trabajador->insertar($idtrabajador,$fecha_pago, $monto, $descripcion, $imagen1);
            
            echo json_encode($rspta, true);
  
          }else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {
              $datos_f1 = $pago_trabajador->obtenerImg($idtrabajador);
              $img1_ant = $datos_f1['data']['imagen_perfil'];
              if ($img1_ant != "") { unlink("../dist/docs/persona/perfil/" . $img1_ant);  }
            }            

            // editamos un trabajador existente
            $rspta=$pago_trabajador->editar($idpago_trabajador, $idtrabajador, $fecha_pago, $monto, $descripcion, $imagen1);
            
            echo json_encode($rspta, true);
          }            

        break;

        case 'desactivar':

          $rspta=$pago_trabajador->desactivar($_GET["id_tabla"]);

          echo json_encode($rspta, true);

        break;

        case 'eliminar':

          $rspta=$pago_trabajador->eliminar($_GET["id_tabla"]);

          echo json_encode($rspta, true);

        break;

        case 'mostrar':

          $rspta=$pago_trabajador->mostrar($idpago_trabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;

        case 'tbla_trabajador':          

          $rspta=$trabajador->tbla_principal();
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $value) {             
          
              $data[]=array(
                "0"=>$cont++,
                "1"=> ' <button class="btn btn-info btn-sm" onclick="datos_trabajador('.$value['idpersona'].')"data-toggle="tooltip" data-original-title="ver datos"><i class="far fa-eye"></i></button>',
                "2"=>'<div class="user-block">
                  <img class="img-circle" src="../dist/docs/persona/perfil/'. $value['foto_perfil'] .'" alt="User Image" onerror="'.$imagen_error.'">
                  <span class="username"><p class="text-primary m-b-02rem" >'. $value['nombres'] .'</p></span>
                  <span class="description">'. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>
                  </div>',
                "3"=> $value['cargo'],
                "4"=> '<div>
                <span class="description">Mensual: <b>'. number_format($value['sueldo_mensual']) .'</b> </span><br>
                <span class="description">Diario: <b> '. $value['sueldo_diario'] .'</b> </span>
                </div>',
                "5"=>'<a href="tel:+51'.quitar_guion($value['celular']).'" data-toggle="tooltip" data-original-title="Llamar al trabajador.">'. $value['celular'] . '</a>',
              "6"=> '<button class="btn btn-lg" onclick="tbla_pago_trabajador(' . $value['idpersona'] . ',\''.$value['nombres'].'\',\''.$value['sueldo_mensual'].'\', \''.$value['cargo'].'\')" ><i class="fas fa-hand-holding-usd fas-xl" style="color: #1a8722;"></i></button>',
                "7"=> '<b>'.$value['banco'] .': </b>'. $value['cuenta_bancaria'] .' <br> <b>CCI: </b>'.$value['cci'],
                "8"=>(($value['estado'])?'<span class="text-center badge badge-success">Activado</span>': '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
                "9"=> $value['nombres'],
                "10"=> $value['tipo_documento'],
                "11"=> $value['numero_documento'],
                "12"=> format_d_m_a($value['fecha_nacimiento']),
                "13"=>calculaedad($value['fecha_nacimiento']),
                "14"=> $value['banco'],
                "15"=> $value['cuenta_bancaria'],
                "16"=> $value['cci'],
                "17"=> number_format($value['sueldo_mensual']),
                "18"=> $value['sueldo_diario'],

              );
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);

          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break;

        case 'tbla_pago_trabajador':          

          $rspta=$pago_trabajador->tbla_principal($_GET["idpago_trabajador"]);
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $value) {             
          
              $data[]=array(
                "0"=>$cont++,
                "1"=> $value['anio'],
                "2"=>$value['nombre_mes'],
                "3"=> $_GET["sueldo_mensual"],
                "4"=> $value['monto_pagado'],
                "5"=> '<button type="button" class="btn btn-success" onclick="ver_desglose_de_pago('.$value['nombre_mes'].');" >Ver Detalle</button>',
                

              );
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);

          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break; 
        
          

        case 'verdatos':
          $rspta=$pago_trabajador->verdatos($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break; 
        case 'datos_trabajador':
          $rspta=$pago_trabajador->datos_trabajador($_POST["idtrabajador"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;     
        

        case 'formato_banco':           
          $rspta=$pago_trabajador->formato_banco($_POST["idbanco"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);           
        break;

        /* =========================== S E C C I O N   R E C U P E R A R   B A N C O S =========================== */
        case 'recuperar_banco':           
          $rspta=$pago_trabajador->recuperar_banco();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);           
        break;

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;

      }

      //Fin de las validaciones de acceso
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

  function calculaedad($fechanacimiento){
    $ano_diferencia = '-';
    if (empty($fechanacimiento) || $fechanacimiento=='0000-00-00') { } else{
      list($ano,$mes,$dia) = explode("-",$fechanacimiento);
      $ano_diferencia  = date("Y") - $ano;
      $mes_diferencia = date("m") - $mes;
      $dia_diferencia   = date("d") - $dia;
      if ($dia_diferencia < 0 || $mes_diferencia < 0)
        $ano_diferencia--;
    } 
    
    return $ano_diferencia;
  }

  ob_end_flush();

?>
