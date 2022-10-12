<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{
    ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Pago Trabajador | Admin Integra</title>

        <?php $title = "Trabajadores"; require 'head.php'; ?>
        <link rel="stylesheet" href="../dist/css/switch_domingo.css">

      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['recurso']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1> <i class="fas fa-dollar-sign nav-icon"></i> Pago Trabajador: </h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="trabajador.php">Home</a></li>
                        <li class="breadcrumb-item active">Pago</li>
                      </ol>
                    </div>
                  </div>
                </div>
                <!-- /.container-fluid -->
              </section>

              <!-- Main content -->
              <section class="content">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12">
                      <div class="card card-primary card-outline">
                        <div class="card-header">
                          <h3 class="card-title">
                            
                            <button type="button" class="btn bg-gradient-warning" id="btn-regresar" style="display: none;" onclick="show_hide_table(1);"><i class="fas fa-arrow-left"></i> Regresar</button>
                            <button type="button" class="btn bg-gradient-success" id="btn-agregar"data-toggle="modal" style="display: none;" data-target="#modal-agregar-trabajador" ><i class="fa-solid fa-circle-plus"></i> Agregar</button>
                            Admnistra de manera tu pagos de trabajadores
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                        <div id="div-tabla-trabajador">
                          <!-- Lista de trabajdores activos -->                      
                          <table id="tabla-trabajador" class="table table-bordered table-striped display" style="width: 100% !important;">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="">Aciones</th>
                                <th>Nombres</th>
                                <th>Cargo</th>
                                <th>Sueldo</th>
                                <th>Telefono</th>
                                <th>Fecha Nac. / Edad</th>
                                <th>Cuenta bancaria</th>
                                <th>Estado</th>
                                <th>Nombres</th>
                                <th>Tipo</th>
                                <th>Num Doc.</th>
                                <th>Nacimiento</th>
                                <th>Edad</th>
                                <th>Banco</th>
                                <th>Cta. Cte.</th>
                                <th>CCI</th>
                                <th>Sueldo mensual</th>
                                <th>Sueldo Diario</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">#</th>
                                <th>Aciones</th>
                                <th>Nombres</th>
                                <th>Cargo</th>
                                <th>Sueldo</th>
                                <th>Telefono</th>
                                <th>Fecha Nac. / Edad</th>
                                <th>Cuenta bancaria</th>
                                <th>Estado</th>
                                <th>Nombres</th>
                                <th>Tipo</th>
                                <th>Num Doc.</th>
                                <th>Nacimiento</th>
                                <th>Edad</th>
                                <th>Banco</th>
                                <th>Cta. Cte.</th>
                                <th>CCI</th>
                                <th>Sueldo mensual</th>
                                <th>Sueldo Diario</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                        <div id="div-tabla-pago-trabajador" style="display: none !important;">
                          <!-- Lista de trabajdores activos -->                      
                          <table id="tabla-pago-trabajador" class="table table-bordered table-striped display" style="width: 100% !important; ">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                
                                <th>Año</th>
                                <th>Mes</th>
                                <th>Sueldo</th>
                                <th >Total pagado</th>
                                <th>Ver Detalle</th>
                                
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">#</th>
                                
                                <th>Año</th>
                                <th>Mes</th>
                                <th>Sueldo</th>
                                <th>Total pagado</th>
                                <th>Ver Detalle</th>

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

                <!-- Modal agregar trabajador -->
                <div class="modal fade" id="modal-agregar-trabajador">
                  <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Pagar trabajador</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-trabajador" name="form-trabajador" method="POST">
                          <div class="card-body">

                            <div class="row" id="cargando-1-fomulario">
                              <!-- id trabajador -->
                              <input type="hidden" name="idpago_trabajador" id="idpago_trabajador" />
                              <input type="hidden" name="idtrabajador" id="idtrabajador" />

                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-12 ">
                                <div class="form-group">
                                  <label for="nombre_trab">Nombre y Apellidos</label>
                                  <input type="text" disabled  name="nombre_trabajador" class="form-control" id="nombre_trabajador" />                                  
                                  
                                    <!-- Aqui listamos los cargo_trabajador -->
                                  </select>
                                </div>
                              </div>
                              <!-- Swichs permanente -->
                              <!-- <div class="col-4 col-sm-5 col-md-6 col-lg-2">
                                  <label for="socio" class="d-none show-min-width-576px">Socio <smclass="text-danger sino">(NO)</small> </label>
                                  <div class="switch-toggle">
                                    <input type="checkbox" id="socio" >
                                    <label for="socio" onclick="habilitando_socio();" onchange="habilitando_socio();"></label>
                                  </div>
                                  <input type="hidden" name="input_socio" id="input_socio" value="0"  >
                              </div> -->
                              <!-- Sueldo(Mensual) -->
                              <div class="col-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label for="extraer_cargo">Cargo Trabajador</label>
                                  <input type="text" disabled step="any" name="extraer_cargo" class="form-control" id="extraer_cargo" />
                                </div>
                              </div>
                              
                              <!-- Sueldo(Mensual) -->
                              <div class="col-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label for="sueldo_mensual">Sueldo Mensual</label>
                                  <input type="number" disabled step="any" name="sueldo_mensual" class="form-control" id="sueldo_mensual"/>
                                </div>
                              </div>

                              <!-- fecha de nacimiento -->
                              <div class="col-12 col-sm-10 col-md-6">
                                <div class="form-group">
                                  <label for="fecha_pago">Fecha de Pago</label>
                                  <input
                                    type="date"
                                    class="form-control"
                                    name="fecha_pago"
                                    id="fecha_pago"
                                    placeholder="Fecha de Pago"
                                    
                                  />
                                  
                                </div>
                              </div> 

                              <!-- Monto a pagar -->
                              <div class="col-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label for="monto_pago">Monto a Pagar</label>
                                  <input type="number" step="any" name="monto_pago" class="form-control" id="monto_pago" />
                                </div>
                              </div>

                              <!-- Monto a restante -->
                              <!--<div class="col-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label for="monto_restante">Monto restante</label>
                                  <input type="number" disabled step="any" name="monto_restante" class="form-control" id="monto_restante" />
                                </div>
                              </div>  -->                     


                              <!--descripcion-->
                              <div class="col-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                  <label for="descripcion">Descripción </label> <br />
                                  <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                </div>
                              </div>

                              <!-- imagen perfil -->
                              <div class="col-12 col-sm-6 col-md-6 ">
                                <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"></div>
                                <label for="comprobante">Comprobante de Pago</label> <br />
                                <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="comprobante_i" style="cursor: pointer !important;" width="auto" />
                                <input style="display: none;" type="file" name="comprobante" id="comprobante" accept="image/*" />
                                <input type="hidden" name="comprobante_actual" id="comprobante_actual" />
                                <div class="text-center" id="comprobante_nombre"><!-- aqui va el nombre de la FOTO --></div>
                              </div>

                              <!-- Progress -->
                              <div class="col-md-12">
                                <div class="form-group">
                                  <div class="progress" id="div_barra_progress_trabajador" style="display: none !important;">
                                    <div id="barra_progress_trabajador" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row" id="cargando-2-fomulario" style="display: none;" >
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                                  
                          </div>
                          <!-- /.card-body -->
                          <button type="submit" style="display: none;" id="submit-form-trabajador">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="limpiar_form_trabajador();" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Modal ver trabajador-->
                <div class="modal fade" id="modal-ver-pago_trabajador">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos trabajador</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div id="datostrabajador" class="class-style">
                          <!-- vemos los datos del trabajador -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Modal elegir Activo -->
                <div class="modal fade" id="modal-desglose-de-pago">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">
                          <a data-toggle="modal" data-target="#modal-agregar-material-activos-fijos">
                            <button id="btnAgregarArt" type="button" class="btn btn-success btn-sm" onclick="limpiar_materiales()"><span class="fa fa-plus"></span> Crear Productos</button>
                          </a>
                          Seleccionar Activo Fijo
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body table-responsive">
                        <table id="tblaactivos" class="table table-striped table-bordered table-condensed table-hover" style="width: 100% !important;">
                          <thead>
                            <th data-toggle="tooltip" data-original-title="Opciones">Op.</th>
                            <th>Nombre Activo</th>
                            <th>Clasificación</th>
                            <th data-toggle="tooltip" data-original-title="Precio Unitario">P/U.</th>
                            <th>Descripción</th>
                            <th data-toggle="tooltip" data-original-title="Ficha Técnica" >F.T.</th>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

              </section>
              <!-- /.content -->
            </div>

            <?php
          }else{
            require 'noacceso.php';
          }
          require 'footer.php';
          ?>
        </div>
        <!-- /.content-wrapper -->
        
        <?php require 'script.php'; ?>       
        
        <!-- Funciones del modulo -->
        <script type="text/javascript" src="scripts/pago_trabajador.js"></script>

        <script> $(function () {  $('[data-toggle="tooltip"]').tooltip();  }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
