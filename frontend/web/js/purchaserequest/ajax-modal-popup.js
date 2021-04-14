$("body").on("click", "#buttonCreatePR", function() {
    $('#createPRModal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
    $('#modalHeader').html($(this).attr('title'));
});