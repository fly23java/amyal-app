

$(document).ready(function(){ 


    
    $(document).on('click', '#get_pdf', function(e) {
        //get cover id
       
      
        // var id=$(this).data('id');
        // var vehcil;
        // var data={
        //     'id': id
        // };
        // $("#shipment_id").val(id);
     
           // start get data if you hava
        
              
            //     var htmlContent = document.getElementById('print').innerHTML;
                
            // //   var htmlContent = response.pdfContant.html();
            // //   console.log(htmlContent);
            //   // Configuration for pdf generation
            //   var pdfOptions = {
            //      margin: 0,
            //      filename: 'your-pdf-file.pdf',
            //      image: { type: 'jpeg', quality: 0.98 },
            //      html2canvas: { scale: 1 },
            //      jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            //   };
        
            //   // Generate PDF using html2pdf library
            // //   html2pdf().from(htmlContent).toCanvas().save();
            // html2pdf().from(htmlContent).set(pdfOptions).save();
                
            var printContents = document.getElementById("print").innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;

        });
       
        
    });
    
    
   
