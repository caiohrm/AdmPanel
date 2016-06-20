/**
 * Created by caio- on 15/06/2016.
 */
$(document).ready(function() {
  $(".data-table").dataTable({
      "oLanguage":{
          "sInfo":"Mostrando _START_ a _END_ de _TOTAL_ registros"
      },
      "sScrollY":"400px",
    //  "sScrollX":"100%",
      "sScrollXinner":"100%",
      "aaSorting":[[0,"asc"]],
      "bFilter": false,
      "paging":   false,
  })
});