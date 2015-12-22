$(document).ready(function () {

    
    $("#resttest").submit(function(e){
        e.preventDefault();
        var param=$("#resttest").not("#url").find("input:visible").serialize();
        
        $.ajax({
           url: $("#url").val(),
           method: $("#method").val(),
           data:param
                   
        }).done(function( html ) {
            $( "#result" ).append( html );
        });
        
    });
    
    
    hljs.initHighlightingOnLoad();

});

