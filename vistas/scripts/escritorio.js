var char_linea_subcontrato;

function init() {

  $('#mEscritorio').addClass("active");

  tablero();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════

  
  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════

  $("#valorizacion_filtro").select2({ theme: "bootstrap4", placeholder: "Filtro valorizacion", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

function tablero() {   

  $.post("../ajax/escritorio.php?op=tablero",  function (e, status) {

    e = JSON.parse(e);  //console.log(e);

    if (e.status) {
      $("#cantidad_box_producto").html(formato_miles(e.data.cant_producto));
      $("#cantidad_box_agricultor").html(formato_miles(e.data.cant_agricultor));
      $("#cantidad_box_trabajador").html(formato_miles(e.data.cant_trabajador));
      $("#cantidad_box_venta").html(formato_miles(e.data.cant_venta_producto));
    } else {
      ver_errores(e);
    } 

  }).fail( function(e) { ver_errores(e); } );
}

init();

// :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  S U B C O N T R A T O  ::::::::::::::::::::::::
$(function () {
  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

  var $salesChart = $('#sales-chart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: [ 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      datasets: [
        {
          backgroundColor: '#28a745',
          borderColor: '#28a745',
          data: [700, 100, 600, 800, 1000, 2000, 3000, 5000, 2500, 9700, 2500, 3000],
          label: 'Subcontrato'
        },
        {
          backgroundColor: '#ced4da',
          borderColor: '#ced4da',
          data: [700, 800, 200, 1700, 2700, 2000, 1800, 1500, 2000, 600, 800, 1000,],
          label: 'Gastos'
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: true
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return '$' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })

  var $visitorsChart = $('#visitors-chart')
  // eslint-disable-next-line no-unused-vars
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels: [ 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      datasets: [
        {
          type: 'line',
          data: [700, 100, 600, 800, 1000, 2000, 3000, 5000, 2500, 9700, 2500, 3000],
          backgroundColor: 'transparent',
          borderColor: '#28a745',
          pointBorderColor: '#28a745',
          pointBackgroundColor: '#28a745',
          fill: false,
          label: 'Subcontrato'
          // pointHoverBackgroundColor: '#28a745',
          // pointHoverBorderColor    : '#28a745'
        },
        {
          type: 'line',
          data: [700, 800, 200, 1700, 2700, 2000, 1800, 1500, 2000, 600, 800, 1000,],
          backgroundColor: 'tansparent',
          borderColor: '#ced4da',
          pointBorderColor: '#ced4da',
          pointBackgroundColor: '#ced4da',
          fill: false,
          label: 'Utilidad'
          // pointHoverBackgroundColor: '#ced4da',
          // pointHoverBorderColor    : '#ced4da'
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: true
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
})