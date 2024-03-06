

$(document).ready(function(){ 


    
    $(document).on('click', '#test_hima', function(e) {
        //get cover id
       
        // $("#loader").removeClass("d-none"); 
        // $("#loader").addClass("d-block"); 
        
        var id = $(this).data('id');
        var data = {
            'id': id
        };
        $("#shipment_id").val(id);
        
        // بدء الحصول على البيانات إذا كانت متاحة
        $.ajax({
            url: config.routes.getVehcile,
            method: 'get',
            data: data,
            dataType: 'json',
            success: function(response) {
                $('#vehicle_id').html("");
                if (response.Vehicle && response.Vehicle.length > 0) {
                    // إذا كانت هناك بيانات ، قم بمعالجتها
                    $.each(response.Vehicle, function(i, item) {
                        $('#vehicle_id').append($('<option>', {
                            value: item.id,
                            text: item.plate + ' ' + item.right_letter + ' ' + item.middle_letter + ' ' + item.left_letter
                        }));
                    });
        
                    // Initialize select2 after appending options
                    $('#vehicle_id').select2();
                } else {
                    // رسالة إذا لم تكن هناك بيانات
                    $('#vehicle_id').append($('<option>', {
                        value: '',
                        text: 'لا توجد مركبات متوافق مع طلبك'
                    }));
                }
            },
            error: function(response) {
                // رسالة خطأ
                console.error("Error fetching vehicle data:", response.statusText);
            }
        });
        
        // start get data if you hava
        $.ajax({
            url: config.routes.getDatahipmentdetails,
            method: 'get',
            dataType: 'json',
            data: data,
            success: function(response) {
                if (response.shipmentDeliveryDetail && $.trim(response.shipmentDeliveryDetail.vehicle_id)) {
                    let vehicleId = response.shipmentDeliveryDetail.vehicle_id;
        
                    // Set the value for the select and trigger the change event for Select2
                    $('#vehicle_id').val(vehicleId).trigger('change');
                }
        
                if ($.trim(response.shipment.carrier_price)) {
                    $("#carrier_price").val(response.shipment.carrier_price);
                }
            },
            error: function(response) {
                console.log('no data');
            }
        });
        

      
        
        
          
        // $("#loader").removeClass("d-block");
        // $("#loader").addClass("d-none"); 
       
       
        $('#modals-slide-in').modal('show');
        
    });
    
    
    
    $(document).on('change', '#vehicle_id', function(e) {
        let oldCarrierPrice = $("#carrier_price").val();
        let vehicleId = $('#vehicle_id').val();
        let shipmentId = $('#shipment_id').val();
    
        if (!vehicleId || !shipmentId) {
            return;
        }
    
        let data = {
            'vehicle_id': vehicleId,
            'shipment_id': shipmentId,
        };
    
        // عرض رمز التحميل هنا إذا كنت بحاجة إليه
    
        $.ajax({
            url: config.routes.getCarrierPrice,
            method: 'get',
            data: data,
            dataType: 'json',
            success: function(response) {
                $("#carrier_price").val(response.price);
            },
            error: function(response) {
                $("#carrier_price").val(oldCarrierPrice);
            },
            complete: function() {
                // إخفاء رمز التحميل هنا إذا كنت بحاجة إليه
            }
        });
    });
    
});