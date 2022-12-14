<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{
    ?>
    <!doctype html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Usuarios | Admin Integra</title>

        <?php $title = "Usuarios"; require 'head.php'; ?>
        
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['acceso']==1){
            //require 'enmantenimiento.php';
            ?>    
          
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Usuarios</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="usuario.php">Home</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
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
                          <h3 class="card-title btn-regresar" style="display: none;">
                            <button type="button" class="btn bg-gradient-warning" onclick="limpiar_form_usuario(); show_hide_form(1);"><i class="fas fa-arrow-left"></i> Regresar</button>                            
                            <b class="trabajador-name"></b>
                          </h3>
                          <h3 class="card-title btn-agregar">
                            <button type="button" class="btn bg-gradient-success" onclick="permisos(); limpiar_form_usuario(); show_hide_form(2);"><i class="fas fa-user-plus"></i> Agregar</button>
                            Usuarios que administran el sistema  
                          </h3>                
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                          <div id="mostrar-tabla">
                            <table id="tabla-usuarios" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Aciones</th>
                                  <th>Nombres</th>
                                  <th>Telefono</th>
                                  <th>Usuario</th>
                                  <th>Cargo</th>
                                  <th>Ultm. Sesion</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody>                         
                                
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th>Aciones</th>
                                  <th>Nombres</th>
                                  <th>Telefono</th>
                                  <th>Usuario</th>
                                  <th>Cargo</th>
                                  <th>Ultm. Sesion</th>
                                  <th>Estado</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <div id="mostrar-form" style="display: none;">                            
                             
                            <form id="form-usuario" name="form-usuario"  method="POST" >                      
                              <div class="card-body">
                                <div class="row" id="cargando-1-fomulario">
                                  <!-- id usuario -->
                                  <input type="hidden" name="idusuario" id="idusuario" />

                                  <!-- Trabajador -->
                                  <div class="col-12 col-sm-9 col-md-9 col-lg-7 col-xl-7">
                                    <div class="form-group">
                                      <label for="trabajador" id="trabajador_c">Trabajador</label>                               
                                      <select name="trabajador" id="trabajador" class="form-control select2" style="width: 100%;"  onchange="cargo_trabajador();" > </select>
                                      <input type="hidden" name="trabajador_old" id="trabajador_old">
                                    </div>                                                        
                                  </div>

                                  <!-- adduser --> 
                                  <div class="col-12 col-sm-3 col-md-3 col-lg-1 col-xl-1">
                                    <div class="form-group">
                                      <label class="text-white d-none show-min-width-576px">.</label> 
                                      <label class="d-none show-max-width-576px" >Nuevo Trabajador</label>
                                      <a data-toggle="modal" href="#modal-agregar-trabajador" >
                                        <button type="button" class="btn btn-success btn-block" data-toggle="tooltip" data-original-title="Agregar nuevo Trabajador" onclick="limpiar_form_trabajador();">
                                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </button>
                                      </a>
                                    </div>
                                  </div>

                                  <!-- cargo -->
                                  <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="cargo">Cargo</label>                               
                                      <input type="text" name="cargo" class="form-control" id="cargo" placeholder="Cargo trabajador" readonly>

                                    </div>                                                     
                                  </div>

                                  <!-- Login -->
                                  <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="login">Login <small class="text-danger">(Dato para ingresar al sistema)</small></label>
                                      <input type="text" name="login" class="form-control" id="login" placeholder="Login" autocomplete="off" onkeyup="convert_minuscula(this);">
                                    </div>
                                  </div>

                                  <!-- Contrase??a -->
                                  <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="password">Contrase??a <small class="text-danger">(por defecto "123456")</small></label>
                                      <input type="password" name="password" class="form-control" id="password" placeholder="Contrase??a" autocomplete="off">
                                      <input type="hidden" name="password-old"   id="password-old"  >
                                    </div>
                                  </div>     

                                  <!-- permisos -->
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="mb-0">
                                      <label class="ml-1" for="permisos">Permisos</label>                               
                                      <div id="permisos">
                                        <i class="fas fa-spinner fa-pulse fa-2x"></i>
                                      </div>
                                    </div>
                                  </div>

                                  <!-- Progress -->
                                  <div class="col-md-12">
                                    <div class="form-group">
                                      <div class="progress" id="div_barra_progress_usuario" style="display: none !important;">
                                        <div id="barra_progress_usuario" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                              <div class="card-footer justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="show_hide_form(1);"> <i class="fas fa-arrow-left"></i> Close</button>
                                <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                              </div>                      
                            </form>
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
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Agregar trabajador</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form id="form-trabajador" name="form-trabajador" method="POST">
                          <div class="card-body">
                            <div class="row" id="cargando-3-fomulario">
                              <!-- id trabajador -->
                              <input type="hidden" name="idtrabajador_trab" id="idtrabajador_trab" />
                              <!-- Tipo de documento -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="tipo_documento_trab">Tipo de documento</label>
                                  <select name="tipo_documento_trab" id="tipo_documento_trab" class="form-control" placeholder="Tipo de documento">
                                    <option selected value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                    <option value="CEDULA">CEDULA</option>
                                    <option value="OTRO">OTRO</option>
                                  </select>
                                </div>
                              </div>

                              <!-- N?? de documento -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="num_documento_trab">N?? de documento</label>
                                  <div class="input-group">
                                    <input type="number" name="num_documento_trab" class="form-control" id="num_documento_trab" placeholder="N?? de documento" />
                                    <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec('_trab');">
                                      <span class="input-group-text" style="cursor: pointer;">
                                        <i class="fas fa-search text-primary" id="search_trab"></i>
                                        <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge_trab" style="display: none;"></i>
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <!-- Nombre -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                  <label for="nombre_trab">Nombre y Apellidos/Razon Social</label>
                                  <input type="text" name="nombre_trab" class="form-control" id="nombre_trab" placeholder="Nombres y apellidos" />
                                </div>
                              </div>

                              <!-- Correo electronico -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="email_trab">Correo electr??nico</label>
                                  <input type="email" name="email_trab" class="form-control" id="email_trab" placeholder="Correo electr??nico" onkeyup="convert_minuscula(this);" />
                                </div>
                              </div>

                              <!-- Telefono -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="telefono_trab">Tel??fono</label>
                                  <input type="text" name="telefono_trab" id="telefono_trab" class="form-control" data-inputmask="'mask': ['999-999-999', '+51 999 999 999']" data-mask />
                                </div>
                              </div>

                              <!-- fecha de nacimiento -->
                              <div class="col-12 col-sm-10 col-md-6 col-lg-3">
                                <div class="form-group">
                                  <label for="nacimiento_trab">Fecha Nacimiento</label>
                                  <input
                                    type="date"
                                    class="form-control"
                                    name="nacimiento_trab"
                                    id="nacimiento_trab"
                                    placeholder="Fecha de Nacimiento"
                                    onclick="calcular_edad('#nacimiento_trab', '#edad_trab', '.edad_trab');"
                                    onchange="calcular_edad('#nacimiento_trab', '#edad_trab', '.edad_trab');"
                                  />
                                  <input type="hidden" name="edad_trab" id="edad_trab" />
                                </div>
                              </div>

                              <!-- edad -->
                              <div class="col-12 col-sm-2 col-md-6 col-lg-1">
                                <div class="form-group">
                                  <label for="edad_trab">Edad</label>
                                  <p class="edad_trab" style="border: 1px solid #ced4da; border-radius: 4px; padding: 5px;">0 a??os.</p>
                                </div>
                              </div>

                              <!-- banco -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="banco_trab">Banco</label>
                                  <select name="banco_trab" id="banco_trab" class="form-control select2 banco_trab" style="width: 100%;" onchange="formato_banco();">
                                    <!-- Aqui listamos los bancos -->
                                  </select>
                                </div>
                              </div>

                              <!-- Cuenta bancaria -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="cta_bancaria_trab" class="chargue-format-1">Cuenta Bancaria</label>
                                  <input type="text" name="cta_bancaria_trab" class="form-control" id="cta_bancaria_trab" placeholder="Cuenta Bancaria" data-inputmask="" data-mask />
                                </div>
                              </div>

                              <!-- CCI -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="cci_trab" class="chargue-format-2">CCI</label>
                                  <input type="text" name="cci_trab" class="form-control" id="cci_trab" placeholder="CCI" data-inputmask="" data-mask />
                                </div>
                              </div>

                              <!-- Titular de la cuenta -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="titular_cuenta_trab">Titular de la cuenta</label>
                                  <input type="text" name="titular_cuenta_trab" class="form-control" id="titular_cuenta_trab" placeholder="Titular de la cuenta" />
                                </div>
                              </div>

                              <!-- Ruc -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="ruc_trab">Ruc</label>
                                  <input type="number" name="ruc_trab" class="form-control" id="ruc_trab" placeholder="Ingrese n??mero de ruc" />
                                </div>
                              </div>
                              <!-- cargo_trabajador  -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="cargo_trabajador_trab">Cargo </label>
                                  <select name="cargo_trabajador_trab" id="cargo_trabajador_trab" class="form-control select2 cargo_trabajador_trab" style="width: 100%;">
                                    <!-- Aqui listamos los cargo_trabajador -->
                                  </select>
                                </div>
                              </div>

                              <!-- Sueldo(Mensual) -->
                              <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                                <div class="form-group">
                                  <label for="sueldo_mensual_trab">Sueldo(Mensual)</label>
                                  <input type="number" step="any" name="sueldo_mensual_trab" class="form-control" id="sueldo_mensual_trab" onclick="sueld_mensual();" onkeyup="sueld_mensual();" />
                                </div>
                              </div>

                              <!-- Sueldo(Diario) -->
                              <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                                <div class="form-group">
                                  <label for="sueldo_diario_trab">Sueldo(Diario)</label>
                                  <input type="number" step="any" name="sueldo_diario_trab" class="form-control" id="sueldo_diario_trab" readonly />
                                </div>
                              </div>

                              <!-- Direccion -->
                              <div class="col-12 col-sm-12 col-md-6 col-lg-8">
                                <div class="form-group">
                                  <label for="direccion_trab">Direcci??n</label>
                                  <input type="text" name="direccion_trab" class="form-control" id="direccion_trab" placeholder="Direcci??n" />
                                </div>
                              </div>

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
                                  <div class="progress" id="div_barra_progress_trabajador" style="display: none !important;">
                                    <div id="barra_progress_trabajador" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                          <button type="submit" style="display: none;" id="submit-form-trabajador">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="limpiar_form_trabajador();" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_trabajador">Guardar Cambios</button>
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

        <script type="text/javascript" src="scripts/usuario.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }) </script>

      </body>
    </html> 
    
    <?php  
  }
  ob_end_flush();

?>
