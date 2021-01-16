function test(id, valueAlt) {
            $.ajax({
                url: '/../ajaxUpdateAlt',
                method: 'POST',
                dataType: 'text',
                data: {
                    postAlt: 1,
                    idImg: id,
                    valAlt: valueAlt
                }, 
                 success : function(){
             $('#notification').show();
             $( "#notification" ).hide(4000);
        },
        error : function(result, statut, erreur){
            console.log('sortCategCatalogue > ERREUR AJAX');
        }
    });    
}
