$(document).ready(function(){ 
    // Event listener for clicking on element with id 'statuses_edit'
    $(document).on('click', '#statuses_edit', function(e) {
        var id = $(this).data('id');
        var data = {
            'id': id
        };
        
        // AJAX request to retrieve data
        $.ajax({
            url: config.routes.statusesGet,
            method: 'get',
            data: data,
            dataType: 'json',
            success: function(response) {
                // Clear dropdown list
                $('#status_id').html("");
                
                // Populate dropdown list with options based on response
                $.each(response.Status, function (i, item) {
                    $('#status_id').append($('<option>', { 
                        value: item.id,
                        text: item.name_arabic,
                    }));
                });

                // Set selected option in dropdown list based on response value
                $("#status_id option").each(function() {
                    if ($(this).val() == response.shipment.status_id) {
                        $(this).attr("selected", "selected");    
                    }
                });

                // Set value of input field with id 'shipment_status_id'
                $("#shipment_status_id").val(response.shipment.id);
            },
            error: function(response) {
                // Handle error if needed
            }
        });
    });
});
