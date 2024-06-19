    // $(function() {

    //     var pagetitle = $("#page-title").text();
    //     if (pagetitle == "Kasa") {
    //         $("#liste").tab("show");
    //     }

    //     if (pagetitle == "Özet") {
    //         alert("")
    //         $("#kasaozet").tab("show");
    //     }
    // })

    // $(function() {
    //     $(".tabMenu").click(function() {
    //         var navLinkText = $(this).data("title");
    //         $("#page-title").text(navLinkText);
    //         setActiveMenu(this);
    //     });
    // });

    // $('[data-mask]').inputmask('dd.mm.yyyy')
    // $('#reservationdate').datetimepicker({
    //     format: 'DD.MM.YYYY',
    //     locale: 'tr'

    // });

    $(".closeModal").click(function() {
        submitFormByModal();

    })

    
    function submitFormByModal() {
        var formData = new FormData($("#myForm")[0]);
        var vault_id = $("#vault_id").val();
        formData.append('type', "1");
        formData.append('action', "add-transaction");
        formData.append('vault_id', vault_id);

        //console.log([...formData]);

        if (validateForm('toast')) {

            $.ajax({
                url: 'pages/kasa/main.php',
                method: 'POST',
                data: formData,
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                success: function(res) {
                    var res = JSON.parse(res);
                    if (res.status == 200) {
                        vault_actions(vault_id);
                        case_summary(vault_id);
                        toastr.success(
                            res.message,
                            "İşlem Durumu",
                            "success",
                        )
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error: ' + textStatus + ' - ' + errorThrown);
                }

            })

            //$(".modal-backdrop").remove();

        }

    }

    $(document).ready(function() {
        const listItems = $(".kasa ul li");

        listItems.on("click", function(event) {
            if (!$(event.target).is("i")) {
                listItems.removeClass('clicked');
                $(this).addClass('clicked');
            }

        })



    });

    $(".kasa .kasa-item").click(function() {
        var vault_id = $(this).data("params");
        var date = new Date();

        $("#vault_id").val(vault_id)

        $("#vault_date").val(formatDate(date));
        case_summary(vault_id);
        vault_actions(vault_id);
    })

    function case_summary(vault_id) {
       
        $.ajax({
            url: "ajax.php",
            method: "POST",
            data: {
                'vault_id': vault_id,
                'action': 'kasa-ozeti'
            },
            success: function(data) {
                var data = JSON.parse(data);
                if (data.status == 200) {
                    //console.log(data);
                    $("#total_income").text(data.total_income);
                    $("#total_expense").text(data.total_expense);
                    $("#total_balance").text(data.total_balance);
                    $("#total_profit").text(data.total_profit);

                } else {
                    toastr.error(data.message);
                }


            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error: " + textStatus + " - " + errorThrown);
            }

        });
    }

    $(".glr").click(function() {
        var vault_id = $("#vault_id").val();
        if (vault_id == 0) {
            toastr.error("Kasa Seçiniz");
            return false;
        }
    })



    function vault_actions() {
        var vault_id = $("#vault_id").val();
        $.ajax({
            url: "ajax.php",
            method: "POST",
            data: {
                'vault_id': vault_id,
                'action': 'kasa'
            },
            success: function(data) {
                new DataTable('#example1', {
                    destroy: true,
                    data: data.columns,
                     columns: [{
                            data: 'id'
                        }, // Sütun adı veritabanı kolon adı ile eşleşmelidir
                        {
                            data: 'account_name'
                        },
                        {
                            data: 'type_id',
                            render: function(data, type, row) {
                                if (data == 1) {
                                    return 'Gelir';
                                } else {
                                    return data; // or return whatever you want for other cases
                                }
                            }
                        },
                        {
                            data: 'date'
                        },
                        {
                            data: 'case_name'
                        },
                        {
                            data: 'sub_type'
                        },
                       
                        {
                            data: 'amount'
                        },
                        {
                            data: 'description'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return '<i class="fa-solid fa-ellipsis list-button" data-toggle="dropdown"></i>' +
                                    '<ul class="dropdown-menu">' +
                                    '<li class="dropdown-item edit"><i class="fa-solid fa-edit dropdown-list-icon"></i>' +
                                    '<a href="#" onclick="RoutePagewithParams(\'kasa/edit\',\'{id=' + row.id + '}\')" data-title="Kasa Güncelleme">Güncelle</a>' +
                                    '</li>' +
                                    '<li class="dropdown-item"><i class="fa-solid fa-trash dropdown-list-icon"></i>' +
                                    '<a href="#" class="delete-transaction" data-id=' + row.id + '>Sil</a>' +
                                    '</li>' +
                                    '</ul>';
                            }
                        }
                    ],
                    language: {
                        search: "Ara",
                        emptyTable: "Kayıt Yok!",
                        lengthMenu: "Her sayfada _MENU_ kayıt göster ",
                        paginate: {
                            first: "İlk",
                            last: "Son",
                            next: "Sonraki",
                            previous: "Önceki"
                        },
                        info: "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
                        infoEmpty: "Kayıt bulunamadı!",

                    },
                    responsive: true,
                    autowidth: true,

                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error: " + textStatus + " - " + errorThrown);
            }
        });
    }


    $(document).on("click", ".delete-transaction", function() {
        var id = $(this).data("id");


        swal.fire({
            title: "Emin misiniz?",
            text: "Bu işlemi geri alamazsınız!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Evet, sil!",
            cancelButtonText: "İptal"
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: "pages/kasa/main.php",
                    method: "POST",
                    type: "json",
                    data: {
                        id: $(this).data("id"),
                        action: "delete-transaction"
                    },
                    success: function(data) {
                        var data = JSON.parse(data);
                        if (data.status == 200) {
                            //toastr.success(data.message);
                            swal.fire({
                                title: "Başarılı",
                                text: "Başarılı bir şekilde silindi!",
                                icon: "success",
                            })
                            vault_actions();
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log("Error: " + textStatus + " - " + errorThrown);
                    }
                });
            }
        });
    });

    $(".delete-case").click(function(e) {
        var caseId = $(this).attr("data-id");
        e.preventDefault();
        var $this = $(this); //
       
        swal.fire({
            title: "Emin misiniz?",
            text: "Bu işlemi geri alamazsınız!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Evet, sil!",
            cancelButtonText: "İptal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "pages/kasa/main.php",
                    method: "POST",
                    type: "json",
                    data: {
                        id: caseId,
                        action: "delete-case"
                    },
                    success: function(data) {
                        console.log(data);
                        var data = JSON.parse(data);
                        if (data.status == 200) {
                            $this.closest('.nav-item').remove(); // Üst 'nav-item' elementini bul ve sil
                            
                            //toastr.success(data.message);
                            swal.fire({
                                title: "Başarılı",
                                text: "Başarılı bir şekilde silindi!",
                                icon: "success",
                            })
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log("Error: " + textStatus + " - " + errorThrown);
                    }
                });
            }
        });

    }
    );
