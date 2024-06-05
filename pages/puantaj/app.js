$(document).ready(function () {
  const listgun = $(".gun");

  listgun.on("click", function () {
    if ($(this).hasClass("clicked")) {
      $("#modal-default").modal("show");
    }
  });

  let isMouseDown = false;
  $(".gun").mouseover(function (event) {
    if (isMouseDown) {
      $(this).addClass("clicked");
    }
  });

  $(".gun")
    .mousedown(function () {
      isMouseDown = true;
      $(this).toggleClass("clicked");
    })
    .mouseup(function () {
      isMouseDown = false;
      if ($(".gun.clicked").length > 0) {
        $("#modal-default").modal("show");
      }
    });

  const listItems = $(".nav-item");

  listItems.click(function () {
    const clickedCells = $("table td.clicked");

    const avatar = $(this).find(".avatar");
    const avatarText = avatar.text();
    const avatarColor = avatar.css("color");
    const avatarBgColor = avatar.css("background-color");
    const avatarDataid = avatar.attr("data-id");

    clickedCells.each(function () {
      clickedCells.text(avatarText);
      clickedCells.css("color", avatarColor);
      clickedCells.attr("data-id", avatarDataid);
      clickedCells.css("background-color", avatarBgColor);
    });
    clickedCells.removeClass("clicked");
    $("#modal-default").modal("hide");
  });
});

//Delete tuşuna basıldığı zaman içeriği temizler
$(document).keydown(function (event) {
  // 'Delete' tuşunun keycode'u 46'dır
  if (event.keyCode === 46) {
    // .clicked sınıfına sahip tüm td elemanlarını seç ve içeriğini temizle
    $("td.clicked").each(function () {
      $(this).attr("data-id", "");
      $(this).empty();
      $(this).toggleClass("clicked");
      $(this).css("background-color", "white");
    });
  }
});

$(".table th").click(function () {
  // Tıklanan sütunun indeksini al
  var columnIndex = $(this).index();
  var modalShow = true;
  // Tüm satırlardaki ilgili sütundaki td'lere .clicked sınıfını ekle
  if (columnIndex > 2) {
    $(".table tbody tr").each(function () {
      $(this)
        .find("td:eq(" + columnIndex + ")")
        .toggleClass("clicked");
    });
    if ($(".table td.clicked").length > 0) {
      $("#modal-default").modal("show");
    }
  }
});

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
    "puantaj/main",
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

//Personel Özet Bilgilerini gösternmek için
$(".btn-user-modal").on("click", function () {
  // Butonun bulunduğu satırı bul
  var row = $(this).closest("tr");
  var user_id = row.find("td:first").data("id");

  $.ajax({
    url: "ajax.php",
    type: "POST",
    data: {
      action: "user-info",
      id: user_id,
    },
    success: function (data) {
      // alert(data);
      var response = JSON.parse(data);
      if (response.statu == 200) {
        // Kullanıcı adının baş harflerini al
        var initials = response.data.full_name
          .split(" ")
          .map(function (word) {
            return word.charAt(0).toUpperCase();
          })
          .join("");
        $(".avatar-user").text(initials);
        $(".lead-name").text(response.data.full_name);
        $(".lead-job").text(response.data.job);
        $(".lead-daily-wages").html("<strong>Günlük Ücret:</strong> " + response.data.daily_wages);
        $(".lead-phone").text(response.data.phone);
        $(".lead-email").text(response.data.email);
        $("#modal-summary").modal("show");
      }
    },
  });

  // // Butonun text değerini al
  // var userName = $(this).text();

  // // Modal içindeki değerleri güncelle
  // $(".lead-job").text(jobDescription);
  // $(".lead-name").text(userName);
  // // $(".avatar-user").text(initials);

  // // Modali göster
  // $("#modal-summary").modal("show");
});

function puantaj_olustur() {
  // JSON verisini saklamak için bir nesne oluştur
  var jsonData = {};
  // Tablodaki her satırı döngü ile işle
  $("table tbody tr").each(function (index) {
    var row = $(this);
    var employeeData = {}; // Her çalışan için bir nesne oluştur

    // Ad, soyad ve ünvan bilgisini al
    var person_id = row.find("td:first").data("id");
    var position = row.find("td:eq(1)").text();

    // Tarihler için döngü yap
    row.find("td:gt(2)").each(function (index, td) {
      var date = $("table thead tr:eq(1) th")
        .eq(index + 3)
        .text(); // İndeks + 2, 2. indeksten başlamasını sağlar

      var status = $(this).attr("data-id") ? $(this).attr("data-id") : ""; // Durum bilgisini al
      // console.log(person_id + "--" + date + "--" + status); //

      // Çalışanın adı, soyadı ve ünvanı ile birleştirilmiş anahtar oluştur
      var key = person_id + " : " + position;

      // Anahtar zaten varsa, alt nesneye yeni tarih ve durum ekleyin
      if (jsonData[key]) {
        jsonData[key][date] = status;
      } else {
        // Yoksa yeni bir alt nesne oluşturun ve anahtarla birlikte ana nesneye ekleyin
        employeeData[date] = status;
        jsonData[key] = employeeData;
      }
    });
  });

  // JSON verisini konsolda göster
  // console.log(JSON.stringify(jsonData, null, 2));

  var company_id = $("#company option:selected").val();
  var project_id = $("#project option:selected").val();
  var year = $("#year").val();
  var month = $("#months").val();

  var data = {
    action: "puantaj",
    company_id: company_id,
    project_id: project_id,
    months: month,
    year: year,
    data: JSON.stringify(jsonData),
  };
  // console.log(company_id, year, month);
  $.ajax({
    url: "ajax.php",
    type: "POST",
    data: data,
    dataType: "json",
    success: function (response) {
      if (response.status == 200) {
        swal.fire({
          title: "Başarılı",
          icon: "success",
          text: response.message,
        });
      } else if (response.status == 400) {
        swal.fire({
          title: "Uyarı",
          icon: "alert",
          text: response.message,
        });
      }
    },
    error: function (xhr, status, error) {
      // Handle error if deletion fails (optional)
      console.error(xhr.responseText);
      Swal.fire({
        title: "Hata!",
        text: "Bir şeyler ters gitti!",
        icon: "error",
      });
    },
  });
}
