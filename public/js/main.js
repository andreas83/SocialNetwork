$( document ).ready(function() {
   
    
    $(".toggleform").on("click", function(e){
        e.preventDefault();
        $("#registerform").toggleClass("hide");
        $("#loginform").toggleClass("hide");
    });
});