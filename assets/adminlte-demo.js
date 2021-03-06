require('../vendor/kevinpapst/adminlte-bundle/Resources/assets/admin-lte');
require('admin-lte/dist/css/AdminLTE.min.css');
require('./css/app.scss');
require('bootstrap-sweetalert/dist/sweetalert.css');
//require('./css/datatables.css');
require('./css/bootstrap-editable.css');
require('../public/style.css');
require('@fortawesome/fontawesome-free');
global.$.AdminLTE={};
global.$.AdminLTE.options={};
require('admin-lte/dist/js/adminlte.min');
require('./js/bootstrap-editable.min');
require('bootstrap-sweetalert/dist/sweetalert');
require('signature_pad/dist/signature_pad.m')
/*require('datatables.net')();
require('datatables.net-bs')();*/
//require('bootstrap')();
require('../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.js');
const routes = require('../public/js/fos_js_routes.json');
Routing.setRoutingData(routes);
// ------ for charts ------
const Chart = require('chart.js/dist/Chart.min');
global.Chart = Chart;

$(document).ready(
    function ()
    {
        $('[data-datepickerenable="on"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: "YYYY-MM-DD",
                firstDay: 1
            }
        });
        $('jqtable_contrat').hide();
    }
);
