$(function(){
			  var patentes = [ 

			   {value: "sss222" , data: " 21-04-2016 05:05:38 " }, 
 {value: "kji888" , data: " 21-04-2016 05:09:22 " }, 
 {value: "dsa111" , data: " 25-04-2016 02:16:11 " }, 

			   
			  ];
			  
			  // setup autocomplete function pulling from patentes[] array
			  $('#autocomplete').autocomplete({
			    lookup: patentes,
			    onSelect: function (suggestion) {
			      var thehtml = '<strong>patente: </strong> ' + suggestion.value + ' <br> <strong>ingreso: </strong> ' + suggestion.data;
			      $('#outputcontent').html(thehtml);
			         $('#botonIngreso').css('display','none');
      						console.log('aca llego');
			    }
			  });
			  

			});