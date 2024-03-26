$(document).ready(function () {
    // Handle tab click event

    $('.tab-shipment').on('click', function (e) {
        e.preventDefault();

        // Get the tab content ID based on the data-id attribute
        var id = $(this).data('id');

        var data = {
            'id': id
        };

        $.ajax({
            type: "GET",
            url: config.routes.retrunShipmentInTabsByStatus,
            data: data,
            dataType: "json",
            success: function (response) {
                $('#home' + id).html("");
                var table = $('.zero-configuration2').DataTable();
                table.destroy();

                $('#home' + id).append(response.data);

                $('.zero-configuration2').DataTable({
                    "drawCallback": function () {
                        $('.previous').addClass('btn btn-sm btn-dark');
                        $('.paginate_button').addClass('btn btn-sm btn-primary');
                        $('.next').addClass('btn btn-sm btn-dark');
                        $('div.dataTables_filter input').addClass('form-control');
                        $('.dataTables_length').addClass('d-none');
                    },
                    order: [[3, 'desc']]
                });
            }
        });
    });
});
