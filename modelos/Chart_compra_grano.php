<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ChartCompraGrano
{
  //Implementamos nuestro constructor
  public function __construct() { }  

  //Implementar un método para mostrar los datos de un registro a modificar
  public function box_content_reporte() {
    $data = Array();

    $sql_1 = "SELECT COUNT(idpersona) as cant_cliente FROM compra_grano WHERE estado='1' AND estado_delete='1' GROUP BY idpersona";
    $cant_clientes = ejecutarConsultaSimpleFila($sql_1); if ($cant_clientes['status'] == false) { return $cant_clientes; }

    $sql_2 = "SELECT SUM(dcg.peso_neto) AS peso_neto 
    FROM detalle_compra_grano AS dcg, compra_grano AS cg 
    WHERE dcg.idcompra_grano = cg.idcompra_grano  AND dcg.tipo_grano = 'COCO' AND cg.estado = '1'  AND cg.estado_delete = '1'  GROUP BY dcg.tipo_grano;";
    $kilo_coco = ejecutarConsultaSimpleFila($sql_2);  if ($kilo_coco['status'] == false) { return $kilo_coco; }

    $sql_3 = "SELECT SUM(dcg.peso_neto) AS peso_neto 
    FROM detalle_compra_grano AS dcg, compra_grano AS cg 
    WHERE dcg.idcompra_grano = cg.idcompra_grano  AND dcg.tipo_grano = 'PERGAMINO' AND cg.estado = '1'  AND cg.estado_delete = '1'  GROUP BY dcg.tipo_grano;";
    $kilo_pergamino = ejecutarConsultaSimpleFila($sql_3);  if ($kilo_pergamino['status'] == false) { return $kilo_pergamino; }

    $sql_4 = "SELECT SUM(total_compra) as total_compra FROM compra_grano WHERE estado ='1' AND estado_delete ='1'";
    $total_compra = ejecutarConsultaSimpleFila($sql_4); if ($total_compra['status'] == false) { return $total_compra; }

    $data = array(
      'cant_clientes'   => (empty($cant_clientes['data']) ? 0 : $cant_clientes['data']['cant_cliente']),
      'kilo_coco'       => (empty($kilo_coco['data']) ? 0 : $kilo_coco['data']['peso_neto']),
      'kilo_pergamino'  => (empty($kilo_pergamino['data']) ? 0 : $kilo_pergamino['data']['peso_neto']),
      'total_compra'    => (empty($total_compra['data']) ? 0 : $total_compra['data']['total_compra']),      
    );

    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];
    
  }

  public function chart_linea($id_proyecto, $year_filtro, $mes_filtro, $dias_filtro) {
    $data_gasto = Array(); $data_pagos = Array();

    $producto_mas_vendido_nombre = Array(); $producto_mas_vendido_cantidad = Array();

    $factura_total = 0; $factura_aceptadas = 0; $factura_rechazadas = 0; $factura_eliminadas = 0; $factura_rechazadas_eliminadas = 0;

    $factura_total_gasto = 0; $factura_total_pago = 0;

    $productos_mas_vendidos = [];

    if ($year_filtro == null || $year_filtro == '' || $mes_filtro == null || $mes_filtro == null) {
      for ($i=1; $i <= 12 ; $i++) { 
        $sql_1 = "SELECT idpersona, SUM(total_compra) as total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
        ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
        FROM compra_grano  WHERE MONTH(fecha_compra)='$i' AND   YEAR(fecha_compra) = '$year_filtro'  AND estado='1' AND estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_1); if ($mes['status'] == false) { return $mes; }
        array_push($data_gasto, (empty($mes['data']) ? 0 : (empty($mes['data']['total_gasto']) ? 0 : floatval($mes['data']['total_gasto']) ) ));
  
        $sql_2 = "SELECT SUM(dcg.peso_neto) as peso_neto  
        FROM detalle_compra_grano as dcg, compra_grano as cg 
        WHERE dcg.idcompra_grano = cg.idcompra_grano AND MONTH(cg.fecha_compra)='$i' AND YEAR(cg.fecha_compra) = '$year_filtro' 
        AND cg.estado='1' AND cg.estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_2);  if ($mes['status'] == false) { return $mes; }
        array_push($data_pagos, (empty($mes['data']) ? 0 : (empty($mes['data']['peso_neto']) ? 0 : floatval($mes['data']['peso_neto']) ) ));       
  
      }
      $sql_3 = "SELECT COUNT(idcompra_grano) as factura_total FROM compra_grano WHERE  YEAR(fecha_compra) = '$year_filtro';";
      $factura_total = ejecutarConsultaSimpleFila($sql_3); if ($factura_total['status'] == false) { return $factura_total; }

      $sql_4 = "SELECT COUNT(idcompra_grano) as factura_aceptadas FROM compra_grano WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1';";
      $factura_aceptadas = ejecutarConsultaSimpleFila($sql_4); if ($factura_aceptadas['status'] == false) { return $factura_aceptadas; }

      $sql_5 = "SELECT COUNT(idcompra_grano) as factura_rechazadas FROM compra_grano WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='0' AND estado_delete='1';";
      $factura_rechazadas = ejecutarConsultaSimpleFila($sql_5); if ($factura_rechazadas['status'] == false) { return $factura_rechazadas; }

      $sql_6 = "SELECT COUNT(idcompra_grano) as factura_eliminadas FROM compra_grano WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='0';";
      $factura_eliminadas = ejecutarConsultaSimpleFila($sql_6); if ($factura_eliminadas['status'] == false) { return $factura_eliminadas; }

      $sql_7 = "SELECT COUNT(idcompra_grano) as factura_rechazadas_eliminadas FROM compra_grano WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='0' AND estado_delete='0';";
      $factura_rechazadas_eliminadas = ejecutarConsultaSimpleFila($sql_7); if ($factura_rechazadas_eliminadas['status'] == false) { return $factura_rechazadas_eliminadas; }

      // -------------------------
      $sql_8 = "SELECT idpersona, SUM(total_compra) as factura_total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
      ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
      FROM compra_grano  WHERE  YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1';";
      $factura_total_gasto = ejecutarConsultaSimpleFila($sql_8); if ($factura_total_gasto['status'] == false) { return $factura_total_gasto; }

      // $sql_9 = "SELECT SUM(dcg.monto) as factura_total_pago  
      // FROM detalle_compra_grano as dcg, compra_grano as cg 
      // WHERE dcg.idcompra_grano = cg.idcompra_grano  AND  YEAR(cg.fecha_compra) = '$year_filtro' AND cg.estado='1' AND cg.estado_delete='1';";
      // $factura_total_pago = ejecutarConsultaSimpleFila($sql_9);  if ($factura_total_pago['status'] == false) { return $factura_total_pago; }

      // -----------------------
      $sql_10 = "SELECT dcg.tipo_grano, SUM(dcg.peso_bruto) as peso_bruto, SUM(dcg.dcto_humedad) AS dcto_humedad, SUM(dcg.porcentaje_cascara) AS porcentaje_cascara, SUM(dcg.dcto_embase) AS dcto_embase, SUM(dcg.peso_neto) AS peso_neto
      FROM compra_grano as cg, detalle_compra_grano as dcg
      WHERE cg.idcompra_grano = dcg.idcompra_grano AND  YEAR(cg.fecha_compra) = '$year_filtro'
      GROUP BY dcg.tipo_grano ;";
      $productos_mas_vendidos = ejecutarConsultaArray($sql_10);  if ($productos_mas_vendidos['status'] == false) { return $productos_mas_vendidos; }

      if ( !empty($productos_mas_vendidos['data']) ) {
        foreach ($productos_mas_vendidos['data'] as $key => $value) {
          array_push($producto_mas_vendido_nombre, $value['tipo_grano']);
          array_push($producto_mas_vendido_cantidad, $value['peso_neto']);
        }        
      }

    }else{
      for ($i=1; $i <= $dias_filtro ; $i++) {
        $sql_1 = "SELECT idpersona, SUM(total_compra) as total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
        ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
        FROM compra_grano  
        WHERE DAY(fecha_compra)='$i' AND MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_1);
        if ($mes['status'] == false) { return $mes; }
        array_push($data_gasto, (empty($mes['data']) ? 0 : (empty($mes['data']['total_gasto']) ? 0 : floatval($mes['data']['total_gasto']) ) ));
  
        $sql_2 = "SELECT SUM(dcg.peso_neto) as peso_neto  
        FROM detalle_compra_grano as dcg, compra_grano as dg 
        WHERE dcg.idcompra_grano = dg.idcompra_grano AND DAY(dg.fecha_compra)='$i' AND MONTH(dg.fecha_compra)='$mes_filtro' AND YEAR(dg.fecha_compra) = '$year_filtro' AND dg.estado='1' AND dg.estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_2);
        if ($mes['status'] == false) { return $mes; }
        array_push($data_pagos, (empty($mes['data']) ? 0 : (empty($mes['data']['peso_neto']) ? 0 : floatval($mes['data']['peso_neto']) ) ));
      }

      $sql_3 = "SELECT COUNT(idcompra_grano) as factura_total FROM compra_grano WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' ;";
      $factura_total = ejecutarConsultaSimpleFila($sql_3);
      if ($factura_total['status'] == false) { return $factura_total; }

      $sql_4 = "SELECT COUNT(idcompra_grano) as factura_aceptadas FROM compra_grano WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1' ;";
      $factura_aceptadas = ejecutarConsultaSimpleFila($sql_4);
      if ($factura_aceptadas['status'] == false) { return $factura_aceptadas; }

      $sql_5 = "SELECT COUNT(idcompra_grano) as factura_rechazadas FROM compra_grano WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='0' AND estado_delete='1' ;";
      $factura_rechazadas = ejecutarConsultaSimpleFila($sql_5);
      if ($factura_rechazadas['status'] == false) { return $factura_rechazadas; }

      $sql_6 = "SELECT COUNT(idcompra_grano) as factura_eliminadas FROM compra_grano WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='0' ;";
      $factura_eliminadas = ejecutarConsultaSimpleFila($sql_6);
      if ($factura_eliminadas['status'] == false) { return $factura_eliminadas; }

      $sql_7 = "SELECT COUNT(idcompra_grano) as factura_rechazadas_eliminadas FROM compra_grano WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='0' AND estado_delete='0' ;";
      $factura_rechazadas_eliminadas = ejecutarConsultaSimpleFila($sql_7);
      if ($factura_rechazadas_eliminadas['status'] == false) { return $factura_rechazadas_eliminadas; }

      // -------------------------
      $sql_8 = "SELECT idpersona, SUM(total_compra) as factura_total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
      ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
      FROM compra_grano  WHERE  MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro'  AND estado='1' AND estado_delete='1';";
      $factura_total_gasto = ejecutarConsultaSimpleFila($sql_8);
      if ($factura_total_gasto['status'] == false) { return $factura_total_gasto; }

      // $sql_9 = "SELECT SUM(pg.monto) as factura_total_pago  
      // FROM pago_compras as pg, compra_grano as cpp 
      // WHERE pg.idcompra_grano = cpp.idcompra_grano  AND MONTH(pg.fecha_compra)='$mes_filtro' AND YEAR(pg.fecha_compra) = '$year_filtro' AND cpp.estado='1' AND cpp.estado_delete='1';";
      // $factura_total_pago = ejecutarConsultaSimpleFila($sql_9);
      // if ($factura_total_pago['status'] == false) { return $factura_total_pago; }

      // -----------------------
      $sql_10 = "SELECT dcg.tipo_grano, SUM(dcg.peso_bruto) as peso_bruto, SUM(dcg.dcto_humedad) AS dcto_humedad, SUM(dcg.porcentaje_cascara) AS porcentaje_cascara, SUM(dcg.dcto_embase) AS dcto_embase, SUM(dcg.peso_neto) AS peso_neto
      FROM compra_grano as cg, detalle_compra_grano as dcg
      WHERE cg.idcompra_grano = dcg.idcompra_grano AND MONTH(cg.fecha_compra)='$mes_filtro' AND  YEAR(cg.fecha_compra) = '$year_filtro'
      GROUP BY dcg.tipo_grano;";
      $productos_mas_vendidos = ejecutarConsultaArray($sql_10);
      if ($productos_mas_vendidos['status'] == false) { return $productos_mas_vendidos; }

      if ( !empty($productos_mas_vendidos['data']) ) {
        foreach ($productos_mas_vendidos['data'] as $key => $value) {
          array_push($producto_mas_vendido_nombre, $value['tipo_grano']);
          array_push($producto_mas_vendido_cantidad, $value['peso_neto']);
        }        
      }
    }
    
    
    return $retorno = [
      'status'=> true, 'message' => 'Salió todo ok,', 
      'data' => [
        'total_gasto'=>$data_gasto, 
        'total_deposito'=>$data_pagos, 

        'factura_total'=>(empty($factura_total['data']) ? 0 : (empty($factura_total['data']['factura_total']) ? 0 : floatval($factura_total['data']['factura_total']) ) ), 
        'factura_aceptadas'=>(empty($factura_aceptadas['data']) ? 0 : (empty($factura_aceptadas['data']['factura_aceptadas']) ? 0 : floatval($factura_aceptadas['data']['factura_aceptadas']) ) ), 
        'factura_rechazadas'=>(empty($factura_rechazadas['data']) ? 0 : (empty($factura_rechazadas['data']['factura_rechazadas']) ? 0 : floatval($factura_rechazadas['data']['factura_rechazadas']) ) ), 
        'factura_eliminadas'=>(empty($factura_eliminadas['data']) ? 0 : (empty($factura_eliminadas['data']['factura_eliminadas']) ? 0 : floatval($factura_eliminadas['data']['factura_eliminadas']) ) ),
        'factura_rechazadas_eliminadas'=>(empty($factura_rechazadas_eliminadas['data']) ? 0 : (empty($factura_rechazadas_eliminadas['data']['factura_rechazadas_eliminadas']) ? 0 : floatval($factura_rechazadas_eliminadas['data']['factura_rechazadas_eliminadas']) ) ), 
        
        'factura_total_gasto'=>(empty($factura_total_gasto['data']) ? 0 : (empty($factura_total_gasto['data']['factura_total_gasto']) ? 0 : floatval($factura_total_gasto['data']['factura_total_gasto']) ) ),
        //'factura_total_pago'=>(empty($factura_total_pago['data']) ? 0 : (empty($factura_total_pago['data']['factura_total_pago']) ? 0 : floatval($factura_total_pago['data']['factura_total_pago']) ) ),

        'productos_mas_vendidos'=>$productos_mas_vendidos['data'], 
        'producto_mas_vendido_nombre'=>$producto_mas_vendido_nombre, 
        'producto_mas_vendido_cantidad'=>$producto_mas_vendido_cantidad, 
      ]  
    ];
  }

  public function anios_select2($id_proyecto) {
    $sql = "SELECT DISTINCTROW YEAR(fecha_compra) as anios FROM compra_grano ORDER BY fecha_compra DESC;";
    return ejecutarConsultaArray($sql);
  }
    
}

?>
