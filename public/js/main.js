$( document ).ready(function() {
   


    $("#search").find("input[type=text]").on("keyup", function()
    {
        clearSearchResult();
        $.get('/api/hashtags/'+$(this).val().replace("#", ""), function (data) {
            $(data).each(function(res, d){
                $("#search").find(".searchresult").append("<li>#"+d.hashtag+"</li>")
               
            });
            $("#search").find(".searchresult li").click(function(){
               if($(".frontpage").length>0)
               {
                   window.location.href="/hash/"+$(this).text().replace("#", "");
                   return true;
               }
               clearSearchResult();
               clearStream();
               
               //set input from search res
               $("#search").find("input[type=text]").val($(this).text());
               
               //cnt click 
               var container = document.getElementsByClassName('stream')[0];
               
               var component = React.createElement(StreamBox, {hashtag:$(this).text()});
               React.render(component, container);
               
               
            });
        });
    });
    
    $("#search").on("submit", function(e){
        e.preventDefault();
        if($(".frontpage").length>0)
        {
            window.location.href="/hash/"+$("#search").find("input[type=text]").val().replace("#", "");
            return true;
        }
        clearSearchResult();
        clearStream();
        var container = document.getElementsByClassName('stream')[0];
        var component = React.createElement(StreamBox, {hashtag:$("#search").find("input[type=text]").val()});
        
        React.render(component, container);
    });
    
    function clearSearchResult(){
        $("#search").find(".searchresult").html("");
    }
    
    function clearStream(){
        React.unmountComponentAtNode(document.getElementsByClassName('stream')[0]);
        $(".stream").html("");
    }
    
    $(".toggleform").on("click", function(e){
        e.preventDefault();
        $("#registerform").toggleClass("hide");
        $("#loginform").toggleClass("hide");
    });
});