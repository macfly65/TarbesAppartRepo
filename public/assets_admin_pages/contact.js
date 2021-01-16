$( document ).ready(function() {
    setTimeout(function() {
                $("#flashMsg").children().each(function(){ 
                     $(this).remove();
                });     
            }, 3000);       
});
        
