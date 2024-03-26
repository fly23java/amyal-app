$(document).ready(function() {

    $(document).on('click', '#test_hima', function(e) {
        setTimeout(function() {
            showPreloader();
        }, 1000);

        var id = $(this).data('id');
        var data = {
            'id': id
        };
        $("#shipment_id").val(id);

        $.ajax({
            url: config.routes.getVehcile,
            method: 'get',
            data: data,
            dataType: 'json',
            success: function(response) {
                $('#vehicle_id').html("");
                $("#carrier_price").val('');
                $('#shipmentCrarierPrice').html('');
                if (response.Vehicle && response.Vehicle.length > 0) {
                    $('#vehicle_id').append($('<option>اختر المركية</option>'));
                    $.each(response.Vehicle, function(i, item) {
                        $('#vehicle_id').append($('<option>', {
                            value: item.id,
                            text: item.plate + ' ' + item.right_letter + ' ' + item.middle_letter + ' ' + item.left_letter
                        }));
                    });

                    $('#vehicle_id').select2();
                } else {
                    $('#vehicle_id').append($('<option>', {
                        value: '',
                        text: 'لا توجد مركبات متوافق مع طلبك'
                    }));
                }
            },
            error: function(response) {
                console.error("Error fetching vehicle data:", response.statusText);
            }
        });

        $.ajax({
            url: config.routes.getDatahipmentdetails,
            method: 'get',
            dataType: 'json',
            data: data,
            success: function(response) {
                if (response.error !== true) {
                    if (response.shipmentDeliveryDetail !== null && response.shipmentDeliveryDetail.vehicle_id !== "") {
                        let vehicleId = response.shipmentDeliveryDetail.vehicle_id;
                        $('#vehicle_id').val(vehicleId).trigger('change');
                    }

                    if (response.shipment.carrier_price !== null && response.shipment.carrier_price !== "") {
                        $("#carrier_price").val(response.shipment.carrier_price);
                    } else {
                        $("#carrier_price").html("");
                    }
                }
                setTimeout(function() {
                    $('#preloader').fadeOut();
                }, 1000);

                $('#modals-slide-in').modal('show');
            },
            error: function(response) {
                console.log('no data');
            }
        });

    });

    // $(document).on('change', '#vehicle_id', function(e) {
    //     let shipmentCrarierPrice = $("#carrier_price").val();
    //     let vehicleId = $('#vehicle_id').val();
    //     let shipmentId = $('#shipment_id').val();

    //     if (vehicleId !== null && shipmentId !== null) {
    //         let data = {
    //             'vehicle_id': vehicleId,
    //             'shipment_id': shipmentId,
    //         };

    //         $.ajax({
    //             url: config.routes.getCarrierPrice,
    //             method: 'get',
    //             data: data,
    //             dataType: 'json',
    //             success: function(response) {
    //                 $('#shipmentCrarierPrice').html('');
    //                 if (response.error !== true) {
    //                     if (response.price === true) {
    //                         $('#shipmentCrarierPrice').append($('<div class="form-group">' +
    //                             '<label class="form-label" for="basic-icon-default-fullname">السعر الموجود في العقد</label>' +
    //                             '<input class="form-control" name="old_carrier_price" type="number" id="old_carrier_price">' +
    //                             '</div>'));

    //                         $("#old_carrier_price").val(response.contractprice);
    //                     }
    //                 }
    //             },
    //             error: function(response) {
    //                 $("#carrier_price").val(shipmentCrarierPrice);
    //             },
    //             complete: function() {
    //                 // Hide preloader if needed
    //             }
    //         });
    //     }

    // });

    function showPreloader() {
        $('#preloader').fadeIn();
    }

    function hidePreloader() {
        $('#preloader').fadeOut();
    }

});
