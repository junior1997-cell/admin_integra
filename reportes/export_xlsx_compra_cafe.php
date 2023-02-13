<?php 
  require '../vendor/autoload.php'; 
  use PhpOffice\PhpSpreadsheet\Spreadsheet;  
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Style\Border;
  use PhpOffice\PhpSpreadsheet\Style\Color;


  $spreadsheet = new Spreadsheet();
  $spreadsheet->getProperties()->setCreator("Integra Peru")->setTitle("Compra de Cafe");
  
  $spreadsheet->setActiveSheetIndex(0);
  $spreadsheet->getActiveSheet()->getStyle('A')->getAlignment()->setVertical('center');
  $spreadsheet->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal('left');
  $spreadsheet->getActiveSheet()->getStyle('F1:G2')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('F1:G2')->getAlignment()->setVertical('center');
  $spreadsheet->getActiveSheet()->getStyle('F20:G22')->getAlignment()->setHorizontal('right'); #
  $spreadsheet->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setVertical('center');
  $spreadsheet->getActiveSheet()->getStyle('G6:G22')->getAlignment()->setHorizontal('right'); #ALINEAR NUMEROS
  // $spreadsheet->getActiveSheet()->getStyle('F:I')->getAlignment()->setHorizontal('right'); # subtotal
  // $spreadsheet->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal('right'); # subtotal

  // Negrita
  $spreadsheet->getActiveSheet()->getStyle('A')->getFont()->setBold(true);  
  $spreadsheet->getActiveSheet()->getStyle('F1:G2')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('F20:F22')->getFont()->setBold(true);
  

  // Tamaño de Letra
  $spreadsheet->getActiveSheet()->getStyle('F1')->getFont()->setSize(15);
  
  // Tamaño de celda
  $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
  $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(7);
  $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
  $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
  $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
  $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25); 
  $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20); 
  $spreadsheet->getActiveSheet()->getRowDimension(19)->setRowHeight(5);
  $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(20); 

  // Borde celda
  $spreadsheet->getActiveSheet()->getStyle('A1:E7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  $spreadsheet->getActiveSheet()->getStyle('F1:G22')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  $spreadsheet->getActiveSheet()->getStyle('F4:G18')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('000000'));
  $spreadsheet->getActiveSheet()->getStyle('F20:G22')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('000000'));
  
  // Color celda
  $spreadsheet->getActiveSheet()->getStyle('F4:F18')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('66c07b');
  $spreadsheet->getActiveSheet()->getStyle('F20:F22')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f8e700');
  
  
  $hojaActiva = $spreadsheet->getActiveSheet();

  // Add png image to comment background
  $drawing = $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Paid');
  $drawing->setDescription('Paid');
  $drawing->setPath('../dist/img/default/empresa-logo.jpg'); // put your path and image here
  $drawing->setCoordinates('A1');
  $drawing->setWidthAndHeight(40, 40);
  $drawing->setOffsetY(1);
  $drawing->setOffsetX(35);
  $drawing->setRotation(0);
  // $drawing->getShadow()->setVisible(true);
  // $drawing->getShadow()->setDirection(45);
  $drawing->setWorksheet($spreadsheet->getActiveSheet());

  // $spreadsheet->getDefaultStyle()->getFont()->setName("Tahoma");
  // $spreadsheet->getDefaultStyle()->getFont()->setSize(15);

  $hojaActiva->mergeCells('A1:A2'); #Vacio
  $hojaActiva->mergeCells('B1:E2'); #Vacio
  $hojaActiva->mergeCells('F1:G2'); #Numero comprobante
  $hojaActiva->mergeCells('B3:G3'); #Proveedor
  $hojaActiva->mergeCells('B4:E4'); #Ruc
  $hojaActiva->mergeCells('B5:E5'); #Fecha 
  $hojaActiva->mergeCells('B6:E6'); #Base 
  $hojaActiva->mergeCells('B7:E7'); #Metodod de pago
  $hojaActiva->mergeCells('F19:G19'); #Linea negra 

  $hojaActiva->setCellValue('A3', 'Proveedor:');
  $hojaActiva->setCellValue('A4', 'RUC:');
  $hojaActiva->setCellValue('A5', 'Fecha:');
  $hojaActiva->setCellValue('A6', 'Base:');
  $hojaActiva->setCellValue('A7', 'Método de pago:');

  $hojaActiva->setCellValue('F4', 'TIPO DE CAFÉ');
  $hojaActiva->setCellValue('F5', 'UNDIAD');
  $hojaActiva->setCellValue('F6', 'KILOS BRUTOS');
  $hojaActiva->setCellValue('F7', 'SACOS');
  $hojaActiva->setCellValue('F8', 'HUMEDAD(%)');
  $hojaActiva->setCellValue('F9', 'RENDIMINETO(%)');
  $hojaActiva->setCellValue('F10', 'SEGUNDA(%)');
  $hojaActiva->setCellValue('F11', 'CASCARA(%)');
  $hojaActiva->setCellValue('F12', 'TAZA(%)');
  $hojaActiva->setCellValue('F13', 'TARA(SACOS + HUMEDAD)');
  $hojaActiva->setCellValue('F14', 'KG. NETOS');
  $hojaActiva->setCellValue('F15', 'QUINTAL NETO (55.2)');
  $hojaActiva->setCellValue('F16', 'PRECIO');
  $hojaActiva->setCellValue('F17', 'DESCUENTO (adicional)');
  $hojaActiva->setCellValue('F18', 'SUBTOTAL');  

  require_once "../modelos/Compra_cafe_v2.php";
  $compra_cafe = new Compra_cafe_v2();

  $rspta      = $compra_cafe->mostrar_compra_para_editar($_GET['id']);
  // echo json_encode($rspta, true);

  $hojaActiva->setCellValue('B3', $rspta['data']['cliente']);                     #Cliente
  $hojaActiva->setCellValue('B4', $rspta['data']['numero_documento']);            #RUC
  $hojaActiva->setCellValue('B5', format_d_m_a( $rspta['data']['fecha_compra'])); #Fecha
  $hojaActiva->setCellValue('B6', $rspta['data']['descripcion']);                 #Descripcion
  $hojaActiva->setCellValue('B7', $rspta['data']['metodo_pago']);                 #Metodo Pago
  $hojaActiva->setCellValue('F1', $rspta['data']['tipo_comprobante'] .' - ' . $rspta['data']['numero_documento']);
  
  $hojaActiva->setCellValue('G4', $rspta['data']['detalle_compra']['tipo_grano']); 
  $hojaActiva->setCellValue('G5', $rspta['data']['detalle_compra']['unidad_medida']); 
  $hojaActiva->setCellValue('G6', number_format($rspta['data']['detalle_compra']['peso_bruto'], 2,'.',',')); 
  $hojaActiva->setCellValue('G7', number_format($rspta['data']['detalle_compra']['sacos'], 2,'.',',')); 
  $hojaActiva->setCellValue('G8', number_format($rspta['data']['detalle_compra']['dcto_humedad'], 2,'.',',')); 
  $hojaActiva->setCellValue('G9', number_format($rspta['data']['detalle_compra']['dcto_rendimiento'], 2,'.',',')); 
  $hojaActiva->setCellValue('G10', number_format($rspta['data']['detalle_compra']['dcto_segunda'], 2,'.',',')); 
  $hojaActiva->setCellValue('G11', number_format($rspta['data']['detalle_compra']['dcto_cascara'], 2,'.',',')); 
  $hojaActiva->setCellValue('G12', number_format($rspta['data']['detalle_compra']['dcto_taza'], 2,'.',',')); 
  $hojaActiva->setCellValue('G13', number_format($rspta['data']['detalle_compra']['dcto_tara'], 2,'.',',')); 
  $hojaActiva->setCellValue('G14', number_format($rspta['data']['detalle_compra']['peso_neto'], 2,'.',','));
  $hojaActiva->setCellValue('G15', number_format($rspta['data']['detalle_compra']['quintal_neto'], 2,'.',','));
  $hojaActiva->setCellValue('G16', number_format($rspta['data']['detalle_compra']['precio_con_igv'], 2,'.',','));
  $hojaActiva->setCellValue('G17', number_format($rspta['data']['detalle_compra']['descuento_adicional'], 2,'.',','));
  $hojaActiva->setCellValue('G18', number_format($rspta['data']['detalle_compra']['subtotal'], 2,'.',','));

  $hojaActiva->setCellValue('F20', $rspta['data']['tipo_gravada']);
  $hojaActiva->setCellValue('F21', 'IGV('. ($rspta['data']['val_igv'] * 100 ) .'%)');
  $hojaActiva->setCellValue('F22', 'TOTAL');

  $hojaActiva->setCellValue('G20', number_format($rspta['data']['subtotal_compra'], 2,'.',','));
  $hojaActiva->setCellValue('G21', number_format($rspta['data']['igv_compra'], 2,'.',','));
  $hojaActiva->setCellValue('G22', number_format($rspta['data']['total_compra'], 2,'.',','));

  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Compra_de_cafe.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
