$(document).ready(function() {
  if ($("#view-chart").length > 0) {
    var ctx = document.getElementById("view-chart").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
        datasets: [{
          label: '# of Votes',
          data: [12, 19, 3, 5, 2, 3],
          backgroundColor: [
            'rgba(36, 109, 248, .2)'
          ],
          borderColor: [
            'rgba(36, 109, 248, 1 )'
          ],
          borderWidth: 1
        }]
      },
      options: {
        title: {
          display: false
        },
        gridLines: {
          display: false
        },
        legend: {
          display: false
        },
        tooltips: {
          mode: 'index',
          intersect: true
        },
        responsive: true,
        scales: {
          xAxes: [{
            stacked: true,
          }],
          yAxes: [{
            stacked: true,
          }]
        }
      }
    });
  }

	/*---------------------------------------------
		Dashboard
	---------------------------------------------*/

	$('.upload-profile-photo .file-input').change(function(){
	    var curElement = $(this).parent().parent().find('.image');
	    var reader = new FileReader();

	    reader.onload = function (e) {
	        // get loaded data and render thumbnail.
	        curElement.attr('src', e.target.result);
	    };

	    // read the image file as a data URL.
	    reader.readAsDataURL(this.files[0]);
	});

	$('.send-file .file-input').change(function(){
	    var curElement = $(this).parent().parent().find('.image');
	    var reader = new FileReader();

	    reader.onload = function (e) {
	        // get loaded data and render thumbnail.
	        curElement.attr('src', e.target.result);
	    };

	    // read the image file as a data URL.
	    reader.readAsDataURL(this.files[0]);
	});


  /*-------------------------------------
    tooltip
  -------------------------------------*/

  $('.user-number i').tooltip();

})
// custom date input
$('.date').bind('keyup',function(event){
  var key = event.keyCode || event.charCode || event.which;
   var dateVal = $(this).val();
   var dateVal__ = dateVal.slice(0, -1);
   var lastKey = dateVal.slice(-1);
   var length = dateVal.length;
   var daysInMonth = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
   
   if ((key == 8 || key == 46) && (length == 5 || length == 2)) {// Deleting on a '/' deletes the '/'
     $(this).val(dateVal__);
   }
   if ((key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {// If key is a number allow data input
     if ((length === 4 && lastKey > 3) || (length === 1 && lastKey > 1)) {
       dateVal = dateVal__ + '0' + lastKey;
       length++;
     }
     if (length === 2 && (dateVal.split('')[0] === '1' && lastKey > 2)) {
       dateVal = dateVal__ + '2';
     }
     if (length === 5) {// Checks to compare the month vs the day value
       var monthVal = dateVal.slice(0,2);
       var dayVal = dateVal.slice(-2);
       if (dayVal > daysInMonth[monthVal - 1]) {
         dayVal = daysInMonth[monthVal - 1];
         dateVal = dateVal.slice(0, -2).toString() + dayVal;
       }
     }
     if (length === 2 || length === 5) {
       dateVal += '/';
       $(this).val(dateVal);
     }
   }
   else if (key >= 65 && key <= 90 && key !== 86 || key > 105) {// Else if key is a non-numeric value, remove it
     $(this).val(dateVal__);
   }
 });
 
 $('.date').bind('blur',function(){
   $(this).attr('placeholder', '');
   var $element = $(this).val();
   var yyForToday = new Date().getFullYear().toString().substr(2,2);
   if ($element.length == 8) {
     var century = ($element.slice(-2) > yyForToday) ? 19 : 20;
     $element = $element.slice(0, -2) + century + $element.slice(-2);
     $(this).val($element);
   }
   // Add a span element describing the error for the user
   if ($element.length < 10 && $element.length !== 0) {
     $(this).parent().find('*').css({ 'border-color': 'red', 'color': 'red' });
     $(this).parent().parent().append('<span class="dateError" style="color:red">*dates are entered in mm/dd/yyyy format</span>');
   }
   if ($element.length == 6) {
     $(this).parent().parent().append('<br/><span class="dateError" style="color:red">*a year was not entered</span>');
   }
   $(this).on('focus', function () {
     $(this).parent().find('*').css({ 'border-color': '', 'color': '' });
     $(this).parent().parent().find('span.dateError').remove();
   })
 });
 (function ($) {
  $(document).ready(function () {
    
    uploadImage()
    
    function uploadImage() {
      var button = $('.upload-images .pic')
      var uploader = $('<input type="file" accept="image/*" />')
      var images = $('.upload-images')
      
      button.on('click', function () {
        uploader.click()
      })
      
      uploader.on('change', function () {
          var reader = new FileReader()
          reader.onload = function(event) {
            images.prepend('<div class="img" style="background-image: url(\'' + event.target.result + '\');" rel="'+ event.target.result  +'"><span class="ti-close"></span></div>')
          }
          reader.readAsDataURL(uploader[0].files[0])
  
       })
      
      images.on('click', '.img', function () {
        $(this).remove()
      })
    
    }
    

  })
})(jQuery)