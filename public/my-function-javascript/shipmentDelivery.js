

$(document).ready(function(){ 


    
    $(document).on('click', '#shipment_delivery', function(e) {
        //get cover id
       
        // $("#loader").removeClass("d-none"); 
        // $("#loader").addClass("d-block"); 
        
        var id=$(this).data('id');
        var vehcil;
        var data={
            'id': id
        };
        $("#shipment_delivery_shipment_id").val(id);
     
           // start get data if you hava
        $.ajax({
            url: config.routes.shipmentDetails,
            method: 'get',
            data: data,
            dataType: 'json',
            success: function(response){
               console.log(response.shipmentDeliveryDetail);

               if($.trim(response.shipmentDeliveryDetail.loading_time)){
                $("#loading_time").val(response.shipmentDeliveryDetail.loading_time);
                }
               if($.trim(response.shipmentDeliveryDetail.unloading_time)){
                $("#unloading_time").val(response.shipmentDeliveryDetail.unloading_time);
                }
               if($.trim(response.shipmentDeliveryDetail.arrival_time)){
                $("#arrival_time").val(response.shipmentDeliveryDetail.arrival_time);
                }
               if($.trim(response.shipmentDeliveryDetail.departure_time)){
                $("#departure_time").val(response.shipmentDeliveryDetail.departure_time);
                }
              
                $("#shipment_delivery_shipment_id").val(response.shipmentDeliveryDetail.shipment_id);
            },
            error: function(response) {
              
            }
        });
        
    });
    
    
  
});