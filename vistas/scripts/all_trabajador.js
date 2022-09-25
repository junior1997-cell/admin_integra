var tabla; var tabla2;
var cant_banco_multimple = 1;
//Función que se ejecuta al inicio
function init() {

  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");

  $("#mRecurso").addClass("active");

  $("#lAllTrabajador").addClass("active");

  tbla_principal();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2_cargo_trabajador", '#cargo_trabajador', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) {  $("#submit-form-trabajador").submit(); });  

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#cargo_trabajador").select2({ theme: "bootstrap4",  placeholder: "Selecione Cargo", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

init();

function formatState (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/banco/logo/${state.title}`: '../dist/docs/banco/logo/logo-sin-banco.svg'; 
  var onerror = `onerror="this.src='../dist/docs/banco/logo/logo-sin-banco.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

// abrimos el navegador de archivos
$("#foto1_i").click(function() { $('#foto1').trigger('click'); });
$("#foto1").change(function(e) { addImage(e,$("#foto1").attr("id")) });

function foto1_eliminar() {

	$("#foto1").val("");

	$("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");

	$("#foto1_nombre").html("");
}

//Función limpiar
function limpiar_form_trabajador() {
  cant_banco_multimple = 1;
  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');

  $("#idtrabajador").val("");
  $("#tipo_documento option[value='DNI']").attr("selected", true);
  $("#nombre").val(""); 
  $("#num_documento").val(""); 
  $("#direccion").val(""); 
  $("#telefono").val(""); 
  $("#email").val(""); 
  $("#nacimiento").val("");
  $("#edad").val("0");  $("#p_edad").html("0");    
  $("#cta_bancaria").val("");  
  $("#cci").val("");  
  $("#banco_0").val("").trigger("change"); $("#lista_bancos").html("");

  $("#tipo").val("").trigger("change");
  $("#ocupacion").val("").trigger("change");
  $("#titular_cuenta").val("");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1").val("");
	$("#foto1_actual").val("");  
  $("#foto1_nombre").html(""); 
  
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function tbla_principal() {

  tabla=$('#tabla-trabajador').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,9,10,11,3,4,12,13,14,15,16,5,], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,9,10,11,3,4,12,13,14,15,16,5,], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,9,10,11,3,4,12,13,14,15,16,5,], } }, {extend: "colvis"} ,
    ],
    ajax:{
      url: '../ajax/all_trabajador.php?op=tbla_principal',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);  ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); } 
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [8, 9, 10, 11, 12, 13, 14, 15, 16], visible: false, searchable: false, }, 
    ],
  }).DataTable();

}

//Función para guardar o editar
function guardar_y_editar_trabajador(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-trabajador")[0]);

  $.ajax({
    url: "../ajax/all_trabajador.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  //console.log(e); 
        if (e.status == true) {	
          Swal.fire("Correcto!", "Trabajador guardado correctamente", "success");
          tabla.ajax.reload(null, false);          
          limpiar_form_trabajador();
          $("#modal-agregar-trabajador").modal("hide"); 
          cant_banco_multimple = 1; 
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});
          $("#barra_progress").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%",  });
      $("#barra_progress").text("0%");
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// ver detallles del registro
function verdatos(idtrabajador){

  $(".tooltip").removeClass("show").addClass("hidde");

  $('#datostrabajador').html(''+
  '<div class="row" >'+
    '<div class="col-lg-12 text-center">'+
      '<i class="fas fa-spinner fa-pulse fa-6x"></i><br />'+
      '<br />'+
      '<h4>Cargando...</h4>'+
    '</div>'+
  '</div>');

  var verdatos=''; 

  var imagen_perfil =''; btn_imagen_perfil=''; 

  var imagen_dni_anverso =''; var btn_imagen_dni_anverso=''; 
  var imagen_dni_reverso =''; var btn_imagen_dni_reverso=''; 
  
  var cv_documentado=''; var btn_cv_documentado=''; 
  var cv_no_documentado ='';  var btn_cv_no_documentado='';

  $("#modal-ver-trabajador").modal("show")

  $.post("../ajax/all_trabajador.php?op=verdatos", { idtrabajador: idtrabajador }, function (e, status) {

    e = JSON.parse(e);  //console.log(e); 
    
    if (e.status == true) {
      
    
      if (e.data.trabajador.imagen_perfil != '') {

        imagen_perfil=`<img src="../dist/docs/all_trabajador/perfil/${e.data.trabajador.imagen_perfil}" alt="" class="img-thumbnail w-130px">`
        
        btn_imagen_perfil=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/perfil/${e.data.trabajador.imagen_perfil}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/perfil/${e.data.trabajador.imagen_perfil}" download="PERFIL ${e.data.trabajador.nombres}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {
        imagen_perfil='No hay imagen';
        btn_imagen_perfil='';
      }

      if (e.data.trabajador.imagen_dni_anverso != '') {

        imagen_dni_anverso=`<img src="../dist/docs/all_trabajador/dni_anverso/${e.data.trabajador.imagen_dni_anverso}" alt="" class="img-thumbnail">`
        
        btn_imagen_dni_anverso=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/dni_anverso/${e.data.trabajador.imagen_dni_anverso}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/dni_anverso/${e.data.trabajador.imagen_dni_anverso}" download="DNI ${e.data.trabajador.nombres}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {
        imagen_dni_anverso='No hay imagen';
        btn_imagen_dni_anverso='';
      }

      if (e.data.trabajador.imagen_dni_reverso != '') {

        imagen_dni_reverso=`<img src="../dist/docs/all_trabajador/dni_reverso/${e.data.trabajador.imagen_dni_reverso}" alt="" class="img-thumbnail">`
        
        btn_imagen_dni_reverso=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/dni_reverso/${e.data.trabajador.imagen_dni_reverso}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/dni_reverso/${e.data.trabajador.imagen_dni_reverso}" download="DNI - ${e.data.trabajador.nombres}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {
        imagen_dni_reverso='No hay imagen';
        btn_imagen_dni_reverso='';
      }

      if (e.data.trabajador.cv_documentado != '') {
        
        cv_documentado=doc_view_extencion(e.data.trabajador.cv_documentado, 'all_trabajador', 'cv_documentado', '100%');
        
        btn_cv_documentado=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/cv_documentado/${e.data.trabajador.cv_documentado}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/cv_documentado/${e.data.trabajador.cv_documentado}" download="CV DOCUMENTADO - ${e.data.trabajador.nombres}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {
        cv_documentado='Sin CV documentado';
        btn_cv_documentado='';
      }

      if (e.data.trabajador.cv_no_documentado != '') {
        
        cv_no_documentado=  doc_view_extencion(e.data.trabajador.cv_no_documentado, 'all_trabajador', 'cv_no_documentado', '100%');
        
        btn_cv_no_documentado=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/cv_no_documentado/${e.data.trabajador.cv_no_documentado}"> <i class="fas fa-expand"></i> </a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/cv_no_documentado/${e.data.trabajador.cv_no_documentado}" download="CV NO DOCUMENTADO - ${e.data.trabajador.nombres}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {
        cv_no_documentado='Sin CV no documentado';
        btn_cv_no_documentado='';
      }

      var banco ="";

      e.data.bancos.forEach((valor, index) => {
        banco = banco.concat( `<tr data-widget="expandable-table" aria-expanded="false">
          <th>Banco <br> Cta. <br> CCI </th>
          <td> 
            <div class="user-block">
              ${(valor.banco_seleccionado == 1? '<img class="img-circle" src="../dist/svg/check-mark.svg" >': '<img class="img-circle" src="../dist/svg/close-mark.svg" >' )}
              <span class="username">${valor.banco}</span>
              <span class="description">${valor.cuenta_bancaria}</span>    
              <span class="description">${valor.cci}</span>              
            </div>
          </td>
        </tr>`);
      });

      verdatos=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th rowspan="2" class="text-center">${imagen_perfil}<br>${btn_imagen_perfil} </th>
                  <td> <b>Nombre: </b>${e.data.trabajador.nombres}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <td> <b>DNI: </b>${e.data.trabajador.numero_documento}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Dirección</th>
                  <td>${e.data.trabajador.direccion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Correo</th>
                  <td>${e.data.trabajador.email}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Teléfono</th>
                  <td>${e.data.trabajador.telefono}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha Nac.</th>
                  <td>${e.data.trabajador.fecha_nacimiento}</td>
                </tr>
                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Titular cuenta </th>
                  <td>${e.data.trabajador.titular_cuenta}</td>
                </tr>
                ${banco}
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>DNI anverso</th>
                  <td> ${imagen_dni_anverso} <br>${btn_imagen_dni_anverso}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>DNI reverso</th>
                  <td> ${imagen_dni_reverso}<br>${btn_imagen_dni_reverso}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>CV Doc.</th>
                  <td> ${cv_documentado} <br>${btn_cv_documentado}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>CV no Doc.</th>
                  <td> ${cv_no_documentado} <br>${btn_cv_no_documentado}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $("#datostrabajador").html(verdatos);

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

// mostramos los datos para editar
function mostrar(idtrabajador) {
  $(".tooltip").removeClass("show").addClass("hidde");
  limpiar_form_trabajador();  

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-trabajador").modal("show")

  $.post("../ajax/all_trabajador.php?op=mostrar", { idtrabajador: idtrabajador }, function (e, status) {

    e = JSON.parse(e);  console.log(e);   

    if (e.status == true) {       

      $("#tipo_documento option[value='"+e.data.trabajador.tipo_documento+"']").attr("selected", true);
      $("#nombre").val(e.data.trabajador.nombres);
      $("#num_documento").val(e.data.trabajador.numero_documento);
      $("#direccion").val(e.data.trabajador.direccion);
      $("#telefono").val(e.data.trabajador.telefono);
      $("#email").val(e.data.trabajador.email);
      $("#nacimiento").val(e.data.trabajador.fecha_nacimiento);      
      $("#tipo").val(e.data.trabajador.idtipo_trabajador).trigger("change");
      $("#ocupacion").val(e.data.trabajador.idocupacion).trigger("change");
      $("#titular_cuenta").val(e.data.trabajador.titular_cuenta);
      $("#idtrabajador").val(e.data.trabajador.idtrabajador);
      $("#ruc").val(e.data.trabajador.ruc);         
            
      if (e.data.trabajador.imagen_perfil!="") {
        $("#foto1_i").attr("src", "../dist/docs/all_trabajador/perfil/" + e.data.trabajador.imagen_perfil);
        $("#foto1_actual").val(e.data.trabajador.imagen_perfil);
      }
      calcular_edad('#nacimiento','#p_edad','#edad'); 

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function desactivar(idtrabajador) {

  Swal.fire({
    icon: "warning",
    title: 'Antes de expulsar ingrese una descripción',
    input: 'text',
    inputAttributes: {
      autocapitalize: 'off'
    },
    showCancelButton: true,
    cancelButtonColor: "#d33",
    confirmButtonText: 'Si, expulsar!',
    confirmButtonColor: "#28a745",
    showLoaderOnConfirm: true,
    preConfirm: (login) => {
      // console.log(login);
      return fetch(`../ajax/all_trabajador.php?op=desactivar&idtrabajador=${idtrabajador}&descripcion=${login}`)
        .then(response => {
          console.log(response);
          if (!response.ok) {
            throw new Error(response.statusText)
          }
          return response.json()
        })
        .catch(error => {
          Swal.showValidationMessage(
            `Request failed: ${error}`
          )
        })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    console.log(result );
    if (result.isConfirmed) {
      if (result.value.ok) {
        Swal.fire("Expulsado!", "Tu trabajador ha sido expulsado.", "success");
        tabla.ajax.reload(null, false); tabla2.ajax.reload(null, false);
      }else{
        Swal.fire("Error!", "No se pudo realizar la petición.", "error");
      }     
    }
  })

  // Swal.fire({
  //   title: "¿Está Seguro de  Desactivar  el trabajador?",
  //   text: "",
  //   icon: "warning",
  //   showCancelButton: true,
  //   confirmButtonColor: "#28a745",
  //   cancelButtonColor: "#d33",
  //   confirmButtonText: "Si, desactivar!",
  // }).then((result) => {
  //   if (result.isConfirmed) {
  //     $.post("../ajax/all_trabajador.php?op=desactivar", { idtrabajador: idtrabajador }, function (e) {

  //       Swal.fire("Desactivado!", "Tu trabajador ha sido desactivado.", "success");
    
  //       tabla.ajax.reload(null, false); tabla2.ajax.reload(null, false);
  //     });      
  //   }
  // });   
}

//Función para activar registros
function activar(idtrabajador, nombre) {
  $(".tooltip").removeClass("show").addClass("hidde");
  Swal.fire({
    title: "¿Está Seguro de  Activar  el trabajador?",
    html: `<b class="text-success">${nombre}</b> <br> Este trabajador tendra acceso al sistema`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
    showLoaderOnConfirm: true,
    preConfirm: (input) => {       
      return fetch(`../ajax/all_trabajador.php?op=activar&idtrabajador=${idtrabajador}`).then(response => {
        console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    if (result.isConfirmed) {
      if (result.value.status) {
        Swal.fire("Activado!", "Tu trabajador ha sido activado.", "success");
        tabla.ajax.reload(null, false); tabla2.ajax.reload(null, false);
      }else{
        ver_errores(result.value);
      }     
    }    
  });      
}

//Función para desactivar registros
function eliminar(idtrabajador, nombre) {
  $(".tooltip").removeClass("show").addClass("hidde");
  Swal.fire({
    title: "!Elija una opción¡",
    html: `<b class="text-danger"><del>${nombre}</del></b> <br> Al <b>Expulsar</b> Padrá encontrar el registro en la tabla inferior! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`,
    icon: "warning",
    showCancelButton: true,
    showDenyButton: true,
    confirmButtonColor: "#17a2b8",
    denyButtonColor: "#d33",
    cancelButtonColor: "#6c757d",    
    confirmButtonText: `<i class="fas fa-times"></i> Expulsar`,
    denyButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,    
    showLoaderOnDeny: true,
    preDeny: (input) => {       
      return fetch(`../ajax/all_trabajador.php?op=eliminar&idtrabajador=${idtrabajador}`).then(response => {
        console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    console.log(result );
    if (result.isConfirmed) {    
      Swal.fire({
        icon: "warning",
        title: 'Antes de expulsar ingrese una descripción',
        input: 'text',
        inputAttributes: { autocapitalize: 'off' },
        showCancelButton: true,
        cancelButtonColor: "#d33",
        confirmButtonText: 'Si, expulsar!',
        confirmButtonColor: "#28a745",
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          // console.log(login);
          return fetch(`../ajax/all_trabajador.php?op=desactivar&idtrabajador=${idtrabajador}&descripcion=${login}`).then(response => {
            console.log(response);
            if (!response.ok) { throw new Error(response.statusText); }
            return response.json();
          }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); });
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        console.log(result );
        if (result.isConfirmed) {
          if (result.value.status) {
            Swal.fire("Expulsado!", "Tu trabajador ha sido expulsado.", "success");
            tabla.ajax.reload(null, false); tabla2.ajax.reload(null, false);
          }else{
            ver_errores(result.value);
          }     
        }
      });

    }else if (result.isDenied) {
      //op=eliminar
      if (result.value.status) {
        Swal.fire("Eliminado!", "Tu trabajador ha sido Eliminado.", "success");
        tabla.ajax.reload(null, false); tabla2.ajax.reload(null, false);
      }else{
        ver_errores(result.value);
      }      
    }
  });
}

// .....::::::::::::::::::::::::::::::::::::: B A N C O  :::::::::::::::::::::::::::::::::::::::..


/* =========================== S E C C I O N   R E C U P E R A R   B A N C O S =========================== */

function recuperar_banco() {
  
  $.post("../ajax/all_trabajador.php?op=recuperar_banco", function (e, textStatus, jqXHR) {
    e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      toastr_success('oka', 'se realzo toda la transaccion', 700);
      tabla.ajax.reload(null, false); 
    } else {
      ver_errores(e);
    }
    $('#recuperar_banco').addClass('disabled');
  });
}

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {   

  $("#banco").on('change', function() { $(this).trigger('blur'); });
  $("#tipo").on('change', function() { $(this).trigger('blur'); });
  $("#ocupacion").on('change', function() { $(this).trigger('blur'); });

  $("#form-trabajador").validate({
    rules: {
      tipo_documento: { required: true },
      num_documento:  { required: true, minlength: 6, maxlength: 20 },
      nombre:         { required: true, minlength: 6, maxlength: 100 },
      email:          { email: true, minlength: 10, maxlength: 50 },
      direccion:      { minlength: 5, maxlength: 70 },
      telefono:       { minlength: 8 },
      tipo_trabajador:{ required: true},
      cargo:          { required: true},
      cta_bancaria:   { minlength: 10,},
      banco:          { required: true},
      tipo:           { required: true},
      ruc:            { minlength: 11, maxlength: 11},
    },
    messages: {
      tipo_documento: { required: "Campo requerido.", },
      num_documento:  { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre:         { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      email:          { required: "Campo requerido.", email: "Ingrese un coreo electronico válido.", minlength: "MÍNIMO 10 caracteres.", maxlength: "MÁXIMO 50 caracteres.", },
      direccion:      { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 70 caracteres.", },
      telefono:       { minlength: "MÍNIMO 8 caracteres.", },
      tipo_trabajador:{ required: "Campo requerido.", },
      cargo:          { required: "Campo requerido.", },
      cta_bancaria:   { minlength: "MÍNIMO 10 caracteres.", },
      tipo:           { required: "Campo requerido.", },
      banco:      { required: "Campo requerido.", },
      ruc:            { minlength: "MÍNIMO 11 caracteres.", maxlength: "MÁXIMO 11 caracteres.", },
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
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_y_editar_trabajador(e);
    },
  });

  $("#banco").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#ocupacion").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


