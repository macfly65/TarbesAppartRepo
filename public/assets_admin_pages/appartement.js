//ADD MEDIAS
function updateAlt(id, valueAlt) {
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
        url: '../../ajaxSortMediasAppart',
        method: 'POST',
        dataType: 'text',
        data: {
            updateOrderMediasAppart: 1,
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