
$("#returnlist").click(function () {
    var pageTitle = $("#returnlist").data("title");
    $("#liste").tab("show");
    $("#page-title").text(pageTitle);
  });
  
  $(document).ready(function () {
    $(".select2").select2();
    //Initialize Select2 Elements
    $(".select2bs4").select2({
      theme: "bootstrap4",
    });
  
    $("[data-mask]").inputmask("dd.mm.yyyy");
    $("#startdate,#enddate").datetimepicker({
      format: "DD.MM.YYYY",
      locale: "tr",
    });
  });
  
  $("#city").on("change", function () {
    var il_id = $(this).val();
  
    $.ajax({
      url: "ajax.php",
      type: "POST",
      data: {
        il_id: il_id,
        action: "ilce",
      },
      success: function (data) {
        $("#town").html(data);
      },
    });
  });
  
  $('[data-mask]').inputmask('dd.mm.yyyy')
  $('#startdate,#enddate').datetimepicker({
      format: 'DD.MM.YYYY',
      locale: 'tr'

  });