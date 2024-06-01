$("#company").on("change", function () {
  if ($(this).val() > 0) {
    $(".check").bootstrapToggle("enable");
  } else {
    $(".check").bootstrapToggle("off");
    $(".check").bootstrapToggle("disable");
  }
});

$("#donem-kapat").on("change", function (event) {
  var donemVal = $(this).is(":checked") ? 1 : 0;
  update_state(donemVal);
});

$("#bordro-view").on("change", function () {
  var view_val = $(this).is(":checked") ? 1 : 0;
  update_state(view_val, (action = "bordro-gorsun"));
});

function update_state(state_val, action = "puantaj-kapat") {
  var company_id = $("#company").val();
  var ay = $("#months").val();
  var yil = $("#year").val();
  // console.log(state_val);

  if (company_id != "" && ay != "" && yil != "") {
    $.ajax({
      url: "ajax.php",
      type: "POST",
      data: {
        company_id: company_id,
        yil: yil,
        ay: ay,
        state_val: state_val,
        action: action,
      },
      success: function (data) {},
    });
  }
}

//Firma Değiştiği zaman sayfayı yeniden yükle
$("#company").on("change", function () {
  Route();
});

$("#project").on("change", function () {
  Route();
});

//Yıl değiştiği zaman sayfayı yeniden yükle
$("#year").on("change", function () {
  Route();
});

//Ay değiştiği zaman sayfayı yeniden yükle
$("#months").on("change", function () {
  Route();
});

function Route() {
  var month = $("#months").val();
  var year = $("#year").val();
  var company_id = $("#company option:selected").val();
  var project_id = $("#project option:selected").val();

  //var year = $("#year").val();

  //Sayfayı yeniden yönlendirmek için
  RoutePagewithParams(
    "bordro/main",
    "months=" +
      month +
      "&year=" +
      year +
      "&company_id=" +
      company_id +
      "&project_id=" +
      project_id
  );
}

function maas_hesapla() {
  var company_id = $("#company").val();
  if (company_id == "") {
    alert("Listeden firma seçiniz");
  } else {
    var company_id = $("#company").val();
    var ay = $("#months").val();
    var yil = $("#year").val();
    // console.log(state_val);

    if (company_id != "" && ay != "" && yil != "") {
      $.ajax({
        url: "ajax.php",
        type: "POST",
        data: {
          company_id: company_id,
          yil: yil,
          ay: ay,
          action: "maas_hesapla",
        },
        success: function (data) {
          // console.log(data);
          var res = JSON.parse(data);
          if (res.statu == 400) {
            swal.fire({
              icon: "error",
              text: res.message,
              title: "Uyarı!",
            });
          } else {
            Route();
            swal.fire({
              icon: "success",
              text: res.message,
              title: "Başarılı!",
            });
          }
        },
      });
    }
  }
}
