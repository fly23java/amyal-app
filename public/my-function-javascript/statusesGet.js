

$(document).ready(function(){ 

        

        $(document).on('click', '#statuses_edit', function(e) {
            var id=$(this).data('id');
           
            var data={
                'id': id
            };
                  
                    $.ajax({
                        url:  config.routes.statusesGet,
                        method: 'get',
                        data: data,
                        dataType: 'json',
                        success: function(response){
                            console.log(response);
                                
                            $.each(response.Status, 
                                function (i, item) {
                                        $('#status_id').append($('<option>', { 
                                            value: item.id,
                                            text : item.name_arabic,
                                        }));



                            }
                            );
                            $("#status_id option").each(function(){
                    
                   
                                if($(this).val()== response.shipment.status_id){ // EDITED THIS LINE
                                    $(this).attr("selected","selected");    
                                   
                                  
                                }});

                                $("#shipment_status_id").val(response.shipment.id);
                           
                        },
                        error: function(response) {
                           
                        }
                    });
        });
});