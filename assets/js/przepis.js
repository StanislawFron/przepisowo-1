$(document).ready(function(){
    $('#addIngredientForm').on("click", (e) => {
        e.preventDefault();
        let ingredientLength = document.getElementsByClassName('ingredient').length;
        if(ingredientLength<20){
            $('.ingredientDiv').append('<div class="ingredient"><input class="m-2" type="text" placeholder="składnik" name="ingredientName'+(ingredientLength+1)+'"/><input class="m-2" type="text" placeholder="ilosc" name="ingredientAmount'+(ingredientLength+1)+'"/></div>')
        }
    })

    $('#deleteIngredientForm').on("click", (e) => {
        e.preventDefault();
        if(document.getElementsByClassName('ingredient').length>1){
            $(".ingredient:last").remove();
        }
    })

    $('#addPreparingStep').on("click", (e) => {
        e.preventDefault();
        let preparingStepLength = document.getElementsByClassName('preparingStep').length;
        if(preparingStepLength<20){
            $('.preparingDiv').append('<div class="preparingStep m-2"><input type="text" name="preparingStep'+(preparingStepLength+1)+'"/></div>')
        }
    })

    $('#deletePreparingStep').on("click", (e) => {
        e.preventDefault();
        if(document.getElementsByClassName('preparingStep').length>1){
            $(".preparingStep:last").remove();
        }
    })
})