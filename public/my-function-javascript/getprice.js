$(document).ready(function(){ 

// 

$(document).on('change', '#create_shipment_form', function(e) {
   
  
  
   
    if($('#account_id').val() && 
    $('#loading_city_id').val() &&
    $('#unloading_city_id').val() &&
    $('#vehicle_type_id').val() &&
    $('#goods_id').val() 
    ){
       var data = {
            'account_id' : $('#account_id').val(),
            'loading_city_id' : $('#loading_city_id').val(),
            'unloading_city_id' : $('#unloading_city_id').val(),
            'vehicle_type_id' : $('#vehicle_type_id').val(),
            'goods_id' : $('#goods_id').val(),
          
        };
      
        $.ajax({
            url:  config.routes.createShipmentForm,
            method: 'get',
            data: data,
            dataType: 'json',
            success: function(response){
              
                $("#price").val(response.price);
            },
            error: function(response) {
                
            }
        });
    }
});


$(document).on('change', '#edit_shipment_form', function(e) {
    
    if($('#account_id').val() && 
    $('#loading_city_id').val() &&
    $('#unloading_city_id').val() &&
    $('#vehicle_type_id').val() &&
    $('#goods_id').val() 
    ){
        
        var data = {
            'account_id' : $('#account_id').val(),
            'loading_city_id' : $('#loading_city_id').val(),
            'unloading_city_id' : $('#unloading_city_id').val(),
            'vehicle_type_id' : $('#vehicle_type_id').val(),
            'goods_id' : $('#goods_id').val(),
          
        };
      
        $.ajax({
            url:  config.routes.createShipmentForm,
            method: 'get',
            data: data,
            dataType: 'json',
            success: function(response){
              
                $("#price").val(response.price);
                
               
            },
            error: function(response) {
                $("#price").val('');
            }
        });
    }
});






 });