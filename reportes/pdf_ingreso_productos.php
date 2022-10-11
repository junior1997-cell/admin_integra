<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) {
  session_start();
}

if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
      
  require 'Factura.php';
  require_once "../modelos/Ingreso_producto.php";

  //Establecemos la configuración de la factura
  $pdf = new PDF_Invoice('P', 'mm', 'A4');
  
  $compra_producto = new Ingreso_producto();

  if (empty($_GET)) {
    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
  } else if ($_GET['id'] != '') {
    $id = $_GET['id'];
    $rspta = $compra_producto->mostrar_compra_para_editar($id);
  } else {    
  }

  //Establecemos los datos de la empresa
  $logo     = "../dist/img/default/empresa-logo.jpg";
  $ext_logo = "jpg";
  $empresa  = 'INTEGRA PERU SAC';
  $documento= 'RUC: 3532432423' ;
  $direccion= 'JR. LAS ROSASA / JAEN / PERU';
  $telefono = '938-724-523' ;

  //Enviamos los datos de la empresa al método addSociete de la clase Factura
  $pdf->AddPage();  
  $pdf->addSociete(utf8_decode($empresa), 
  $documento . "\n" . utf8_decode("Dirección: ") . utf8_decode($direccion) . "\n" . utf8_decode("Teléfono: ") . $telefono , 
  $logo, $ext_logo);
  $pdf->fact_dev($rspta['data']['compra']['tipo_comprobante'], $rspta['data']['compra']['serie_comprobante']);
  $pdf->addDate(format_d_m_a($rspta['data']['compra']['fecha_compra']));

  $pdf->temporaire( utf8_decode("Integra Peru") );

  //Enviamos los datos del cliente al método addClientAdresse de la clase Factura
  $pdf->addClientAdresse(utf8_decode($rspta['data']['compra']['nombres']), 
    utf8_decode("Dirección: "). utf8_decode($rspta['data']['compra']['direccion']), 
    $rspta['data']['compra']['tipo_documento'] . ": " .$rspta['data']['compra']['numero_documento'], 
    "Email: " . $rspta['data']['compra']['correo'], 
    "Telefono: " . $rspta['data']['compra']['celular']
  );
  $pdf->addReference( utf8_decode( decodeCadenaHtml((empty($rspta['data']['compra']['descripcion'])) ? '- - -' :$rspta['data']['compra']['descripcion']) ));

  //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
  $cols = [ "#" => 8, "PRODUCTO" => 63, "UM" => 18, "CANT." => 14, "V/U" => 18, "IGV" => 14, "P.U." => 20, "DSCT." => 13, "SUBTOTAL" => 22];
  $pdf->addCols($cols);
  $cols = [ "#" => "C", "PRODUCTO" => "L", "UM" => "C",  "CANT." => "C", "V/U" => "R", "IGV" => "R","P.U." => "R", "DSCT." => "R", "SUBTOTAL" => "R"];
  $pdf->addLineFormat($cols);
  $pdf->addLineFormat($cols);
  //Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
  $y = 89;

  $cont = 1;
  //Obtenemos todos los detalles de la venta actual
  foreach ($rspta['data']['detalle'] as $key => $reg) {

    $line = [ "#" => $cont++, 
      "PRODUCTO" => utf8_decode( decodeCadenaHtml($reg['nombre'])), 
      "UM" => $reg['abreviatura'], 
      "CANT." => $reg['cantidad'], 
      "V/U" => number_format($reg['precio_sin_igv'], 2, '.',','), 
      "IGV" => number_format($reg['igv'], 2, '.',','), 
      "P.U." => number_format($reg['precio_con_igv'], 2, '.',','), 
      "DSCT." => number_format($reg['descuento'], 2, '.',','), 
      "SUBTOTAL" => number_format($reg['subtotal'], 2, '.',',')
    ];
    $size = $pdf->addLine($y, $line);
    $y += $size + 2;
  }

  //Convertimos el total en letras
  require_once "Letras.php";
  $V = new EnLetras();
  $num_total = floatval($rspta['data']['compra']['total']);
  $con_letra = strtoupper($V->ValorEnLetras(503, "SOLES"));
  $pdf->addCadreTVAs("---" . $con_letra);

  //Mostramos el impuesto
  $pdf->addTVAs(number_format($rspta['data']['compra']['subtotal'], 2, '.',','), number_format($rspta['data']['compra']['igv'], 2, '.',','), number_format($rspta['data']['compra']['total'], 2, '.',','), "S/ ");
  $pdf->addCadreEurosFrancs('IGV ('.( ( empty($rspta['data']['compra']['val_igv']) ? 0 : floatval($rspta['data']['compra']['val_igv']) )  * 100 ) . '%)');
  $pdf->Output('Reporte de compra.pdf', 'I');
   
}

function number_words($valor,$desc_moneda, $sep, $desc_decimal) {
  $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
  return $f->format(1432);
}
ob_end_flush();
?>
