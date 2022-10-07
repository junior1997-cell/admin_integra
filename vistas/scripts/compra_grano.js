//Requejo99@
var reload_detraccion = "";

var tabla_compra_insumo;
var tabla_comprobantes;

var tabla_compra_x_proveedor;
var tabla_detalle_compra_x_proveedor;

var tablamateriales;

var tabla_pagos1;
var tabla_pagos2;
var tabla_pagos3;

var array_doc = [];
var host = window.location.host == 'localhost'? `http://localhost/admin_integra/dist/docs/compra_insumo/comprobante_compra/` : `${window.location.origin}/dist/docs/compra_insumo/comprobante_compra/` ;

var array_class_trabajador = [];

//Función que se ejecuta al inicio
function init() {

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_ComprasGrano").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mCompraGrano").addClass("active bg-green");

  $("#lComprasGrano").addClass("active");

  //$("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Cliente", '#idproveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Cliente", '#filtro_proveedor', null);
  // lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_pago', null);
  // lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_prov', null);
  // lista_select2("../ajax/ajax_general.php?op=select2Color", '#color_p', null);
  // lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida_p', null);
  // lista_select2("../ajax/ajax_general.php?op=select2Categoria_all", '#categoria_insumos_af_p', null);
  // lista_select2("../ajax/ajax_general.php?op=select2TierraConcreto", '#idtipo_tierra_concreto', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════

  $("#guardar_registro_compras").on("click", function (e) {  $("#submit-form-compras").submit(); });

  $("#guardar_registro_proveedor").on("click", function (e) { $("#submit-form-proveedor").submit(); });

  $("#guardar_registro_pago").on("click", function (e) {  $("#submit-form-pago").submit(); });

  $("#guardar_registro_comprobante_compra").on("click", function (e) {  $("#submit-form-comprobante-compra").submit();  });  

  $("#guardar_registro_material").on("click", function (e) {  $("#submit-form-materiales").submit(); });  
  

  // ══════════════════════════════════════ INITIALIZE SELECT2 - COMPRAS ══════════════════════════════════════

  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });

  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione Comprobante", allowClear: true, });

  $("#metodo_de_pago").select2({ theme: "bootstrap4", placeholder: "Selecione método", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - PAGO COMPRAS ══════════════════════════════════════

  $("#banco_pago").select2({ templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });  

  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Selecione una forma de pago", allowClear: true, });

  $("#tipo_pago").select2({ theme: "bootstrap4", placeholder: "Selecione un tipo de pago", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - PROVEEDOR ══════════════════════════════════════

  $("#banco_prov").select2({templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });
  
  // ══════════════════════════════════════ INITIALIZE SELECT2 - MATERIAL ══════════════════════════════════════

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione Cliente", allowClear: true, });

  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });

  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  $('#precio_unitario_p').number( true, 2 );
  $('#precio_sin_igv_p').number( true, 2 );
  $('#precio_igv_p').number( true, 2 );
  $('#precio_total_p').number( true, 2 );

  no_select_tomorrow('#fecha_compra');

  // Formato para telefono
  $("[data-mask]").inputmask();

  //filtros();
}

$('.click-btn-fecha-inicio').on('click', function (e) {$('#filtro_fecha_inicio').focus().select(); });
$('.click-btn-fecha-fin').on('click', function (e) {$('#filtro_fecha_fin').focus().select(); });

function templateBanco (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/banco/logo/${state.title}`: '../dist/docs/banco/logo/logo-sin-banco.svg'; 
  var onerror = `onerror="this.src='../dist/docs/banco/logo/logo-sin-banco.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};


//vaucher - pago
$("#doc3_i").click(function () { $("#doc3").trigger("click"); });
$("#doc3").change(function (e) { addImageApplication(e, $("#doc3").attr("id")); });

// Eliminamos el COMPROBANTE - PAGO
function doc3_eliminar() {
  $("#doc3").val("");
  $("#doc_old_3").val("");  
  $("#doc3_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc3_nombre").html("");
}

// ::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C O M P R A S :::::::::::::::::::::::::::::::::::::::::::::

//Función limpiar
function limpiar_form_compra() {
  $(".tooltip").removeClass("show").addClass("hidde");

  $("#idcompra_proyecto").val("");
  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprobante").val("Ninguno").trigger("change");
  $("#glosa").val("null").trigger("change");

  $("#serie_comprobante").val("");
  $("#val_igv").val(0);
  $("#descripcion").val("");
  
  $("#total_venta").val("");  
  $(".total_venta").html("0");

  $(".subtotal_compra").html("S/ 0.00");
  $("#subtotal_compra").val("");

  $(".igv_compra").html("S/ 0.00");
  $("#igv_compra").val("");

  $(".total_venta").html("S/ 0.00");
  $("#total_venta").val("");

  $("#estado_detraccion").val("0");
  $('#my-switch_detracc').prop('checked', false); 

  $(".filas").remove();

  cont = 0;

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function show_hide_form(flag) {

  if (flag == 1) {
    // show tabla principal
    $("#div_tabla_compra").show();
    $("#div_tabla_compra_proveedor").hide();
    $("#div_form_agregar_compras_grano").hide();
    $("#div_tabla_pago_compras_grano").hide();

    $("#btn_agregar").show();
    $("#btn_regresar").hide();    
    $("#btn_pagar").hide();

    $("#guardar_registro_compras").hide();
    
  } else if(flag == 2) {
    // show tabla detalle compra por cliente
    $("#div_tabla_compra").hide();
    $("#div_tabla_compra_proveedor").show();
    $("#div_form_agregar_compras_grano").hide();
    $("#div_tabla_pago_compras_grano").hide();

    $("#btn_agregar").hide();
    $("#btn_regresar").show();    
    $("#btn_pagar").hide();
  } else if(flag == 3) {
    // show form compra
    $("#div_tabla_compra").hide();
    $("#div_tabla_compra_proveedor").hide();
    $("#div_form_agregar_compras_grano").show();
    $("#div_tabla_pago_compras_grano").hide();

    $("#btn_agregar").hide();
    $("#btn_regresar").show();    
    $("#btn_pagar").hide();
  } else if(flag == 4) {
    // show form pago
    $("#div_tabla_compra").hide();
    $("#div_tabla_compra_proveedor").hide();
    $("#div_form_agregar_compras_grano").hide();
    $("#div_tabla_pago_compras_grano").show();

    $("#btn_agregar").hide();
    $("#btn_regresar").show();    
    $("#btn_pagar").hide();
  }
  array_class_trabajador = [];  

  // $(".leyecnda_pagos").hide();
  // $(".leyecnda_saldos").hide();

  limpiar_form_compra();
  limpiar_form_proveedor();
}

//TABLA - COMPRAS
function tbla_principal( fecha_1, fecha_2, id_proveedor, comprobante) {
  //console.log(idproyecto);
  tabla_compra_insumo = $("#tabla-compra-grano").dataTable({
    responsive: true, 
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,8,9], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,8,9,11], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,4,5,6,8,9,11], } }, {extend: "colvis"} ,        
    ],
    ajax: {
      url: `../ajax/compra_grano.php?op=tbla_principal&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },     
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
      if (data[5] != '') { $("td", row).eq(5).addClass('text-center'); }
      if (data[6] != '') { $("td", row).eq(6).addClass('text-nowrap'); }    
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      // { targets: [6], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      // { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      // { targets: [8,11],  visible: false,  searchable: false,  },
    ],
  }).DataTable();

  $(tabla_compra_insumo).ready(function () {  $('.cargando').hide(); });

  //console.log(idproyecto);
  tabla_compra_x_proveedor = $("#tabla-compra-cliente").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
    ajax: {
      url: `../ajax/compra_grano.php?op=tabla_compra_x_cliente`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      if (data[5] != '') {
        $("td", row).eq(5).addClass('text-right');
      }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();
}

//facturas agrupadas por proveedor.
function listar_facuras_cliente(idproveedor, idproyecto) {

  show_hide_form(2)

  tabla_detalle_compra_x_proveedor = $("#detalles-tabla-compra-prov").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
    ajax: {
      url: "../ajax/compra_grano.php?op=listar_detalle_compra_x_cliente&idproyecto=" + idproyecto + "&idproveedor=" + idproveedor,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar - COMPRAS
function guardar_y_editar_compras(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-compras")[0]);  

  Swal.fire({
    title: "¿Está seguro que deseas guardar esta compra?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
    preConfirm: (input) => {
      return fetch("../ajax/compra_grano.php?op=guardaryeditarcompra", {
        method: 'POST', // or 'PUT'
        body: formData, // data can be `string` or {object}!        
      }).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); });
    },
    showLoaderOnConfirm: true,
  }).then((result) => {
    if (result.isConfirmed) {
      if (result.value.status == true){        
        Swal.fire("Correcto!", "Compra guardada correctamente", "success");

        tabla_compra_insumo.ajax.reload(null, false);
        tabla_compra_x_proveedor.ajax.reload(null, false);

        limpiar_form_compra(); show_hide_form();
        
        $("#modal-agregar-usuario").modal("hide");        
      } else {
        ver_errores(result.value);
      }      
    }
  });  
}

//Función para eliminar registros
function eliminar_compra(idcompra_proyecto, nombre) {

  $(".tooltip").removeClass("show").addClass("hidde");

  crud_eliminar_papelera(
    "../ajax/compra_grano.php?op=anular",
    "../ajax/compra_grano.php?op=eliminar_compra", 
    idcompra_proyecto, 
    "!Elija una opción¡", 
    `<b class="text-danger">${nombre}</b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu compra ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu compra ha sido Eliminado.' ) }, 
    function(){ tabla_compra_insumo.ajax.reload(null, false); tabla_compra_x_proveedor.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}

// .......::::::::::::::::::::::::::::::::::::::::: AGREGAR FACURAS, BOLETAS, NOTA DE VENTA, ETC :::::::::::::::::::::::::::::::::::.......
//Declaración de variables necesarias para trabajar con las compras y sus detalles

document.getElementById("btn-agregar-detalle-form-compra").addEventListener("click", function() {
  agregarDetalleComprobante();
});

var impuesto = 18;
var cont = 0;
var detalles = 0;

function agregarDetalleComprobante() {
  
  var fila = `
  <tr class="filas" id="fila${cont}">         
    <td class="">      
      <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${cont})"><i class="fas fa-times"></i></button>
    </td>
    <td class="">       
      <select class="form-control w-140px" name="tipo_grano[]">
        <option>PERGAMINO</option>
        <option>COCO</option>
      </select>   
    </td>
    <td class="">
      <input type="text" class="input-no-border w-70px unidad_medida_${cont}"  name="unidad_medida[]" id="unidad_medida[]" value="KILO">    
    </td>
    <td class="form-group"><input type="number" class="w-140px form-control peso_bruto_${cont}" name="peso_bruto[]" value="0" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
    <td class="form-group"><input type="number" class="w-140px form-control dcto_humendad_${cont}" name="dcto_humendad[]" value="0" min="0.00" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
    <td class="form-group"><input type="number" class="w-140px form-control porcentaje_cascara_${cont}" name="porcentaje_cascara[]" value="0" min="0.00" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
    <td class="form-group"><input type="number" class="w-140px form-control dcto_embase_${cont}" name="dcto_embase[]" value="0" min="0.00" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>

    <td class="form-group"><input type="number" class="w-140px form-control cantidad_${cont}" name="cantidad[]" value="0" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
    <td class="form-group hidden"><input type="number" class="w-140px input-no-border precio_sin_igv_${cont}" name="precio_sin_igv[]" value="0" readonly min="0" ></td>
    <td class="form-group hidden"><input type="number" class="w-140px input-no-border precio_igv_${cont}" name="precio_igv[]" value="0" readonly ></td>
    <td class="form-group"><input type="number" class="w-140px form-control precio_con_igv_${cont}" name="precio_con_igv[]" value="0" min="0.01" required onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
    <td class="form-group"><input type="number" class="w-140px form-control descuento_${cont}" name="descuento[]" value="0" min="0" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
    <td class="text-right"><span class="text-right subtotal_producto_${cont}">0.00</span></td>
    <td class=""><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm"><i class="fas fa-sync"></i></button></td>
  </tr>`;

  detalles = detalles + 1;

  $("#detalles").append(fila);

  array_class_trabajador.push({ id_cont: cont });

  modificarSubtotales();
  
  toastr_success("Agregado!!",`Nueva Fila agregado !!`, 700);

  cont++;
  evaluar(); 
  
}

function evaluar() {
  if (detalles > 0) {
    $("#guardar_registro_compras").show();
  } else {
    $("#guardar_registro_compras").hide();
    cont = 0;
    $(".subtotal_compra").html("S/ 0.00");
    $("#subtotal_compra").val(0);

    $(".igv_compra").html("S/ 0.00");
    $("#igv_compra").val(0);

    $(".total_venta").html("S/ 0.00");
    $("#total_compra").val(0);

  }
}

function default_val_igv() { if ($("#tipo_comprobante").select2("val") == "Factura") { $("#val_igv").val(0.18); } }

function modificarSubtotales() {  

  var val_igv = $('#val_igv').val(); //console.log(array_class_trabajador);

  if ($("#tipo_comprobante").select2("val") == null) {

    $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

    $("#colspan_subtotal").attr("colspan", 9); //cambiamos el: colspan

    $("#val_igv").val(0);
    $("#val_igv").prop("readonly",true);
    $(".val_igv").html('IGV (0%)');

    $("#tipo_gravada").val('NO GRAVADA');
    $(".tipo_gravada").html('NO GRAVADA');

    if (array_class_trabajador.length === 0) {
    } else {
      array_class_trabajador.forEach((element, index) => {
        var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
        var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
        var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
        var subtotal_producto = 0;

        // Calculamos: IGV
        var precio_sin_igv = precio_con_igv;
        $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

        // Calculamos: precio + IGV
        var igv = 0;
        $(`.precio_igv_${element.id_cont}`).val(igv);

        // Calculamos: Subtotal de cada producto
        subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
        $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(subtotal_producto.toFixed(4)));
      });
      calcularTotalesSinIgv();
    }
  } else {
    if ($("#tipo_comprobante").select2("val") == "Factura") {

      $(".hidden").show(); //Mostramos: IGV, PRECIO SIN IGV

      $("#colspan_subtotal").attr("colspan", 11); //cambiamos el: colspan
      
      $("#val_igv").prop("readonly",false);

      if (array_class_trabajador.length === 0) {
        if (val_igv == '' || val_igv <= 0) {
          $("#tipo_gravada").val('NO GRAVADA');
          $(".tipo_gravada").html('NO GRAVADA');
          $(".val_igv").html(`IGV (0%)`);
        } else {
          $("#tipo_gravada").val('GRAVADA');
          $(".tipo_gravada").html('GRAVADA');
          $(".val_igv").html(`IGV (${(parseFloat(val_igv) * 100).toFixed(2)}%)`);
        }
        
      } else {
        // validamos el valor del igv ingresado        

        array_class_trabajador.forEach((element, index) => {
          var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
          var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
          var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
          var subtotal_producto = 0;

          // Calculamos: Precio sin IGV
          var precio_sin_igv = ( quitar_igv_del_precio(precio_con_igv, val_igv, 'decimal')).toFixed(2);
          $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

          // Calculamos: IGV
          var igv = (parseFloat(precio_con_igv) - parseFloat(precio_sin_igv)).toFixed(2);
          $(`.precio_igv_${element.id_cont}`).val(igv);

          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
          $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(subtotal_producto.toFixed(2)));
        });

        calcularTotalesConIgv();
      }
    } else {

      $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

      $("#colspan_subtotal").attr("colspan", 9); //cambiamos el: colspan

      $("#val_igv").val(0);
      $("#val_igv").prop("readonly",true);
      $(".val_igv").html('IGV (0%)');

      $("#tipo_gravada").val('NO GRAVADA');
      $(".tipo_gravada").html('NO GRAVADA');

      if (array_class_trabajador.length === 0) {
      } else {
        array_class_trabajador.forEach((element, index) => {
          var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
          var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
          var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
          var subtotal_producto = 0;

          // Calculamos: IGV
          var precio_sin_igv = precio_con_igv;
          $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

          // Calculamos: precio + IGV
          var igv = 0;
          $(`.precio_igv_${element.id_cont}`).val(igv);

          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
          $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(subtotal_producto.toFixed(4)));
        });

        calcularTotalesSinIgv();
      }
    }
  }
  toastr_success("Actualizado!!",`Precio Actualizado.`, 700);
}

function calcularTotalesSinIgv() {
  var total = 0.0;
  var igv = 0;
  var mtotal = 0;

  if (array_class_trabajador.length === 0) {
  } else {
    array_class_trabajador.forEach((element, index) => {
      total += parseFloat(quitar_formato_miles($(`.subtotal_producto_${element.id_cont}`).text()));
    });

    $(".subtotal_compra").html("S/ " + formato_miles(total));
    $("#subtotal_compra").val(redondearExp(total, 4));

    $(".igv_compra").html("S/ 0.00");
    $("#igv_compra").val(0.0);
    $(".val_igv").html('IGV (0%)');

    $(".total_venta").html("S/ " + formato_miles(total.toFixed(2)));
    $("#total_venta").val(redondearExp(total, 4));
  }
}

function calcularTotalesConIgv() {
  var val_igv = $('#val_igv').val();
  var igv = 0;
  var total = 0.0;

  var subotal_sin_igv = 0;

  array_class_trabajador.forEach((element, index) => {
    total += parseFloat(quitar_formato_miles($(`.subtotal_producto_${element.id_cont}`).text()));
  });

  //console.log(total); 

  subotal_sin_igv = quitar_igv_del_precio(total, val_igv, 'decimal').toFixed(2);
  igv = (parseFloat(total) - parseFloat(subotal_sin_igv)).toFixed(2);

  $(".subtotal_compra").html(`S/ ${formato_miles(subotal_sin_igv)}`);
  $("#subtotal_compra").val(redondearExp(subotal_sin_igv, 4));

  $(".igv_compra").html("S/ " + formato_miles(igv));
  $("#igv_compra").val(igv);

  $(".total_venta").html("S/ " + formato_miles(total.toFixed(2)));
  $("#total_venta").val(redondearExp(total, 4));

  total = 0.0;
}

function quitar_igv_del_precio(precio , igv, tipo ) {
  
  var precio_sin_igv = 0;

  switch (tipo) {
    case 'decimal':

      // validamos el valor del igv ingresado
      if (igv > 0 && igv <= 1) { 
        $("#tipo_gravada").val('GRAVADA');
        $(".tipo_gravada").html('GRAVADA');
        $(".val_igv").html(`IGV (${(parseFloat(igv) * 100).toFixed(2)}%)`); 
      } else { 
        igv = 0; 
        $(".val_igv").html('IGV (0%)'); 
        $("#tipo_gravada").val('NO GRAVADA');
        $(".tipo_gravada").html('NO GRAVADA');
      }

      if (parseFloat(precio) != NaN && igv > 0 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( ( parseFloat(igv) * 100 ) + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;

    case 'entero':
      
      // validamos el valor del igv ingresado
      if (igv > 0 && igv <= 100) { 
        $("#tipo_gravada").val('GRAVADA');
        $(".tipo_gravada").html('GRAVADA');
        $(".val_igv").html(`IGV (${parseFloat(igv)}%)`); 
      } else { 
        igv = 0; 
        $(".val_igv").html('IGV (0%)'); 
        $("#tipo_gravada").val('NO GRAVADA');
        $(".tipo_gravada").html('NO GRAVADA');
      }

      if (parseFloat(precio) != NaN && igv > 0 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( parseFloat(igv)  + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;
  
    default:
      $(".val_igv").html('IGV (0%)');
      toastr_error("Vacio!!","No has difinido un tipo de calculo de IGV", 700);
    break;
  } 
  
  return precio_sin_igv; 
}

function ocultar_comprob() {
  if ($("#tipo_comprobante").select2("val") == "Ninguno") {
    $("#content-serie-comprobante").hide();

    $("#content-serie-comprobante").val("");

    $("#content-descripcion").removeClass("col-lg-5").addClass("col-lg-7");
  } else {
    $("#content-serie-comprobante").show();

    $("#content-descripcion").removeClass("col-lg-7").addClass("col-lg-5");
  }
}

function eliminarDetalle(indice) {
  $("#fila" + indice).remove();

  array_class_trabajador.forEach(function (car, index, object) {
    if (car.id_cont === indice) {
      object.splice(index, 1);
    }
  });

  modificarSubtotales();

  detalles = detalles - 1;

  evaluar();

  toastr_warning("Removido!!","Producto removido", 700);
}

//mostramos para editar el datalle del comprobante de la compras
function mostrar_compra(idcompra_proyecto) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_form_compra();
  array_class_trabajador = [];

  cont = 0;
  detalles = 0;
  show_hide_form();

  $.post("../ajax/compra_grano.php?op=ver_compra_editar", { idcompra_proyecto: idcompra_proyecto }, function (e, status) {
    
    e = JSON.parse(e); // console.log(e);

    if (e.status == true) {

      if (e.data.tipo_comprobante == "Factura") {
        $(".content-igv").show();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
        $(".content-serie-comprobante").show();
      } else if (e.data.tipo_comprobante == "Boleta" || e.data.tipo_comprobante == "Nota de venta") {
        $(".content-serie-comprobante").show();
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
      } else if (e.data.tipo_comprobante == "Ninguno") {
        $(".content-serie-comprobante").hide();
        $(".content-serie-comprobante").val("");
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
      } else {
        $(".content-serie-comprobante").show();
        //$(".content-descripcion").removeClass("col-lg-7").addClass("col-lg-4");
      }

      $("#idproyecto").val(e.data.idproyecto);
      $("#idcompra_proyecto").val(e.data.idcompra_x_proyecto);
      $("#idproveedor").val(e.data.idproveedor).trigger("change");
      $("#fecha_compra").val(e.data.fecha_compra);
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");
      $("#serie_comprobante").val(e.data.serie_comprobante).trigger("change");
      $("#val_igv").val(e.data.val_igv);
      $("#descripcion").val(e.data.descripcion);
      $("#glosa").val(e.data.glosa).trigger("change");

      if (e.data.estado_detraccion == 0) {
        $("#estado_detraccion").val("0");
        $('#my-switch_detracc').prop('checked', false); 
      } else {
        $("#estado_detraccion").val("1");
        $('#my-switch_detracc').prop('checked', true); 
      }

      if (e.data.producto) {

        e.data.producto.forEach((element, index) => {

          var img = "";

          if (element.imagen == "" || element.imagen == null) {
            img = `../dist/docs/material/img_perfil/producto-sin-foto.svg`;
          } else {
            img = `../dist/docs/material/img_perfil/${element.imagen}`;
          }

          var fila = `
          <tr class="filas" id="fila${cont}">
            <td>
              <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_material(${element.idproducto}, ${cont})"><i class="fas fa-pencil-alt"></i></button>
              <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${cont})"><i class="fas fa-times"></i></button></td>
            </td>
            <td>
              <input type="hidden" name="idproducto[]" value="${element.idproducto}">
              <input type="hidden" name="ficha_tecnica_producto[]" value="${element.ficha_tecnica_producto}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_material('${img}', '${encodeHtml(element.nombre_producto)}')">
                <span class="username"><p class="mb-0 nombre_producto_${cont}" >${element.nombre_producto}</p></span>
                <span class="description color_${cont}"><b>Color: </b>${element.color}</span>
              </div>
            </td>
            <td> <span class="unidad_medida_${cont}">${element.unidad_medida}</span> <input class="unidad_medida_${cont}" type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${element.unidad_medida}"> <input class="color_${cont}" type="hidden" name="nombre_color[]" id="nombre_color[]" value="${element.color}"></td>
            <td class="form-group"><input class="producto_${element.idproducto} producto_selecionado w-100px cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" value="${element.cantidad}" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="hidden"><input class="w-135px input-no-border precio_sin_igv_${cont}" type="number" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${element.precio_sin_igv}" readonly ></td>
            <td class="hidden"><input class="w-135px input-no-border precio_igv_${cont}" type="number"  name="precio_igv[]" id="precio_igv[]" value="${element.igv}" readonly ></td>
            <td class="form-group"><input type="number" class="w-135px precio_con_igv_${cont} form-control" type="number"  name="precio_con_igv[]" id="precio_con_igv[]" value="${parseFloat(element.precio_con_igv).toFixed(2)}" min="0.01" required onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
            <td><input type="number" class="w-135px descuento_${cont}" name="descuento[]" value="${element.descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">0.00</span></td>
            <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm"><i class="fas fa-sync"></i></button></td>
          </tr>`;

          detalles = detalles + 1;

          $("#detalles").append(fila);

          array_class_trabajador.push({ id_cont: cont });

          cont++;
          evaluar();
        });

        modificarSubtotales();
      } else {  
        toastr_error("Sin productos!!","Este registro no tiene productos para mostrar", 700);     
      }

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

//mostramos el detalle del comprobante de la compras
function ver_detalle_compras(idcompra_proyecto) {

  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');

  $("#modal-ver-compras").modal("show");

  $.post("../ajax/compra_grano.php?op=ver_detalle_compras&id_compra=" + idcompra_proyecto, function (r) {
    r = JSON.parse(r);
    if (r.status == true) {
      $(".detalle_de_compra").html(r.data); 
      $("#cargando-5-fomulario").show();
      $("#cargando-6-fomulario").hide();

      $("#print_pdf_compra").removeClass('disabled');
      $("#print_pdf_compra").attr('href', `../reportes/pdf_compra_activos_fijos.php?id=${idcompra_proyecto}&op=insumo` );
      $("#excel_compra").removeClass('disabled');
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E   C O M P R A  ::::::::::::::::::::::::::



// :::::::::::::::::::::::::: - S E C C I O N   D E S C A R G A S -  ::::::::::::::::::::::::::



// :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
//Función limpiar
function limpiar_form_proveedor() {
  $("#idproveedor_prov").val("");
  $("#tipo_documento_prov option[value='RUC']").attr("selected", true);
  $("#nombre_prov").val("");
  $("#num_documento_prov").val("");
  $("#direccion_prov").val("");
  $("#telefono_prov").val("");
  $("#c_bancaria_prov").val("");
  $("#cci_prov").val("");
  $("#c_detracciones_prov").val("");
  $("#banco_prov").val("").trigger("change");
  $("#titular_cuenta_prov").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();

  $(".tooltip").removeClass("show").addClass("hidde");
}

// damos formato a: Cta, CCI
function formato_banco() {

  if ($("#banco_prov").select2("val") == null || $("#banco_prov").select2("val") == "" || $("#banco_prov").select2("val") == "1" ) {

    $("#c_bancaria_prov").prop("readonly", true);
    $("#cci_prov").prop("readonly", true);
    $("#c_detracciones_prov").prop("readonly", true);

  } else {
    
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-3").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');    

    $.post("../ajax/ajax_general.php?op=formato_banco", { 'idbanco': $("#banco_prov").select2("val") }, function (e, status) {
      
      e = JSON.parse(e);  // console.log(e);

      if (e.status == true) {
        $(".chargue-format-1").html("Cuenta Bancaria");
        $(".chargue-format-2").html("CCI");
        $(".chargue-format-3").html("Cuenta Detracciones");

        $("#c_bancaria_prov").prop("readonly", false);
        $("#cci_prov").prop("readonly", false);
        $("#c_detracciones_prov").prop("readonly", false);

        var format_cta = decifrar_format_banco(e.data.formato_cta);
        var format_cci = decifrar_format_banco(e.data.formato_cci);
        var formato_detracciones = decifrar_format_banco(e.data.formato_detracciones);
        // console.log(format_cta, formato_detracciones);

        $("#c_bancaria_prov").inputmask(`${format_cta}`);
        $("#cci_prov").inputmask(`${format_cci}`);
        $("#c_detracciones_prov").inputmask(`${formato_detracciones}`);
      } else {
        ver_errores(e);
      }      
    }).fail( function(e) { ver_errores(e); } );
  }
}

function decifrar_format_banco(format) {

  var array_format =  format.split("-"); var format_final = "";

  array_format.forEach((item, index)=>{

    for (let index = 0; index < parseInt(item); index++) { format_final = format_final.concat("9"); }   

    if (parseInt(item) != 0) { format_final = format_final.concat("-"); }
  });

  var ultima_letra = format_final.slice(-1);
   
  if (ultima_letra == "-") { format_final = format_final.slice(0, (format_final.length-1)); }

  return format_final;
}

//guardar proveedor
function guardar_proveedor(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);

  $.ajax({
    url: "../ajax/compra_grano.php?op=guardar_proveedor",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);
      try {
        if (e.status == true) {
          // toastr.success("proveedor registrado correctamente");
          Swal.fire("Correcto!", "Proveedor guardado correctamente.", "success");          
          limpiar_form_proveedor();
          $("#modal-agregar-proveedor").modal("hide");
          //Cargamos los items al select cliente
          lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', e.data);
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }       
      
      $("#guardar_registro_proveedor").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_proveedor").css({"width": percentComplete+'%'});
          $("#barra_progress_proveedor").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_proveedor").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_proveedor").css({ width: "0%",  });
      $("#barra_progress_proveedor").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_proveedor").css({ width: "0%", });
      $("#barra_progress_proveedor").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_para_editar_proveedor() {
  $("#cargando-11-fomulario").hide();
  $("#cargando-12-fomulario").show();

  $('#modal-agregar-proveedor').modal('show');
  $(".tooltip").remove();

  $.post("../ajax/compra_grano.php?op=mostrar_editar_proveedor", { 'idproveedor': $('#idproveedor').select2("val") }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {     
      $("#idproveedor_prov").val(e.data.idproveedor);
      $("#tipo_documento_prov option[value='" + e.data.tipo_documento + "']").attr("selected", true);
      $("#nombre_prov").val(e.data.razon_social);
      $("#num_documento_prov").val(e.data.ruc);
      $("#direccion_prov").val(e.data.direccion);
      $("#telefono_prov").val(e.data.telefono);
      $("#banco_prov").val(e.data.idbancos).trigger("change");
      $("#c_bancaria_prov").val(e.data.cuenta_bancaria);
      $("#cci_prov").val(e.data.cci);
      $("#c_detracciones_prov").val(e.data.cuenta_detracciones);
      $("#titular_cuenta_prov").val(e.data.titular_cuenta);      

      $("#cargando-11-fomulario").show();
      $("#cargando-12-fomulario").hide();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); });
}

function extrae_ruc() {
  if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { 
    $('.btn-editar-proveedor').addClass('disabled').attr('data-original-title','Seleciona un proveedor');
  } else { 
    if ($('#idproveedor').select2("val") == 1) {
      $('.btn-editar-proveedor').addClass('disabled').attr('data-original-title','No editable');      
    } else{
      var name_proveedor = $('#idproveedor').select2('data')[0].text;
      $('.btn-editar-proveedor').removeClass('disabled').attr('data-original-title',`Editar: ${recorte_text(name_proveedor, 15)}`);      
    }
  }
  $('[data-toggle="tooltip"]').tooltip();
}

// :::::::::::::::::::::::::: S E C C I O N   P A G O   C O M P R A S  ::::::::::::::::::::::::::



// :::::::::::::::::::::::::: S E C C I O N   M A T E R I A L E S  ::::::::::::::::::::::::::


init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..
$(function () {

  // Aplicando la validacion del select cada vez que cambie
  // $("#idproveedor").on('change', function() { $(this).trigger('blur'); });
  // $("#tipo_comprobante").on('change', function() { $(this).trigger('blur'); });
  // $("#forma_pago").on('change', function() { $(this).trigger('blur'); });
  // $("#tipo_pago").on('change', function() { $(this).trigger('blur'); });
  // $("#banco_prov").on('change', function() { $(this).trigger('blur'); });

  $("#form-compras").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idproveedor:        { required: true },
      tipo_comprobante:   { required: true },
      serie_comprobante:  { minlength: 2 },
      descripcion:        { minlength: 4 },
      fecha_compra:       { required: true },
      glosa:              { required: true },
      val_igv:            { required: true, number: true, min:0, max:1 },
    },
    messages: {
      idproveedor:        { required: "Campo requerido", },
      tipo_comprobante:   { required: "Campo requerido", },
      serie_comprobante:  { minlength: "Minimo 2 caracteres", },
      descripcion:        { minlength: "Minimo 4 caracteres", },
      fecha_compra:       { required: "Campo requerido", },
      glosa:              { required: "Campo requerido", },
      val_igv:            { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
      'peso_bruto[]':     { min: "Mínimo 0.01", required: "Campo requerido"},
      'dcto_humendad[]':  { min: "Mínimo 0.00", required: "Campo requerido"},
      'porcentaje_cascara[]':{ min: "Mínimo 0.00", required: "Campo requerido"},
      'dcto_embase[]':    { min: "Mínimo 0.00", required: "Campo requerido"},
      'cantidad[]':       { min: "Mínimo 0.01", required: "Campo requerido"},
      'precio_con_igv[]': { min: "Mínimo 0.01", required: "Campo requerido"},
      'descuento[]':      { min: "Mínimo 0.00", required: "Campo requerido"}
    },

    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");
    },

    submitHandler: function (form) {
      guardar_y_editar_compras(form);
    },
  });  

  $("#form-proveedor").validate({
    rules: {
      tipo_documento_prov:  { required: true },
      num_documento_prov:   { required: true, minlength: 6, maxlength: 20 },
      nombre_prov:          { required: true, minlength: 3, maxlength: 100 },
      direccion_prov:       { minlength: 5, maxlength: 150 },
      telefono_prov:        { minlength: 8 },
      c_bancaria_prov:      { minlength: 6,  },
      cci_prov:             { minlength: 6,  },
      c_detracciones_prov:  { minlength: 6,  },      
      banco_prov:           { required: true },
      titular_cuenta_prov:  { minlength: 4 },
    },
    messages: {
      tipo_documento_prov:  { required: "Campo requerido.", },
      num_documento_prov:   { required: "Campo requerido.",  minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre_prov:          { required: "Campo requerido.", minlength: "MÍNIMO 3 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      direccion_prov:       { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 150 caracteres.", },
      telefono_prov:        { minlength: "MÍNIMO 9 caracteres.", },
      c_bancaria_prov:      { minlength: "MÍNIMO 6 caracteres.", },
      cci_prov:             { minlength: "MÍNIMO 6 caracteres.",  },
      c_detracciones_prov:  { minlength: "MÍNIMO 6 caracteres.", },      
      banco_prov:           { required: "Campo requerido.",  },
      titular_cuenta_prov:  { minlength: 'MÍNIMO 4 caracteres.' },
    },

    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");

      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");
    },

    submitHandler: function (e) {
      guardar_proveedor(e);
    },
  });

  $("#form-pago-compra").validate({
    rules: {
      forma_pago:         { required: true },
      tipo_pago:          { required: true },
      banco_pago:         { required: true },
      fecha_pago:         { required: true },
      monto_pago:         { required: true },
      numero_op_pago:     { minlength: 3 },
      descripcion_pago:   { minlength: 3 },
      titular_cuenta_pago:{ minlength: 3 },
    },
    messages: {
      forma_pago:         { required: "Campo requerido.", },
      tipo_pago:          { required: "Campo requerido.", },
      banco_pago:         { required: "Campo requerido.", },
      fecha_pago:         { required: "Campo requerido.", },
      monto_pago:         { required: "Campo requerido.", },
      numero_op_pago:     { minlength: 'MÍNIMO 3 caracteres.' },
      descripcion_pago:   { minlength: 'MÍNIMO 3 caracteres.' },
      titular_cuenta_pago:{ minlength: 'MÍNIMO 3 caracteres.' },
    },

    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");
    },

    submitHandler: function (e) {
      guardaryeditar_pago(e);
    },
  });  

  // $("#idproveedor").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  // $("#banco_pago").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  // $("#tipo_comprobante").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  // $("#forma_pago").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  // $("#tipo_pago").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  // $("#banco_prov").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});


// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function cargando_search() {
  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
}

function filtros() {  

  var fecha_1       = $("#filtro_fecha_inicio").val();
  var fecha_2       = $("#filtro_fecha_fin").val();  
  var id_proveedor  = $("#filtro_proveedor").select2('val');
  var comprobante   = $("#filtro_tipo_comprobante").select2('val');   
  
  var nombre_proveedor = $('#filtro_proveedor').find(':selected').text();
  var nombre_comprobante = ' ─ ' + $('#filtro_tipo_comprobante').find(':selected').text();

  // filtro de fechas
  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; } else{ fecha_1 = format_a_m_d(fecha_1) == '-'? '': format_a_m_d(fecha_1);}
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; } else{ fecha_2 = format_a_m_d(fecha_2) == '-'? '': format_a_m_d(fecha_2);} 

  // filtro de proveedor
  if (id_proveedor == '' || id_proveedor == 0 || id_proveedor == null) { id_proveedor = ""; nombre_proveedor = ""; }

  // filtro de trabajdor
  if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; nombre_comprobante = "" }

  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_proveedor} ${nombre_comprobante}...`);
  //console.log(fecha_1, fecha_2, id_proveedor, comprobante);

  tbla_principal(fecha_1, fecha_2, id_proveedor, comprobante);
}


//validando excedentes
function validando_excedentes() {
  var totattotal = quitar_formato_miles(localStorage.getItem("monto_total_p"));
  var monto_total_dep = quitar_formato_miles(localStorage.getItem("monto_total_dep"));
  var monto_entrada = $("#monto_pago").val();
  var total_suma = parseFloat(monto_total_dep) + parseFloat(monto_entrada);
  var debe = parseFloat(totattotal) - monto_total_dep;

  //console.log(typeof total_suma);

  if (total_suma > totattotal) {
    toastr_error("Exedente!!",`Monto excedido al total del monto a pagar!`, 700);
  } else {
    toastr_success("Aceptado!!",`Monto Aceptado.`, 700);
  }
}

// ver imagen grande del producto agregado a la compra
function ver_img_material(img, nombre) {
  $("#ver_img_material").attr("src", `${img}`);
  $(".nombre-img-material").html(nombre);
  $("#modal-ver-img-material").modal("show");
}

function export_excel_detalle_factura() {
  $tabla = document.querySelector("#tabla_detalle_factura");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "Detalle comprobante", //Nombre del archivo de Excel
    sheetname: "detalle factura", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.tabla_detalle_factura.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

}

//Función para guardar o editar - COMPRAS
function guardar_y_editar_compras____________plantilla_cargando_POST(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-compras")[0]);

  var swal2_header = `<img class="swal2-image bg-color-252e38 b-radio-7px p-15px m-10px" src="../dist/gif/cargando.gif">`;

  var swal2_content = `<div class="row sweet_loader" >    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
      <div class="progress" id="barra_progress_compra_div">
        <div id="barra_progress_compra" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
          0%
        </div>
      </div>
    </div>
  </div>`;

  Swal.fire({
    title: "¿Está seguro que deseas guardar esta compra?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../ajax/compra_grano.php?op=guardaryeditarcompra",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function() {
          Swal.fire({
            title: "Guardando...",
            html: 'Tu <b>información</b> se esta guradando en la <b>base de datos</b>.',
            showConfirmButton: false,
            didRender: function() { 
              /* solo habrá un swal2 abierta.*/               
              $('.swal2-header').prepend(swal2_header); 
              $('.swal2-content').prepend(swal2_content);
            }
          });
          $("#barra_progress_compra").addClass('progress-bar-striped progress-bar-animated');
        },
        success: function (e) {
          try {
            e = JSON.parse(e);
            if (e.status == true ) {
              // toastr.success("Usuario registrado correctamente");
              Swal.fire("Correcto!", "Compra guardada correctamente", "success");

              tabla_compra_insumo.ajax.reload(null, false);
              tabla_compra_x_proveedor.ajax.reload(null, false);

              limpiar_form_compra(); show_hide_form();
              
              $("#modal-agregar-usuario").modal("hide");
              l_m();
              
            } else {
              // toastr.error(datos);
              Swal.fire("Error!", datos, "error");
              l_m();
            }
          } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); } 

        },
        xhr: function () {
          var xhr = new window.XMLHttpRequest();    
          xhr.upload.addEventListener("progress", function (evt) {    
            if (evt.lengthComputable) {    
              var percentComplete = (evt.loaded / evt.total)*100;
              /*console.log(percentComplete + '%');*/
              $("#barra_progress_compra").css({"width": percentComplete+'%'});    
              $("#barra_progress_compra").text(percentComplete.toFixed(2)+" %");
            }
          }, false);
          return xhr;
        }
      });
    }
  });  
}
