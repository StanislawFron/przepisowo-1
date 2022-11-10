
$(document).ready(function(){
  
  for(x=0;x<skladnikiBasic.length;x++){
    $('.ingredients').append('<div class="ingredient"><label class="ingredient-label d-flex justify-content-between align-items-center pl-lg-5 pr-lg-5 pl-md-2 pr-md-2 pr-sm-5 pl-sm-5">'+skladnikiBasic[x]+'<input type="checkbox" class="checkbox" value="'+skladnikiBasic[x].toLowerCase()+'"><input id="'+skladnikiBasic[x].toLowerCase()+'" type="hidden" value="2" name="'+skladnikiBasic[x].toLowerCase()+'"/></label></div>')
  }

    const ingredients = document.querySelectorAll(".ingredient");
    const ingredientResult = document.querySelectorAll(".ingredientResult");

    if(window.history.replaceState) { 
      window.history.replaceState( null, null, window.location.href ); 
    
    }


    $('#checkAll').on('click', (e) => {
      e.preventDefault();
      document.querySelectorAll(".checkbox").forEach((el) => {
        $(el).data('checked', 1);
        $(el).prop('indeterminate', false);
        $(el).prop('checked', true);
        window.localStorage.setItem($(el).attr("value"), '1');
        $(el).parent().find("input:last").attr("value", 1);
      })
    })

    $('#uncheckAll').on('click', (e) => {
      e.preventDefault();
      document.querySelectorAll(".checkbox").forEach((el) => {
        $(el).data('checked', 2);
        $(el).prop('indeterminate', false);
        $(el).prop('checked', false);
        window.localStorage.setItem($(el).attr("value"), '2');
        $(el).parent().find("input:last").attr("value", 2);
      })
    })

    $('#exceptAll').on('click', (e) => {
      e.preventDefault();
      document.querySelectorAll(".checkbox").forEach((el) => {
        $(el).data('checked', 0);
        $(el).prop('indeterminate', true);
        $(el).prop('checked', false);
        window.localStorage.setItem($(el).attr("value"), '0');
        $(el).parent().find("input:last").attr("value", 0);
      })
    })

    if(window.localStorage.getItem("hideShowBtn")==0){
      $('.ingredient, .form-search-input, .smallBtn').hide();
      $('#hideShowBtn').html("POKAŻ");
    }

    $("#form-search-input").keyup((a) => {
        let searchValue = a.target.value;
        ingredients.forEach((a) => {
            let label = $(a).find("label");
            let label_text = $(label).html().toLowerCase();
             if(label_text.includes(searchValue.toLowerCase())){
                $(a).show()
             }
             else{
                $(a).hide()
             }
         })
    }); 

    $("#form-result-input").keyup((a) => {
      let searchValue = a.target.value;
      ingredientResult.forEach((a) => {
          let label = $(a).find(".ingredientName");
          console.log(label)
          let label_text = $(label).html().toLowerCase();
           if(label_text.includes(searchValue.toLowerCase())){
              $(a).show()
           }
           else{
              $(a).hide()
           }
       })
  }); 


    $('#hideShowBtn').on("click", (e) => {
      e.preventDefault();
      if($('.form-search-input').is(":visible")){
        $('.ingredient, .form-search-input, .smallBtn').slideUp(800);
        window.localStorage.setItem("hideShowBtn", '0');
        $('#hideShowBtn').html("POKAŻ");
      }else{
        $('.ingredient, .form-search-input, .smallBtn').slideDown(800);
        window.localStorage.setItem("hideShowBtn", '1');
        $('#hideShowBtn').html("SCHOWAJ");
      }
    })

    const loadChecked = () => {
      document.querySelectorAll(".checkbox").forEach((el) => {
        if(window.localStorage.getItem($(el).attr("value")) == 0){
          $(el).data('checked', 0);
          $(el).prop('indeterminate', true);
          $(el).prop('checked', false);
          $(el).parent().find("input:last").attr("value", 0);
        }
        else if(window.localStorage.getItem($(el).attr("value")) == 1){
          $(el).data('checked', 1);
          $(el).prop('indeterminate', false);
          $(el).prop('checked', true);
          $(el).parent().find("input:last").attr("value", 1);
        }
        else if(window.localStorage.getItem($(el).attr("value")) == 2){
          $(el).data('checked', 2);
          $(el).prop('indeterminate', false);
          $(el).prop('checked', false);
          $(el).parent().find("input:last").attr("value", 2);
        }
      })
    }

    loadChecked();
  

    $(".checkbox")
    .click(function() {
      var el = $(this);
  
      switch (el.data('checked')) {
  
        // unchecked, going indeterminate
        case 0:
          el.data('checked', 2);
          el.prop('indeterminate', false);
          el.prop('checked', false);
          window.localStorage.setItem($(el).attr("value"), '2');
          break;
  
          // indeterminate, going checked
        case 1:
          el.data('checked', 0);
          el.prop('indeterminate', true);
          el.prop('checked', false);
          window.localStorage.setItem($(el).attr("value"), '0');
          break;
  
          // checked, going unchecked
        default:
          el.data('checked', 1);
          el.prop('indeterminate', false);
          el.prop('checked', true);
          window.localStorage.setItem($(el).attr("value"), '1');
      }
      $(el).parent().find("input:last").attr('value', parseInt(el.data().checked));
    });
});
