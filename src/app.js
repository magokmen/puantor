function submitFormbyAjax(page, params, messageType = "alert") {
  var form = document.getElementById("myForm");
  var formData = new FormData(form);
  if (validateForm()) {
    if (params != "") {
      var parsedParams = JSON.parse(params);
      for (var key in parsedParams) {
        formData.append(key, parsedParams[key]);
      }
    }

    // formData elemanlarını console ekranında göstermek için
    // formData.forEach(function (value, key) {
    //   console.log(key + ": " + value);
    // });

    // Dinamik olarak formun gönderileceği sayfayı belirle
    var submitUrl = "pages/" + page + ".php";

    fetch(submitUrl, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        // Sunucudan gelen HTML ile sayfa içeriğini güncelle
        updatePageContent(data);
        var successMessage = "İşlem Başarı ile gerçekleşti";
        if (messageType === "alert") {
          showMessage(successMessage, "success");
        } else {
          toastrdefaultsuccess(successMessage);
        }
      })
      .catch((error) => console.error(error));
  }
}
function submitFormbyAjax(page, params, messageType = "alert") {
  var form = document.getElementById("myForm");
  var formData = new FormData(form);
  if (validateForm()) {
    if (params != "") {
      var parsedParams = JSON.parse(params);
      for (var key in parsedParams) {
        formData.append(key, parsedParams[key]);
      }
    }

    //formData elemanlarını console ekranında göstermek için
    // formData.forEach(function (value, key) {
    //   console.log(key + ": " + value);
    // });

    // Dinamik olarak formun gönderileceği sayfayı belirle
    var submitUrl = "pages/" + page + ".php";

    fetch(submitUrl, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        // Sunucudan gelen HTML ile sayfa içeriğini güncelle
        updatePageContent(data);

        var successMessage = "İşlem Başarı ile gerçekleşti";
        if (messageType === "alert") {
          showMessage(successMessage, "success");
        } else {
          toastrdefaultsuccess(successMessage);
        }
      })
      .catch((error) => console.error(error));
  }
}
function submitFormReturnJson(page, params, messageType = "alert") {
  var form = document.getElementById("myForm");
  var formData = new FormData(form);
  if (validateForm()) {
    if (params != "") {
      var parsedParams = JSON.parse(params);
      for (var key in parsedParams) {
        formData.append(key, parsedParams[key]);
      }
    }

    //  formData elemanlarını console ekranında göstermek için
    // formData.forEach(function (value, key) {
    //   console.log(key + ": " + value);
    // });

    // Dinamik olarak formun gönderileceği sayfayı belirle
    var submitUrl = "pages/" + page + ".php";
    // console.log(submitUrl);
    $.ajax({
      url: submitUrl,
      data: formData,
      type: "POST",
      processData: false,
      contentType: false,
      success: function (response) {
        var data = JSON.parse(response);
        // console.log(data);
        if (data.status == 200) {
          // Başarı mesajı ve sayfa içeriği güncelleme
          var successMessage = data.message;
          RoutePage(data.page, "", (pTitle = data.pTitle));

          if (messageType === "alert") {
            showMessage(successMessage, "success");
          } else {
            toastrdefaultsuccess(successMessage);
          }
        } else {
          var errorMessage = data.message;
          if (messageType === "alert") {
            showMessage(errorMessage, "error");
          } else {
            toastrdefaulterror(errorMessage);
          }
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("AJAX error: " + textStatus + ": " + errorThrown);
        showMessage("Beklenmeyen bir hata oluştu.", "error");
      },
    });
  }
}

function formatDate(date) {
  let day = date.getDate();
  let month = date.getMonth() + 1; // Aylar 0'dan başladığı için 1 ekleyin
  let year = date.getFullYear();

  // Gün ve ay değerlerini iki haneli yapmak için
  if (day < 10) day = "0" + day;
  if (month < 10) month = "0" + month;

  return day + "." + month + "." + year;
}

function toastrdefaultAlert(alertMessage, type) {
  var Toast = Swal.mixin({
    toast: true,
    position: "top-center",
    showConfirmButton: false,
    timer: 3000,
  });

  const options = {
    closeButton: true,
    positionClass: "toast-top-right",
  };
  toastr.error(alertMessage, "Uyarı!", options);
}
function toastrdefaultsuccess(successMessage) {
  var Toast = Swal.mixin({
    toast: true,
    position: "top-center",
    showConfirmButton: false,
    timer: 3000,
  });

  const options = {
    closeButton: true,
    positionClass: "toast-top-center",
  };
  toastr.success("İşlem Durumu", successMessage, options);
}

function validateForm(messageType = "alert") {
  var isNull = true;
  var form = document.getElementById("myForm");
  var elements = form.elements;
  var emptyFields = [];

  for (var i = 0; i < elements.length; i++) {
    if (
      elements[i].hasAttribute("required") &&
      elements[i].value.trim() === ""
    ) {
      var label = document.querySelector(
        'label[for="' + elements[i].getAttribute("name") + '"]'
      );
      var labelText = label
        ? label.textContent.trim().replace(/[:\(\*\)]/g, "")
        : elements[i].getAttribute("name");
      emptyFields.push(labelText);
    }
  }

  if (emptyFields.length > 0) {
    var errorMessage =
      "Lütfen zorunlu alanları doldurun: </br>" + emptyFields.join(", ");
    if (messageType === "alert") {
      showMessage(errorMessage, "alert");
    } else {
      toastrdefaultAlert(errorMessage, "warning");
    }

    isNull = false;
  } else {
    isNull = true;
    //var form = document.getElementById("myForm");
    //form.submit(); // Formu gönder
    //SubmitForm();
  }
  return isNull;
}

function deleteRecordByAjax(page, params) {
  var formData = new FormData();
  formData.append("method", "Delete");
  var parsedParams = JSON.parse(params);
  for (var key in parsedParams) {
    formData.append(key, parsedParams[key]);

    if (key == "message") {
      delMessage = parsedParams[key];
    } else {
      delMessage = "Geçerli kaydı silmek istiyor musunuz?";
    }
  }

  Swal.fire({
    title: "Emin misiniz?",
    text: delMessage,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Evet, Sil!",
    cancelButtonText: "Vazgeç!",
  }).then((result) => {
    if (result.isConfirmed) {
      // If confirmed, trigger AJAX request to delete record
      var deleteUrl = "pages/" + page + ".php";

      //formData elemanlarını console ekranında göstermek için
      // formData.forEach(function (value, key) {
      //   console.log(key + ": " + value);
      // });

      fetch(deleteUrl, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((data) => {
          var data = JSON.parse(data);
          console.log(data);
          if (data.status == 200) {
            Swal.fire({
              title: "Başarılı!",
              text: data.message,
              icon: "success",
            }).then(() => {
              //console.log("sayfa :" + page, "sayfa başlığı :" + data.pTitle);
              RoutePage(page, "", data.pTitle);
            });
          } else {
            Swal.fire({
              title: "Hata!",
              text: data.message,
              icon: "error",
            });
          }
        })
        .catch((error) => {
          // Handle error if deletion fails (optional)
          console.error(error);
          Swal.fire({
            title: "Hata!",
            text: "Bir şeyler ters gitti!",
            icon: "error",
          });
        });
    } //is confirmed
  });
}

function updatePageContent(html) {
  $("#content").html(html);
}

function updateUrlAddresses(page) {
  //var newUrl = window.location.origin + "/" + page;
  window.history.pushState("", "", "/" + page);
}

function RoutePagewithParams(page, params = "", animate = true) {
  if (animate === true) {
    $(".preloader")
      .css("height", $(window).height() + "px")
      .css("opacity", 1)
      .fadeIn(400);
  }

  $("#content").empty(); // İçeriği temizle
  session_check();
  $("#content").animate({ opacity: 0 }, 300, function () {
    $(this).load("pages/" + page + ".php?" + params, function () {
      // Yükleme tamamlandığında içeriği gösterme işlemi
      $(this).animate({ opacity: 1 }, 300);
      $(".preloader").css("opacity", 0).fadeOut(400);
    });
  });
}

function RoutePage(page, element = "", pTitle = "") {
  console.log(page, pTitle);
  var pageTitleElement = $("#page-title");
  var params = element ? $(element).data("params") : "";
  if (pTitle != "") {
    pageTitleElement.text(pTitle);
  } else {
    var pTitle = element ? $(element).data("title") : "";
    pageTitleElement.text(pTitle);
  }
  $("#content").empty(); // İçeriği temizle
  session_check();
  if (page != null) {
    $("#content").animate({ opacity: 0 }, 300, function () {
      $(this).load("pages/" + page + ".php?" + params, function () {
        // Yükleme tamamlandığında içeriği gösterme işlemi
        //console.log(page,"pTitle : " + pTitle);
        set_session_page("pages/" + page + ".php", pTitle);
        $(this).animate({ opacity: 1 }, 300);
      });
    });
  }
}

function pageTitle(page, title) {
  // var page = page ? page : $(element).data("page");
  // var title = $(element).data('title');
  var pageTitleElement = $("#page-title");

  document.title = title;
  pageTitleElement.text(title);
}

$(function () {
  $(".loadContent").on("click", loadPage);
});

function loadPage(e) {
  e.preventDefault();

  var page = $(this).data("page");
  var title = $(this).data("title");
  var params = $(this).data("params");

  pageTitle(page, title);

  var pagelink;
  session_check();
  // Sayfanın var olup olmadığını kontrol et
  $.ajax({
    url: "pages/" + page + ".php",
    type: "HEAD",
    error: function () {
      // Sayfa yoksa 404'e yönlendir
      // pagelink = "404.php";
      loadContent(pagelink);
    },
    success: function () {
      // Sayfa varsa
      pagelink = "pages/" + page + ".php?" + params;
      set_session_page("pages/" + page + ".php", title);
      loadContent(pagelink);
    },
  });
}

function loadContent(pagelink) {
  // Show preloader

  $(".preloader")
    .css("height", $(window).height() + "px")
    .css("opacity", 1)
    .fadeIn(400);

  session_check();
  $("#content").fadeOut(50, function () {
    $("#content").empty(); // İçeriği temizle
    $(this).load(pagelink, function () {
      // Yükleme tamamlandığında, içeriği göster
      $(this).fadeIn(50);
      // Hide preloader
      $(".preloader").css("opacity", 0).fadeOut(400);
    });
  });
}

function updateBreadcrumb(pageName) {
  // Mevcut breadcrumb'ın içeriğini temizle
  $("#breadcrumb").empty();
  // Tıklanan menü öğesinin ismini breadcrumb'a ekle
  $("#breadcrumb").append('<a href="#">Home</a><span> / ' + pageName + "</>");
}

$(function () {
  $(".nav-link:not('.tabMenu')").on("click", function () {
    setActiveMenu(this);
  });
});

function setActiveMenu(clickedLink, attribute = "title") {
  $(".nav-link").removeClass("active"); // Tüm bağlantılardan active sınıfını kaldır
  $(".nav-link").removeClass("menu-open");
  page = $(clickedLink).data("page");

  var pageTitle = $(clickedLink).data(attribute); // clickedLink parametresini kullan
  $(".nav-menu[data-title='" + pageTitle + "']").addClass("active");
  // set_session_page()
}

function setActiveMenubyTabMenu(e) {
  $(".nav-link").removeClass("active"); // Tüm bağlantılardan active sınıfını kaldır
  $(".nav-link").removeClass("menu-open");
  var pageTitle = $(e).data("link") ?? $(e).data("title");
  // console.log(pageTitle);
  $(".nav-menu[data-title='" + pageTitle + "']").addClass("active");
}

function session_check() {
  $.ajax({
    url: "session_check.php",
    type: "POST",
    success: function (response) {
      if (response == "invalid") {
        window.location.href = "logout.php";
      }
    },
  });
}

function set_session_page(page, pageTitle) {
  $.ajax({
    url: "set_session_page.php",
    type: "POST",
    data: {
      page: page,
      pageTitle: pageTitle,
    },
    success: function (response) {
      if (response == "invalid") {
        window.location.href = "index.php";
      }
    },
  });
}

function SubmitForm() {
  setTimeout(function () {
    showMessage("İşlem Başarı ile tamamlandı!", "success"); // Mesajı göster
  }, 3000); // 2000 milisaniye (2 saniye) sonra göster
}

function showMessage(message, type) {
  var alertClass = "";
  var firstLetter = "";
  //console.log(message);

  if (type === "success") {
    alertClass = "alert-success";
    firstLetter = "Başarılı!";
  } else if (type === "alert") {
    alertClass = "alert-danger";
    firstLetter = "Uyarı!";
  } else if (type === "error") {
    alertClass = "alert-warning";
    firstLetter = "Hata";
  } else if (type === "info") {
    alertClass = "alert-info";
    firstLetter = "Bilgi";
  }

  if (alertClass && message) {
    var alertMessage = $(
      '<div class="message alert ' +
        alertClass +
        ' alert-dismissible fade show">' +
        "<strong>" +
        firstLetter +
        "</strong> " +
        message +
        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
        "</div>"
    );

    $("#content").before(alertMessage);

    window.setTimeout(function () {
      alertMessage.fadeTo(500, 0).slideUp(500, function () {
        $(this).remove();
      });
    }, 3000);
  }
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

function SaveNewKategory(p_name, selectName) {
  var Addcategory = document.getElementById("Addcategory").value;
  if (Addcategory != "") {
    fetch("index.php?p=" + p_name, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "Addcategory=" + encodeURIComponent(Addcategory),
    })
      .then((response) => {
        var selectElement = document.getElementById(selectName);
        var newOption = document.createElement("option");
        newOption.value = Addcategory;
        newOption.textContent = Addcategory;
        selectElement.appendChild(newOption);
        document.getElementById("Addcategory").value = "";
      })
      .catch((error) => {
        // Hata durumunda burada işlemler yapabilirsiniz
      });
  }
}

function offerControl() {
  var customers = document.getElementById("customers");
  showMessage("success", customers.value);
}

$(function () {
  var Toast = Swal.mixin({
    toast: true,
    position: "toast-top-center",
    showConfirmButton: false,
    timer: 3000,
  });

  $(".swalDefaultSuccess").click(function () {
    Toast.fire({
      icon: "success",
      position: "top-center",
      title: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".swalDefaultInfo").click(function () {
    Toast.fire({
      icon: "info",
      title: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".swalDefaultError").click(function () {
    Toast.fire({
      icon: "error",
      title: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".swalDefaultWarning").click(function () {
    Toast.fire({
      icon: "warning",
      title: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".swalDefaultQuestion").click(function () {
    Toast.fire({
      icon: "question",
      title: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });

  $(".toastrDefaultSuccess").click(function () {
    const options = {
      closeButton: true,
      positionClass: "toast-top-center",
    };
    toastr.success("İşlem Durumu", "İşlem başarı ile yapıldı.", options);
  });

  $(".toastrDefaultInfo").click(function () {
    toastr.info("Lorem ipsum dolor sit amet, consetetur sadipscing elitr.");
  });
  $(".toastrDefaultError").click(function () {
    toastr.error("Lorem ipsum dolor sit amet, consetetur sadipscing elitr.");
  });
  $(".toastrDefaultWarning").click(function () {
    toastr.warning("Lorem ipsum dolor sit amet, consetetur sadipscing elitr.");
  });

  $(".toastsDefaultDefault").click(function () {
    $(document).Toasts("create", {
      title: "Toast Title",
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".toastsDefaultTopLeft").click(function () {
    $(document).Toasts("create", {
      title: "Toast Title",
      position: "topLeft",
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.yyyyyyyyyy",
    });
  });
  $(".toastsDefaultBottomRight").click(function () {
    $(document).Toasts("create", {
      title: "Toast Title",
      position: "bottomRight",
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".toastsDefaultBottomLeft").click(function () {
    $(document).Toasts("create", {
      title: "Toast Title",
      position: "bottomLeft",
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".toastsDefaultAutohide").click(function () {
    $(document).Toasts("create", {
      title: "Toast Title",
      autohide: true,
      delay: 750,
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".toastsDefaultNotFixed").click(function () {
    $(document).Toasts("create", {
      title: "Toast Title",
      fixed: false,
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".toastsDefaultFull").click(function () {
    $(document).Toasts("create", {
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
      title: "Toast Title",
      subtitle: "Subtitle",
      icon: "fas fa-envelope fa-lg",
    });
  });
  $(".toastsDefaultFullImage").click(function () {
    $(document).Toasts("create", {
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
      title: "Toast Title",
      subtitle: "Subtitle",
      image: "../../dist/img/user3-128x128.jpg",
      imageAlt: "User Picture",
    });
  });
  $(".toastsDefaultSuccess").click(function () {
    $(document).Toasts("create", {
      class: "bg-success",
      title: "Toast Title",
      subtitle: "Subtitle",
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".toastsDefaultInfo").click(function () {
    $(document).Toasts("create", {
      class: "bg-info",
      title: "Toast Title",
      subtitle: "Subtitle",
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".toastsDefaultWarning").click(function () {
    $(document).Toasts("create", {
      class: "bg-warning",
      title: "Toast Title",
      subtitle: "Subtitle",
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".toastsDefaultDanger").click(function () {
    $(document).Toasts("create", {
      class: "bg-danger",
      title: "Toast Title",
      subtitle: "Subtitle",
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
  $(".toastsDefaultMaroon").click(function () {
    $(document).Toasts("create", {
      class: "bg-maroon",
      title: "Toast Title",
      subtitle: "Subtitle",
      body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
    });
  });
});

$(document).ready(function () {
  $(".select2").select2();
  //Initialize Select2 Elements

  //Ay değiştiği zaman sayfayı yeniden yükle
  $("#companyindex").on("change", function () {
    Route();
  });

  function Route() {
    var company_id = $("#companyindex option:selected").val();
    window.location.href = "index.php?company_id=" + company_id;
  }

  moment().locale("tr");
  $("[data-mask]").inputmask("dd.mm.yyyy");
  $("#startdate,#enddate").datetimepicker({
    format: "DD.MM.YYYY",
    locale: moment.locale("tr"),
  });

  // jQuery UI sortable for the todo list
  $(".todo-list").sortable({
    placeholder: "sort-highlight",
    handle: ".handle",
    forcePlaceholderSize: true,
    zIndex: 999999,
  });
});
