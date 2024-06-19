$(document).ready(function () {
  var title = $("#page-title").text();
  $("ul.nav-pills li a.tabMenu").each(function () {
    var hrefValue = $(this).attr("href").replace("#", "");
    var tabText = $(this).text();
    var tabTitle = $(this).data("title")


    if (title == tabText || title == tabTitle) {
      $(hrefValue).tab("show");
    }
  });
});

$("#returnlist").click(function () {
  var pageTitle = $("#returnlist").data("title");
  $("#liste").tab("show");
  $("#page-title").text(pageTitle);
});

$("#liste").on("click", function () {
  $("#save").addClass("d-none");
});

$("#yeni").on("click", function () {
  $("#save").removeClass("d-none");
});

$(function () {
  $(".tabMenu").click(function () {
    var navLinkText = $(this).attr("data-title");
    $("#page-title").text(navLinkText);
    setActiveMenubyTabMenu(this);
  });
});

$(document).ready(function () {
  $(".select2").select2();


  moment().locale('tr');
  $("[data-mask]").inputmask("dd.mm.yyyy");
  $("#startdate,#enddate").datetimepicker({
    format: "DD.MM.YYYY",
    locale: moment.locale('tr'),
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




$(function () {
  $('.check').bootstrapToggle({
    onstyle: "primary",
    size: "xs",
    toogle: "toogle",

  });
})




$("#companies").on("change", function () {
  var company_id = $("#companies option:selected").val();
  $.ajax({
    url: "ajax.php",
    type: "POST",
    data: {
      "company_id": company_id,
      "action": "proje"
    },
    success: function (data) {
      $('#projects').html(data);
    }
  })
})
