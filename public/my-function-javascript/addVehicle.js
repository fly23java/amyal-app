

$(document).ready(function(){ 


    
    $(document).on('click', '#test_hima', function(e) {
        //get cover id
       
        $("#loader").removeClass("d-none"); 
        $("#loader").addClass("d-block"); 
        
        var id=$(this).data('id');
        var vehcil;
        var data={
            'id': id
        };
        $("#shipment_id").val(id);
     
           // start get data if you hava
        $.ajax({
            url: config.routes.getVehcile,
            method: 'get',
            dataType: 'json',
            success: function(response){
               $.each(response.Vehicle, function (i, item) {
                    $('#vehicle_id').append($('<option>', { 
                        value: item.id,
                        text : item.plate +' '+item.right_letter  +' '+item.middle_letter  +' '+item.left_letter 
                    }));
                   


                    $('#vehicle_id').select2();
                });
                
            },
            error: function(response) {
              
            }
        });
        // start get data if you hava
        $.ajax({
            url: config.routes.getDatahipmentdetails,
            method: 'get',
            dataType: 'json',
            data: data,
            success: function(response){
                vehcil = response.shipmentDeliveryDetail.vehicle_id;
                $("#shipment_delivery_detail_id").val(response.shipmentDeliveryDetail.id);
                $("#vehicle_id option").each(function(){
                    
                   
                    if($(this).val()== vehcil){ // EDITED THIS LINE
                        $(this).attr("selected","selected");    
                        $('#vehicle_id').select2();
                      
                    }
                   
                    if($.trim(response.shipment.carrier_price)){
                        $("#carrier_price").val(response.shipment.carrier_price);
                    }
                  
              
                });
           
            },
            error: function(response) {
                console.log('not data');
            }
        });

      
        
        
          
        $("#loader").removeClass("d-block");
        $("#loader").addClass("d-none"); 
       
       
        $('#modals-slide-in').modal('show');
        
    });
    
    
    
    $(document).on('change', '#vehicle_id', function(e) {
        // console.log('change');

        let  oldCarrierPrice = $("#carrier_price").val();
            var data = {
                'vehicle_id' : $('#vehicle_id').val(),
                'shipment_id' : $('#shipment_id').val(),
              
              
            };
          
            $("#loader").addClass("d-block"); 
            //  console.log(data);
            $.ajax({
                url: config.routes.getCarrierPrice,
                method: 'get',
                data: data,
                dataType: 'json',
                success: function(response){
                   $("#carrier_price").val(response.price);
                    
                },
                error: function(response) {
                    $("#carrier_price").val(oldCarrierPrice);
                }
            });
            
            $("#loader").addClass("d-none"); 
        });
});