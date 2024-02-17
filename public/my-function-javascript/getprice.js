$(document).ready(function(){ 

// 

$(document).on('change', '#create_shipment_form', function(e) {
   
   console.log('true');
  
   
    if($('#user_id').val() && 
    $('#loading_city_id').val() &&
    $('#unloading_city_id').val() &&
    $('#vehicle_type_id').val() &&
    $('#goods_id').val() 
    ){
       var data = {
            'user_id' : $('#user_id').val(),
            'loading_city_id' : $('#loading_city_id').val(),
            'unloading_city_id' : $('#unloading_city_id').val(),
            'vehicle_type_id' : $('#vehicle_type_id').val(),
            'goods_id' : $('#goods_id').val(),
          
        };
      
        $.ajax({
            url:  config.routes.createPhipmentForm,
            method: 'get',
            data: data,
            dataType: 'json',
            success: function(response){
                console.log(response);
                $("#price").val(response.price);
            },
            error: function(response) {
                $("#price").val('');
            }
        });
    }
});


$(document).on('change', '#edit_shipment_form', function(e) {
    
    if($('#user_id').val() && 
    $('#loading_city_id').val() &&
    $('#unloading_city_id').val() &&
    $('#vehicle_type_id').val() &&
    $('#goods_id').val() 
    ){
        
        var data = {
            'user_id' : $('#user_id').val(),
            'loading_city_id' : $('#loading_city_id').val(),
            'unloading_city_id' : $('#unloading_city_id').val(),
            'vehicle_type_id' : $('#vehicle_type_id').val(),
            'goods_id' : $('#goods_id').val(),
          
        };
      
        $.ajax({
            url:  config.routes.createPhipmentForm,
            method: 'get',
            data: data,
            dataType: 'json',
            success: function(response){
                console.log(response);
                $("#price").val(response.price);
                
               
            },
            error: function(response) {
                $("#price").val('');
            }
        });
    }
});






 });