<script>
    $(document).ready(function () {
        $(function () {
            table = $("#example1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,

                "fixedColumns": true,
                ordering: false,
                language: {
                    info: '_PAGES_ sayfadan _PAGE_. sayfa gösteriliyor',
                    infoEmpty: 'Hiç kayıt bulunamadı',
                    infoFiltered: '(_MAX_ kayıt filtrelendi)',
                    lengthMenu: 'Her sayfada _MENU_ kayıt göster',
                    zeroRecords: 'Kayıt Yok!',
                    search: "Ara",
                    paginate: {
                        "first": "İlk",
                        "last": "Son",
                        "next": "Sonraki",
                        "previous": "Önceki"
                    },
                },

            })

        });
    })


</script>