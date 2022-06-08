/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

//Importation du css minifié bootstrap
import "bootstrap/dist/css/bootstrap.min.css";

import "datatables.net-bs5";
import "datatables.net-responsive-bs5";

//Importation de jquery
import $ from "jquery";

$(document).ready(function () {
    $("#datatable").DataTable({
      responsive: true,
      paging: true,
      filter: false,
      lengthMenu: [5, 10],
    });
    $("#datadash").DataTable({
      responsive: true,
      paging: true,
      filter: false,
      lengthMenu: [5, 10],
    });
  });