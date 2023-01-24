$(document).ready(function(){
    const generateCalendar = () => {
        const now = new Date();
        const numberOfDays = new Date(now.getFullYear(), now.getMonth()+1, 0).getDate();
    };
    generateCalendar();


    $(window).on('scroll', () => {
       if(window.scrollY>=170 && window.innerWidth<767){
        $('#calendarNavFixed').addClass("calendarNavFixedPhone");
        $('#calendarNavFixed').addClass("d-flex");
       }else if(window.scrollY>=150 && (window.innerWidth<996 && window.innerWidth>767)){
        $('#calendarNavFixed').addClass("calendarNavFixedOther");
        $('#calendarNavFixed').addClass("d-flex");
       }else{
        $('#calendarNavFixed').removeClass("calendarNavFixedPhone");
        $('#calendarNavFixed').removeClass("d-flex");
        $('#calendarNavFixed').removeClass("calendarNavFixedOther");
       }
    });


    const valueChange = (b) => {
        let shoppingListElement = $(b.target).parent().parent();
        let shoppingListElementValue;
        let nameValue = shoppingListElement;
        shoppingListElement = $(shoppingListElement).find(".ingredientGrammature");
        shoppingListElementValue = $(shoppingListElement).html();
        let valueStart = shoppingListElementValue;
        $(shoppingListElement).empty().append("<input class='shoppingListElementInput' type='text' name='newValue' value='"+shoppingListElementValue+"'/>").append("<input type='hidden' name='newValue2' value='"+$(nameValue).find(".ingredientName").html()+"'/>");
        $(shoppingListElement).parent().find(".actions").empty();
        $(shoppingListElement).parent().find(".actions").append('<div class="shoppingListElementSave shoppingListIngredientImg" style="background-image:url(http://przepisowo.epizy.com/wp-content/themes/przepisy/assets/img/check-lg.svg)"></div><div class="shoppingListElementCancel shoppingListIngredientImg" style="background-image:url(http://przepisowo.epizy.com/wp-content/themes/przepisy/assets/img/x-lg.svg)"></div>');
   
        $(".shoppingListElementSave").on("click", (c) => {
            $("#updateForm").submit();
            let shoppingListElementActions = c.target.parentElement;
            $(shoppingListElementActions).empty();
            $(shoppingListElementActions).append('<div class="shoppingListIngredientImg shoppingListEdit" style="background-image:url(http://przepisowo.epizy.com/wp-content/themes/przepisy/assets/img/pencil.svg)"></div><div class="shoppingListIngredientImg shoppingListDelete" style="background-image:url(http://przepisowo.epizy.com/wp-content/themes/przepisy/assets/img/x-lg.svg)"></div>');
            let shoppingListElementGrammature = $(shoppingListElementActions).parent().find(".shoppingListElementInput");
            let shoppingListElementValue = $(shoppingListElementGrammature).val();
            shoppingListElementGrammature = $(shoppingListElementGrammature).parent();
            $(shoppingListElementGrammature).empty();
            $(shoppingListElementGrammature).append(shoppingListElementValue);
            $(".shoppingListEdit").on("click", (d) => {
                valueChange(d);
             });
        });

        $(".shoppingListElementCancel").on("click", (a) => {
            let targetParent = $(a.target).parent();
            $(targetParent).empty();
            $(targetParent).append('<div class="shoppingListIngredientImg shoppingListEdit" style="background-image:url(http://przepisowo.epizy.com/wp-content/themes/przepisy/assets/img/pencil.svg)"></div><div class="shoppingListIngredientImg shoppingListDelete" style="background-image:url(http://przepisowo.epizy.com/wp-content/themes/przepisy/assets/img/x-lg.svg)"></div>');
            $(targetParent).parent().find(".ingredientGrammature").html(valueStart);
            $(".shoppingListEdit").on("click", (a) => {
                valueChange(a);
             });
         });
    }

    $(".shoppingListEdit").on("click", (a) => {
       valueChange(a);
    });

    $(".shoppingListDelete").on("click", (a) => {
        value = $(a.target).parent().parent().find('.ingredientName').html();
        $('#deleteInput').attr('value', value);
        $("#deleteForm").submit();
     });

})