
$(document).ready(function(){
    if(window.innerWidth>=992){
        $('.nav-item:not(:first)').hide();
        $('#calendar-menu').removeClass("menuHidden");
        $('#calendar-menu').addClass("menuShown");
    }
    else{
        $('.nav-item:not(:first)').show();
        $('#calendar-menu').removeClass("menuShown");
        $('#calendar-menu').addClass("menuHidden");
       
    };
    
    $(window).on('resize', () => {
        if(window.innerWidth>=992){
            $('.nav-item:not(:first)').hide();
            $('#calendar-menu').removeClass("menuHidden");
            $('#calendar-menu').addClass("menuShown");
        }
        else{
            $('.nav-item:not(:first)').show();
            $('#calendar-menu').removeClass("menuShown");
            $('#calendar-menu').addClass("menuHidden");
           
        }
    });

    $('.logo-div').on("click", () => {
        window.location.replace("/")
      })
    
})