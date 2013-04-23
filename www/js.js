 $(function() {
        $(document).ready(function() {
            $.get('treescript.php', function(data) {
                $('.myDiv').after("<br><br> " + data);
				

            });
            return false;
        });
    });

	
ids = new Array();
//this script loads branches dependent on id - has to be below first id ^

$(document).on("click", ".pheno" ,function(){
var id = $(this).attr("id");
if(jQuery.inArray(id,ids) == -1){	//checks if id is in array,
			ids.push(id);	 // adds id to array
			$.ajax({
			type: "POST",
			url: "treescript.php",
			data: { pheno: id}
			}).done(function( msg ) {
			$('#'+id).after(""+msg+"");

				});
			}
			else {
			$("."+id).empty();
			ids.splice(id);	//remvoes id from array

        }


});