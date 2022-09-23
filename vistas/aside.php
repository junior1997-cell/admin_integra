<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="escritorio.php" class="brand-link">
    <img src="../dist/svg/logo-icono.svg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8;" />
    <span class="brand-text font-weight-light">Admin Integra</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar"> 
    <!-- Sidebar user panel (optional) -->
    <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../dist/svg/empresa-logo.svg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Construccion del baño portodoloque parte de no se</a>
      </div>
    </div>     -->

    <!-- SidebarSearch Form -->
    <div class="form-inline mt-4">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" />
        <div class="input-group-append"><button class="btn btn-sidebar"><i class="fas fa-search fa-fw"></i></button></div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column /*nav-flat*/" data-widget="treeview" role="menu" data-accordion="false">
        <!-- MANUAL DE USUARIO -->
        <li class="nav-item">
          <a href="manual_de_usuario.php" class="nav-link pl-2" id="mManualDeUsuario">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Manual de Usuario
              <span class="right badge badge-success">new</span>
            </p>
          </a>
        </li>
        <?php if ($_SESSION['escritorio']==1) {  ?>
          <!-- ESCRITORIO -->
          <li class="nav-item">
            <a href="escritorio.php" class="nav-link pl-2" id="mEscritorio">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Escritorio
                <span class="right badge badge-danger">Home</span>
              </p>
            </a>
          </li>
        <?php  }  ?>

        <?php if ($_SESSION['acceso']==1) {  ?>
          <!-- ACCESOS -->
          <li class="nav-item  b-radio-3px" id="bloc_Accesos">
            <a href="#" class="nav-link pl-2" id="mAccesos">
              <i class="nav-icon fas fa-shield-alt"></i>
              <p>
                Accesos
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview ">
              <!-- Usuarios del sistema -->
              <li class="nav-item ">
                <a href="usuario.php" class="nav-link " id="lUsuario">
                  <i class="nav-icon fas fa-users-cog"></i>
                  <p>Usuarios</p>
                </a>
              </li>
              <!-- Permisos de los usuarios del sistema -->
              <li class="nav-item ">
                <a href="permiso.php" class="nav-link" id="lPermiso">
                  <i class="nav-icon fas fa-lock"></i>
                  <p>Permisos</p>
                </a>
              </li>      
            </ul>
          </li>
        <?php  }  ?>


        <?php if ($_SESSION['recurso']==1) {  ?>
          <!-- Recursos -->
          <li class="nav-item  b-radio-3px" id="bloc_Recurso">
            <a href="#" class="nav-link pl-2" id="mRecurso">
              <i class="nav-icon fas fa-project-diagram"></i>
              <p>
                Recursos <i class="fas fa-angle-left right"></i> <span class="badge badge-info right">6</span>
              </p>
            </a>
            <ul class="nav nav-treeview ">

              <!-- Usuarios del sistema -->
              <li class="nav-item ">
                <a href="all_trabajador.php" class="nav-link" id="lAllTrabajador">
                  <i class="nav-icon fas fa-users"></i>
                  <p>All-Trabajador</p>
                </a>
              </li>

              <!-- Proveedores de la empresa -->
              <li class="nav-item ">
                <a href="all_proveedor.php" class="nav-link" id="lAllProveedor">
                  <i class="nav-icon fas fa-truck"></i>
                  <p>All-Proveedor</p>
                </a>
              </li>  
              
              <!-- Producto para la empresa -->
              <li class="nav-item ">
                <a href="producto.php" class="nav-link" id="lAllProducto">                  
                <img src="../dist/svg/palana-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" >
                  <p>Producto</p>
                </a>
              </li>              
              
              <!-- Datos Generales Bancos y color -->
              <li class="nav-item ">
                <a href="otros.php" class="nav-link" id="lOtros">
                  <i class="nav-icon fas fa-coins"></i>
                  <p>Otros</p>
                </a>
              </li>
            </ul>
          </li>
        <?php  }  ?> 

        <?php if ($_SESSION['otra_factura']==1) {  ?>
          <li class="nav-item">
            <a href="otra_factura.php" class="nav-link pl-2" id="lOtraFactura">
              <i class="nav-icon fas fa-receipt"></i>
              <p>Otras Facturas</p>
            </a>
          </li>
        <?php  }  ?>
        
        <?php if ($_SESSION['resumen_factura']==1) {  ?>
          <li class="nav-item">
            <a href="resumen_factura.php" class="nav-link pl-2" id="lResumenFacura">            
              <i class="nav-icon fas fa-poll"></i>
              <p>Resumen de Facturas</p>
            </a>
          </li>
        <?php  }  ?>       
        
        <?php if ($_SESSION['papelera']==1) {  ?>
          <li class="nav-item">
            <a href="papelera.php" class="nav-link pl-2" id="mPapelera">
              <i class="nav-icon fas fa-trash-alt"></i>
              <p>Papelera</p>
            </a>
          </li>
        <?php  }  ?>
        
        <li class="nav-header">MÓDULOS</li>           

        <!-- <li class="nav-header bg-color-2c2c2c">LOGÍSTICA Y ADQUISICIONES</li> -->
        
        <!-- LOGÍSTICA Y ADQUISICIONES -->      
        <li class="nav-item " id="bloc_LogisticaAdquisiciones">
          <a href="#" class="nav-link bg-color-2c2c2c" id="mLogisticaAdquisiciones" style="padding-left: 7px;">
            <i class="nav-icon far fa-circle"></i>
            <p class="font-size-14px">LOGÍSTICA Y ADQUISICIONES <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">

            <?php if ($_SESSION['compra_insumos']==1) {  ?>   
              <!-- COMPRAS -->      
              <li class="nav-item  b-radio-3px" id="bloc_Compras">
                <a href="#" class="nav-link pl-2" id="mCompra">
                  <i class="fas fa-shopping-cart nav-icon"></i>
                  <p>Compras <i class="fas fa-angle-left right"></i> <span class="badge badge-info right">3</span></p>
                </a>
                <ul class="nav nav-treeview">
                  <!-- Compras del proyecto -->
                  <li class="nav-item ">
                    <a href="compra_insumos.php" class="nav-link" id="lCompras">
                      <i class="nav-icon fas fa-cart-plus"></i> <p>Compras</p>
                    </a>
                  </li>
                  <!-- Resumend de Insumos -->
                  <li class="nav-item ">
                    <a href="resumen_insumos.php" class="nav-link" id="lResumenInsumos">
                      <i class="nav-icon fas fa-tasks"></i> <p>Resumen de insumos</p>
                    </a>
                  </li> 
                  
                  <!-- graficos insumos -->
                  <li class="nav-item ">
                    <a href="chart_compra_insumo.php" class="nav-link" id="lChartCompraInsumo">
                      <i class="nav-icon fas fa-chart-line"></i> <p>Gráficos</p>
                    </a>
                  </li> 
                </ul>
              </li>
            <?php  }  ?>            

            <?php if ($_SESSION['subcontrato']==1) {  ?>  
            <li class="nav-item ">
              <a href="sub_contrato.php" class="nav-link pl-2" id="lSubContrato">
                <i class="nav-icon fas fa-hands-helping"></i>
                <p>Sub Contrato </p>
              </a>
            </li>
            <?php  }  ?>            
            
            <?php if ($_SESSION['planilla_seguro']==1) {  ?>
              <!-- PLANILLAS Y SEGUROS -->       
              <li class="nav-item ">
                <a href="planillas_seguros.php" class="nav-link pl-2" id="lPlanillaSeguro">
                  <!--<i class="nav-icon fas fa-map-marked-alt"></i>lanilla-seguro-ico.svg-->
                  <img src="../dist/svg/planilla-seguro-ico.svg" class="nav-icon" alt="" style="width: 21px !important;" >
                  <p>Planillas y seguros</p>
                </a>
              </li>
            <?php  }  ?>

            <?php if ($_SESSION['otro_gasto']==1) {  ?>
              <!-- OTROS GASTOS -->       
              <li class="nav-item ">
                <a href="otro_gasto.php" class="nav-link pl-2" id="lOtroGasto">
                  <i class="nav-icon fas fa-network-wired"></i>
                  <p>Otros Gastos </p>
                </a>
              </li>
            <?php  }  ?>
            
            <?php if ($_SESSION['viatico']==1) {  ?>
              <!-- BIÁTICOS -->
              <li class="nav-item "  id="bloc_Viaticos">
                <a href="#" class="nav-link pl-2" id="mViatico">
                  <i class="nav-icon fas fa-plane"></i>
                  <p>Viáticos <i class="right fas fa-angle-left"></i> <span class="badge badge-info right">3</span> </p>
                </a>
                <ul class="nav nav-treeview">
                  <!-- TRANSPORTE -->
                  <li class="nav-item">
                    <a href="transporte.php" class="nav-link" id="lTransporte">
                      <i class="fas fa-shuttle-van nav-icon"></i>
                      <p>Transporte</p>
                    </a>
                  </li>
                  <!-- HOSPEDAJE -->
                  <li class="nav-item">
                    <a href="hospedaje.php" class="nav-link" id="lHospedaje"> 
                      <i class="fas fa-hotel nav-icon"></i>
                      <p>Hospedaje</p>
                    </a>
                  </li>
                  <!-- COMIDA -->
                  <li class="nav-item  b-radio-3px" id="sub_bloc_comidas">
                    <a href="#" class="nav-link"  id="sub_mComidas">
                      <i class="fas fa-fish nav-icon"></i>
                      <p>Comida <i class="right fas fa-angle-left"></i> <span class="badge badge-info right">3</span></p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="pension.php" class="nav-link" id="lPension">
                          <i class="fas fa-utensils nav-icon"></i>
                          <p>Pensión</p>
                        </a>
                      </li>
                      
                      <li class="nav-item">
                        <a href="comidas_extras.php" class="nav-link" id="lComidasExtras" >
                          <i class="fas fa-drumstick-bite nav-icon"></i>
                          <p>Comidas - extras</p>
                        </a>
                      </li>
                    </ul>
                  </li>              
                </ul>
              </li>
            <?php  }  ?>
          </ul>
        </li>        

      </ul>      
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
