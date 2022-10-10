<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){

    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));

  }else{ ?>

    <!DOCTYPE html> 
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title> Compras Grano | Admin Integra </title>

        <?php $title = "Compras"; require 'head.php'; ?>

        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_materiales.css" />
        <link rel="stylesheet" href="../dist/css/leyenda.css" />
        
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
        <div class="wrapper">
          <!-- Preloader -->
          <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-principal.svg" alt="AdminLTELogo" width="360" />
          </div> -->

          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['compra_insumos']==1){
            //require 'enmantenimiento.php';
            ?>
            <!--Contenido-->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <div class="content-header">
                <div class="container-fluid"> 
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0"> <img src="../dist/svg/negro-grano-cafe-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" > Compras Grano <span class="h1-nombre-cliente"></span> </h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="compra_insumos.php">Home</a></li>
                        <li class="breadcrumb-item active">Compras Grano</li>
                      </ol>
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
              </div>
              <!-- /.content-header -->

              <!-- Main content -->
              <section class="content">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12">
                      <div class="card card-primary card-outline">
                        <!-- Start Main Top -->
                        <div class="main-top">
                          <div class="container-fluid border-bottom">
                            <div class="row">
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"> 
                                <div class="card-header">
                                  <h3 class="card-title">
                                    <!--data-toggle="modal" data-target="#modal-agregar-compra"  onclick="limpiar();"-->
                                    <button type="button" class="btn bg-gradient-success" id="btn_agregar" onclick="show_hide_form(3); limpiar_form_compra();">
                                      <i class="fas fa-plus-circle"></i> Agregar
                                    </button>                                    
                                    <button type="button" class="btn bg-gradient-warning" id="btn_regresar" style="display: none;" onclick="show_hide_form(1);">
                                      <i class="fas fa-arrow-left"></i> Regresar
                                    </button>
                                    <button type="button" class="btn bg-gradient-success" id="btn_pagar" style="display: none;" data-toggle="modal"  data-target="#modal-agregar-pago" onclick="limpiar_form_pago_compra();">
                                      <i class="fas fa-dollar-sign"></i> Agregar Pago
                                    </button>                                     
                                  </h3>
                                </div>
                              </div>
                              <!-- Leyecnda pagos -->
                              <!-- <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 hiddenn leyecnda_pagos" style="background-color: aliceblue;">
                                <div class="text-slid-box">
                                  <div id="offer-box" class="contenedor">
                                    <div> <b>Leyenda-pago</b> </div>
                                    <ul class="offer-box cls-ul">
                                      <li>
                                        <span class="text-center badge badge-danger" >Pago sin iniciar </span> 
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-warning" >Pago en proceso </span>
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-success" >Pago completo</span>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div> -->
                              <!-- Leyecnda saldos -->
                              <!-- <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 hiddenn leyecnda_saldos" style="background-color: #f0f8ff7d;">
                                <div class="text-slid-box">
                                  <div id="offer-box" class="contenedorr">
                                    <div> <b>Leyenda-saldos</b> </div>
                                    <ul class="offer-box clss-ul">
                                      <li>
                                        <span class="text-center badge badge-warning " >Pago nulo o pago en proceso </span> 
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-success" >Pago Completo </span>
                                      </li>
                                      <li>
                                        <span class="text-center badge badge-danger" >Pago excedido</span>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div> -->
                            </div>
                          </div>
                        </div>
                        <!-- End Main Top -->

                        <!-- /.card-header -->
                        <div class="card-body">
                          <!-- TABLA - COMPRAS -->
                          <div id="div_tabla_compra">
                            <h5><b>Lista de compras Por Facturas</b></h5>
                            <!-- filtros -->
                            <div class="filtros-inputs row mb-4">

                              <!-- filtro por: fecha inicial -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">    
                                <div class="form-group">
                                  <!-- <label for="filtro_fecha_inicio" >Fecha inicio </label> -->
                                  <div class="input-group date"  >
                                    <div class="input-group-append cursor-pointer click-btn-fecha-inicio" >
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" class="form-control"  id="filtro_fecha_inicio" onchange="cargando_search(); delay(function(){filtros()}, 50 );" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask autocomplete="off" />                                    
                                  </div>
                                </div>                                
                              </div>

                              <!-- filtro por: fecha final -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">                                
                                <div class="form-group">
                                  <!-- <label for="filtro_fecha_inicio" >Fecha fin </label> -->
                                  <div class="input-group date"  >
                                    <div class="input-group-append cursor-pointer click-btn-fecha-fin" >
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" class="form-control"  id="filtro_fecha_fin" onchange="cargando_search(); delay(function(){filtros()}, 50 );" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask autocomplete="off" />                                    
                                  </div>
                                </div> 
                              </div>

                              <!-- filtro por: proveedor -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <!-- <label for="filtros" class="cargando_proveedor">Proveedor &nbsp;<i class="text-dark fas fa-spinner fa-pulse fa-lg"></i><br /></label> -->
                                  <select id="filtro_cliente" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                  </select>
                                </div>                                
                              </div>

                              <!-- filtro por: proveedor -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">
                                <div class="form-group">
                                  <!-- <label for="filtros" >Tipo comprobante </label> -->
                                  <select id="filtro_tipo_comprobante" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                    <option value="0">Todos</option>
                                    <option value="Ninguno">Ninguno</option>
                                    <option value="Boleta">Boleta</option>
                                    <option value="Factura">Factura</option>
                                    <option value="Nota de venta">Nota de venta</option>
                                  </select>
                                </div>
                                
                              </div>
                            </div>
                            <!-- /.filtro -->
                            
                            <table id="tabla-compra-grano" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th colspan="13" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                </tr>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Cliente</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo de Persona">Tipo</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Comprobante</th>
                                  <th>Total</th>
                                  <th>Metodo de Pago</th>
                                  <th>Descripción</th>

                                  <th>Tipo Doc.</th>
                                  <th>Num. Doc.</th>
                                  <th>Tipo Comprobante</th>
                                  <th>Num. Comprobante</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Cliente</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo de Persona">Tipo</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Comprobante</th>
                                  <th>Total</th>
                                  <th>Metodo de Pago</th>
                                  <th>Descripción</th> 
                                  
                                  <th>Tipo Doc.</th>
                                  <th>Num. Doc.</th>
                                  <th>Tipo Comprobante</th>
                                  <th>Num. Comprobante</th>
                                </tr>
                              </tfoot>
                            </table>
                            <br />
                            <h4><b>Lista de Compras Por Cliente</b></h4>
                            <table id="tabla-compra-cliente" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Proveedor</th>
                                  <th>Cant</th>
                                  <th>Cel.</th>
                                  <th>Total</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th> 
                                  <th>Proveedor</th>
                                  <th>Cant</th>
                                  <th>Cel.</th>
                                  <th>Total</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- TABLA - COMPRAS POR CLIENTE -->
                          <div id="div_tabla_detalle_compra_x_cliente" style="display: none;">
                            <h5><b>Lista de compras Por Facturas</b></h5>
                            <table id="tabla-detalle-compra-grano-x-cliente" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Comprobante</th>
                                  <th data-toggle="tooltip" data-original-title="Número Comprobante">Num. Comprobante</th>
                                  <th>Total</th>
                                  <th>Descripcion</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Comprobante</th>
                                  <th data-toggle="tooltip" data-original-title="Número Comprobante">Num. Comprobante</th>
                                  <th>Total</th>
                                  <th>Descripcion</th>
                                  <th>Estado</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- FORM - AGREGAR COMPRA GRANO-->
                          <div id="div_form_agregar_compras_grano" style="display: none;">
                            <div class="modal-body p-0px mb-2">
                              <!-- form start -->
                              <form id="form-compras" name="form-compras" method="POST">
                                 
                                <div class="row" id="cargando-1-fomulario">
                                  <input type="hidden" name="idcompra_grano" id="idcompra_grano" /> 

                                  <!-- Tipo de Empresa -->
                                  <div class="col-lg-5">
                                    <div class="form-group">
                                      <label for="idcliente">Cliente <sup class="text-danger">(único*)</sup></label>
                                      <select id="idcliente" name="idcliente" class="form-control select2" data-live-search="true" required title="Seleccione cliente" onchange="extrae_ruc();"> </select>
                                      <input type="hidden" name="ruc_dni_cliente" id="ruc_dni_cliente" /> 
                                    </div>
                                  </div>

                                  <!-- adduser -->
                                  <div class="col-lg-1">
                                    <div class="form-group">
                                    <label for="Add" class="d-none d-sm-inline-block text-break" style="color: white;">.</label> <br class="d-none d-sm-inline-block">
                                      <a data-toggle="modal" href="#modal-agregar-cliente" >
                                        <button type="button" class="btn btn-success p-x-6px" data-toggle="tooltip" data-original-title="Agregar Provedor" onclick="limpiar_form_cliente();">
                                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </button>
                                      </a>
                                      <button type="button" class="btn btn-warning p-x-6px btn-editar-cliente" data-toggle="tooltip" data-original-title="Editar:" onclick="mostrar_para_editar_cliente();">
                                        <i class="fa-solid fa-pencil" aria-hidden="true"></i>
                                      </button>
                                    </div>
                                  </div>

                                  <!-- fecha -->
                                  <div class="col-lg-3" >
                                    <div class="form-group">
                                      <label for="fecha_compra">Fecha <sup class="text-danger">*</sup></label>
                                      <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" placeholder="Fecha" />
                                    </div>
                                  </div>

                                  <!-- metodo de pago -->
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="metodo_pago">Método de pago <sup class="text-danger">*</sup></label>
                                      <select id="metodo_pago" name="metodo_pago" class="form-control select2" data-live-search="true" required title="Seleccione glosa"> 
                                        <option title="fas fa-hammer" value="CONTADO">CONTADO</option>
                                        <option title="fas fa-gas-pump" value="CREDITO">CREDITO</option>
                                      </select>
                                    </div> 
                                  </div>

                                  <!-- Tipo de comprobante -->
                                  <div class="col-lg-4" id="content-tipo-comprobante">
                                    <div class="form-group">
                                      <label for="tipo_comprobante">Tipo Comprobante <sup class="text-danger">(único*)</sup></label>
                                      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2"  onchange="default_val_igv(); modificarSubtotales(); ocultar_comprob();" placeholder="Seleccinar un tipo de comprobante">
                                        <option value="Ninguno">Ninguno</option>
                                        <option value="Boleta">Boleta</option>
                                        <option value="Factura">Factura</option>
                                        <option value="Nota de venta">Nota de venta</option>
                                      </select>
                                    </div>
                                  </div> 

                                  <!-- numero_comprobante -->
                                  <div class="col-lg-2" id="content-serie-comprobante">
                                    <div class="form-group">
                                      <label for="numero_comprobante">N° de Comprobante <sup class="text-danger">(único*)</sup></label>
                                      <input type="text" name="numero_comprobante" id="numero_comprobante" class="form-control" placeholder="N° de Comprobante" />
                                    </div>
                                  </div>

                                  <!-- IGV-->
                                  <div class="col-lg-1" id="content-igv">
                                    <div class="form-group">
                                      <label for="val_igv">IGV <sup class="text-danger">*</sup></label>
                                      <input type="text" name="val_igv" id="val_igv" class="form-control" value="0.18" onkeyup="modificarSubtotales();" />
                                    </div>
                                  </div>

                                  <!-- Descripcion-->
                                  <div class="col-lg-5" id="content-descripcion">
                                    <div class="form-group">
                                      <label for="descripcion">Descripción </label> <br />
                                      <textarea name="descripcion" id="descripcion" class="form-control" rows="1"></textarea>
                                    </div>
                                  </div>                                  

                                  <!--Boton agregar material-->
                                  <div class="row col-lg-12 justify-content-between">
                                    <div class="col-lg-4 col-xs-12">
                                      <div class="row">
                                        <div class="col-lg-6">                                                                                      
                                          <button id="btn-agregar-detalle-form-compra" type="button" class="btn btn-primary btn-block"><span class="fa fa-plus"></span> Agregar Detalle</button>                                            
                                        </div>                                        
                                      </div>
                                    </div>
                                  </div>

                                  <!--tabla detalles compra-->
                                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive row-horizon disenio-scroll">
                                    <br />
                                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                      <thead style="background-color: #00821e80;">
                                        <tr class="text-center">
                                          <th rowspan="2" class="p-y-2px" data-toggle="tooltip" data-original-title="Opciones">Op.</th>
                                          <th rowspan="2" class="p-y-2px">Tipo Grano</th>
                                          <th rowspan="2" class="p-y-2px">Unidad</th>
                                          <th rowspan="2" class="p-y-2px">Peso Bruto</th>
                                          <th colspan="3" class="p-y-2px">Descuento en KG</th>
                                          <th rowspan="2" class="p-y-2px">Peso Neto</th>
                                          <th rowspan="2" class="p-y-2px hidden" data-toggle="tooltip" data-original-title="Valor Unitario" >V/U</th>
                                          <th rowspan="2" class="p-y-2px hidden">IGV</th>
                                          <th rowspan="2" class="p-y-2px" data-toggle="tooltip" data-original-title="Precio Unitario">P/U</th>
                                          <th rowspan="2" class="p-y-2px">Descuento <br> <small>(adicional)</small></th>
                                          <th rowspan="2" class="p-y-2px">Subtotal</th>
                                        </tr>

                                        <tr class="text-center">
                                          <th class="p-y-1px" >Humedad</th>
                                          <th class="p-y-1px" >Cascara</th>
                                          <th class="p-y-1px" >Embase</th>
                                        </tr>
                                        
                                      </thead>
                                      <tfoot>
                                        <td colspan="11" id="colspan_subtotal"></td>
                                        <th class="text-right">
                                          <h6 class="tipo_gravada">GRAVADA</h6>
                                          <h6 class="val_igv">IGV (18%)</h6>
                                          <h5 class="font-weight-bold">TOTAL</h5>
                                        </th>
                                        <th class="text-right"> 
                                          <h6 class="font-weight-bold subtotal_compra">S/ 0.00</h6>
                                          <input type="hidden" name="subtotal_compra" id="subtotal_compra" />
                                          <input type="hidden" name="tipo_gravada" id="tipo_gravada" />

                                          <h6 class="font-weight-bold igv_compra">S/ 0.00</h6>
                                          <input type="hidden" name="igv_compra" id="igv_compra" />
                                          
                                          <h5 class="font-weight-bold total_compra">S/ 0.00</h5>
                                          <input type="hidden" name="total_compra" id="total_compra" />
                                          
                                        </th>
                                      </tfoot>
                                      <tbody></tbody>
                                    </table>
                                  </div>                                    
                                </div>

                                <div class="row" id="cargando-2-fomulario" style="display: none;">
                                  <div class="col-lg-12 text-center">
                                    <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                    <br />
                                    <h4>Cargando...</h4>
                                  </div>
                                </div>                                 
                                 
                                <button type="submit" style="display: none;" id="submit-form-compras">Submit</button>
                              </form>
                            </div>

                            <div class="modal-footer justify-content-between pl-0 pb-0 ">
                              <button type="button" class="btn btn-danger" onclick="show_hide_form(1);" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-success" style="display: none;" id="guardar_registro_compras">Guardar Cambios</button>
                            </div>
                          </div>                          

                          <!-- TABLA - PAGOS COMPRAS GRANO -->
                          <div id="div_tabla_pago_compras_grano" style="display: none;">
                            <h5>pago Compras</h5>
                            <div style="text-align: center;">
                              <div>
                                <h4>Total a pagar: <b id="total_compra"></b></h4>
                              </div>
                              <table id="tabla-pagos-proveedor" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Acciones</th>
                                    <th data-toggle="tooltip" data-original-title="Forma Pago">Forma</th>
                                    <th>Beneficiario</th>
                                    <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                    <th>Descripción</th>
                                    <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                    <th>Monto</th>
                                    <th>Vaucher</th>
                                    <th>Estado</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                  <tr>
                                    <th>#</th>
                                    <th>Aciones</th>
                                    <th>Forma</th>
                                    <th>Beneficiario</th>                                     
                                    <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                    <th>Descripción</th>
                                    <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                    <th style="color: #ff0000; background-color: #45c920;">
                                      <b id="monto_total"></b> <br />
                                      <b id="porcentaje" style="color: black;"></b>
                                    </th>
                                    <th>Vaucher</th>
                                    <th>Estado</th>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>                        

                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.container-fluid -->

                  <!-- MODAL - agregar cliente -->
                  <div class="modal fade" id="modal-agregar-cliente">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Agregar Cliente</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-cliente" name="form-cliente" method="POST">
                            <div class="card-body ">  
                              <div class="row" id="cargando-3-fomulario">
                                <!-- id persona -->
                                <input type="hidden" name="idpersona_cli" id="idpersona_cli" />
                                <!-- tipo persona  -->
                                <input type="hidden" name="id_tipo_persona_cli" id="id_tipo_persona_cli" value="2" />

                                <!-- Tipo de documento -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                                  <div class="form-group">
                                    <label for="tipo_documento_cli">Tipo Doc.</label>
                                    <select name="tipo_documento_cli" id="tipo_documento_cli" class="form-control" placeholder="Tipo de documento">
                                      <option selected value="DNI">DNI</option>
                                      <option value="RUC">RUC</option>
                                      <option value="CEDULA">CEDULA</option>
                                      <option value="OTRO">OTRO</option>
                                    </select>
                                  </div>
                                </div>
                                
                                <!-- N° de documento -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="num_documento_cli">N° de documento</label>
                                    <div class="input-group">
                                      <input type="number" name="num_documento_cli" class="form-control" id="num_documento_cli" placeholder="N° de documento" />
                                      <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec('_cli');">
                                        <span class="input-group-text" style="cursor: pointer;">
                                          <i class="fas fa-search text-primary" id="search_cli"></i>
                                          <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge_cli" style="display: none;"></i>
                                        </span>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <!-- Nombre -->
                                <div class="col-12 col-sm-12 col-md-12 col-lg-5">
                                  <div class="form-group">
                                    <label for="nombre_cli">Nombres/Razon Social</label>
                                    <input type="text" name="nombre_cli" class="form-control" id="nombre_cli" placeholder="Nombres y apellidos" />
                                  </div>
                                </div>

                                <!-- Correo electronico -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="email_cli">Correo electrónico</label>
                                    <input type="email_cli" name="email_cli" class="form-control" id="email_cli" placeholder="Correo electrónico" onkeyup="convert_minuscula(this);" />
                                  </div>
                                </div>

                                <!-- Telefono -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="telefono_cli">Teléfono</label>
                                    <input type="text" name="telefono_cli" id="telefono_cli" class="form-control" data-inputmask="'mask': ['999-999-999', '+51 999 999 999']" data-mask />
                                  </div>
                                </div>

                                <!-- banco -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="banco_cli">Banco</label>
                                    <select name="banco_cli" id="banco_cli" class="form-control select2 banco" style="width: 100%;" onchange="formato_banco();">
                                      <!-- Aqui listamos los bancos -->
                                    </select>
                                  </div>
                                </div>

                                <!-- Cuenta bancaria -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="cta_bancaria_cli" class="chargue-format-1">Cuenta Bancaria</label>
                                    <input type="text" name="cta_bancaria_cli" class="form-control" id="cta_bancaria_cli" placeholder="Cuenta Bancaria" data-inputmask="" data-mask />
                                  </div>
                                </div>

                                <!-- CCI -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="cci_cli" class="chargue-format-2">CCI</label>
                                    <input type="text" name="cci_cli" class="form-control" id="cci_cli" placeholder="CCI" data-inputmask="" data-mask />
                                  </div>
                                </div>

                                <!-- Titular de la cuenta -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="titular_cuenta_cli">Titular de la cuenta</label>
                                    <input type="text" name="titular_cuenta_cli" class="form-control" id="titular_cuenta_cli" placeholder="Titular de la cuenta" />
                                  </div>
                                </div>

                                <!-- Swichs permanente -->
                                <div class="col-4 col-sm-5 col-md-6 col-lg-3 classswichs">
                                    <label for="socio_cli">Es socio ? <small class="text-danger text-bold sino">(NO)</small> </label>
                                    <div class="switch-toggle">
                                      <input type="checkbox" id="socio_cli" >
                                      <label for="socio_cli" onclick="habilitando_socio();" onchange="habilitando_socio();"></label>
                                    </div>
                                    <input type="hidden" name="input_socio_cli" id="input_socio_cli" value="0"  >
                                </div>

                                <!-- Direccion -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-9 classdirecc">
                                  <div class="form-group">
                                    <label for="direccion_cli">Dirección</label>
                                    <input type="text" name="direccion_cli" class="form-control" id="direccion_cli" placeholder="Dirección" />
                                  </div>
                                </div>
                                <!--  -->
                                <!-- imagen perfil -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                  <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"></div>
                                  <label for="foto1">Foto de perfil</label> <br />
                                  <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="foto1_i" style="cursor: pointer !important;" width="auto" />
                                  <input style="display: none;" type="file" name="foto1" id="foto1" accept="image/*" />
                                  <input type="hidden" name="foto1_actual" id="foto1_actual" />
                                  <div class="text-center" id="foto1_nombre"><!-- aqui va el nombre de la FOTO --></div>
                                </div>

                                <!-- Progress -->
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="progress" id="barra_progress_cliente_div" style="display: none !important;">
                                      <div id="barra_progress_cliente" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row" id="cargando-4-fomulario" style="display: none;">
                                <div class="col-lg-12 text-center">
                                  <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                  <br />
                                  <h4>Cargando...</h4>
                                </div>
                              </div>                              

                            </div>
                            <!-- /.card-body -->
                            <button type="submit" style="display: none;" id="submit-form-cliente">Submit</button>
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_cliente">Guardar Cambios</button>
                        </div>
                      </div>
                    </div>
                  </div>                   

                  <!-- MODAL - DETALLE COMPRAS - charge -->
                  <div class="modal fade" id="modal-ver-detalle-compras-grano">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Detalle Compra</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body">
                          <div class="row detalle_de_compra_grano" id="cargando-5-fomulario">                            
                            <!--detalle de la compra-->
                          </div>

                          <div class="row" id="cargando-6-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-success float-right" id="excel_compra" onclick="export_excel_detalle_factura()" ><i class="far fa-file-excel"></i> Excel</button>
                          <a type="button" class="btn btn-info" id="print_pdf_compra" target="_blank" ><i class="fas fa-print"></i> Imprimir/PDF</a>
                        </div>
                      </div>
                    </div>
                  </div>                  
                  
                  <!-- MODAL - agregar Pagos - charge -->
                  <!-- MODAL - ver-vaucher-pagos -->                   

                </div>
              </section>
              <!-- /.content -->
            </div>
            <!--Fin-Contenido-->

            <?php
          }else{
            require 'noacceso.php';
          }
          require 'footer.php';
          ?>
        </div>
        
        <?php require 'script.php'; ?>

        <!-- table export EXCEL -->
        <script src="../plugins/export-xlsx/xlsx.full.min.js"></script>
        <script src="../plugins/export-xlsx/FileSaver.min.js"></script>
        <script src="../plugins/export-xlsx/tableexport.min.js"></script>

        <!-- ZIP -->
        <script src="../plugins/jszip/jszip.js"></script>
        <script src="../plugins/jszip/dist/jszip-utils.js"></script>
        <script src="../plugins/FileSaver/dist/FileSaver.js"></script>
        
        <script type="text/javascript" src="scripts/compra_grano.js"></script>         

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>
    <?php    
  }

  ob_end_flush();
?>
