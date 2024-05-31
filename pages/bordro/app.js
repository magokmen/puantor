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
  console.log(state_val);

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
      success: function (data) {
        
      },
    });
  }
}
