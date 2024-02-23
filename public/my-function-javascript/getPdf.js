

$(document).ready(function(){ 


    
    $(document).on('click', '#get_pdf', function(e) {
  

 

              var arabicSection = document.getElementById('print');

              // Configure the PDF settings
              html2canvas(arabicSection)
              .then(canvas => {
                  // Convert the canvas to data URL
                  var imgData = canvas.toDataURL('image/jpeg');
      
                  // Create a PDF document
                  var pdfDoc = new pdfjs.Document({ compress: true });
      
                  // Add an image to the PDF document
                  pdfDoc.image(imgData, 10, 10, { width: 180 });
      
                  // Save or display the PDF
                  pdfDoc.save('arabic_document.pdf');
              });
    });
});
    
    
   
