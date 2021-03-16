//INDEX
$( document ).ready(function() {
$('#sortableCateg').sortable({
    update: function (event, ui) {
    $(this).children().each(function (index) {
         if($(this).attr('data-pos') != (index+1)){
            $(this).attr('data-pos', (index+1)).addClass('updated');
         }
    });
    saveNewPositionsCategActu();
    }
});
});

function saveNewPositionsCategActu() {
            var positions = [];
            $('.updated').each(function () {
               positions.push([$(this).attr('data-index'), $(this).attr('data-pos')]);
               $(this).removeClass('updated');
            });

            $.ajax({
               url: 'ajaxSortCategorieActu',
               method: 'POST',
               dataType: 'text',
               data: {
                   updateOrderActu: 1,
                   positions: positions
               }, 
                success : function(jsonResult, statut){
            const result = JSON.parse(jsonResult);
            if(result.status && result.flashMessage !== 'undefined'){
                $('#flashMessageAjax').html(result.flashMessage);
            }
        },
        error : function(result, statut, erreur){
            console.log('sortCategCatalogue > ERREUR AJAX');
        }
    });     
}


function addNewcateg() {
$('#openModal').show();
        $.ajax({
               url: 'actualite',
               method: 'POST',
               dataType: 'text',
               data: {
                   addCateg: 1,
               }, success: function (response) {
               }
            });
}



//ADD MEDIAS
function updateAlt(id, valueAlt) {
            $.ajax({
                url: '../../ajaxUpdateAltActu',
                method: 'POST',
                dataType: 'text',
                data: {
                    postAlt: 1,
                    idImg: id,
                    valAlt: valueAlt
                }, 
                 success : function(jsonResult, statut){
            const result = JSON.parse(jsonResult);
            if(result.status && result.flashMessage !== 'undefined'){
                $('#flashMessageAjax').html(result.flashMessage);
            }
        },
        error : function(result, statut, erreur){
            console.log('sortCategCatalogue > ERREUR AJAX');
        }
    });    
}
        
        
        $( document ).ready(function() {
            $('#sortable').sortable({
                update: function (event, ui) {
                $(this).children().each(function (index) {
                     if($(this).attr('data-position') != (index+1)){
                        $(this).attr('data-position', (index+1)).addClass('updated');
                     }
                });
                saveNewPositions();
                }
            });
        });

        function saveNewPositions() {
            var positions = [];
            $('.updated').each(function () {
               positions.push([$(this).attr('data-index'), $(this).attr('data-position')]);
               $(this).removeClass('updated');
            });

            $.ajax({
               url: '../../ajaxSortMediasActu',
               method: 'POST',
               dataType: 'text',
               data: {
                   updateOrderMediasActu: 1,
                   positions: positions
               },
               success : function(jsonResult, statut){
            const result = JSON.parse(jsonResult);
            if(result.status && result.flashMessage !== 'undefined'){
                $('#flashMessageAjax').html(result.flashMessage);
            }
        },
        error : function(result, statut, erreur){
            console.log('sortCategCatalogue > ERREUR AJAX');
        }
    });    
}