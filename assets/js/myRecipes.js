$(document).ready(function(){
    $('.myRecipesEditBtn').on('click', (a) => {
        let newValue = $(a.target).parent().parent().find('.ingredientName').html();
        $('#editInput').val(newValue);
        $('#editOrDelete').val('edit');
    })

    $('.deleteButton').on('click', (a) => {
        let newValue = $(a.target).parent().parent().find('.ingredientName').html();
        $('#editInput').val(newValue);
        $('#editOrDelete').val('delete');
    })
})