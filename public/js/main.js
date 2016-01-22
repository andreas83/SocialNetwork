$(document).ready(function () {
    
    var isMetaLoading = false;
    $("#share_area").on("input propertychange", function () {
        var urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
        if ($(this).val().match(urlRegex)) {
            url = $(this).val().match(urlRegex);
            if (isMetaLoading)
                return false;

            isMetaLoading = true;

            $.get('/api/metadata/?url=' + url, function (data) {

                if (data.type == "www")
                    renderWebPreview(data, url);
                if (data.type == "img")
                    renderImgPreview(url);
                if (data.type == "video")
                    renderVideoPreview(data);
                data.url = url[0];

                $("#metadata").val(JSON.stringify(data));
            });
        }
    });
    $(".close").click(function (e) {
        e.preventDefault();
        $(".preview").hide();
        $("#img").val("");
        $("#metadata").val("");
        isMetaLoading = false;
    });

    function readURL(input) {
        if (input.files && input.files[0]) {

            var files = input.files;

            for (var i = 0; i < files.length; i++)
            {
                var file = files[i];
                if (!file.type.match("image"))
                    continue;
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(".preview").hide();
                    $(".preview.upload").show();
                    var uploadPreview = "<img class=\"img-responsive\"  src=\"" + e.target.result + "\">";
                    $('#uploadPreview').append(uploadPreview);
                }
                reader.readAsDataURL(file);
            }



        }
    }

    $("#img").change(function () {
        readURL(this);
    });


    function renderWebPreview(data, url) {
        $(".preview").hide();
        $(".preview.www").show();
        $("#og_img").attr("src", data.og_img);
        $("#og_title").html(data.og_title);
        $("#og_desc").html(data.og_description);
        $("#www_link").html(url).attr("href", url);
        if ($("#share_area").val() == url)
            $("#share_area").val("");
    }
    function renderImgPreview(url) {
        $(".preview").hide();
        $(".preview.img").show();
        $("#preview_img").attr("src", url);
    }
    function renderVideoPreview(data) {
        $(".preview").hide();
        $(".preview.video").show();
        $("#video_target").html(data.html);
    }


    $("#search").find("input[type=text]").on("keyup", function ()
    {
        clearSearchResult();
        if($(this).val().length==0)
            return false;
        
        $.get('/api/hashtags/' + $(this).val().replace("#", ""), function (data) {
            $(data).each(function (res, d) {
                $("#search").find(".searchresult").append("<li>#" + d.hashtag + "</li>")

            });
            $("#search").find(".searchresult li").click(function () {
                
                if(user_settings==false && $(this).text().replace("#", "")=="nsfw")
                {
                    var info='\
                        <div className="content">\
                            You need to be over +18 to watch nsfw content, \
                            please <a href="/user/register/">register here.</a>\
                        </div>';
                    
                    $(".stream").html(info);
                    return false;
                }
                
                $.post('/api/hashtag/score/' + $(this).text().replace("#", ""), function( data ) {
                    console.log(data);
                });
                
                if ($(".frontpage").length > 0)
                {
                    window.location.href = "/hash/" + $(this).text().replace("#", "");
                    return true;
                }
                clearSearchResult();
                clearStream();

                //set input from search res
                $("#search").find("input[type=text]").val($(this).text());

                
                var container = document.getElementsByClassName('stream')[0];

                var component = React.createElement(InitStream, {hashtag: $(this).text()});
                React.render(component, container);


            });
        });
    });
    
    $("#next").on("click", function(){
        //redirect to /random
        var url = window.location.pathname.split( '/' );
        
        if(url[1]!="random")
        {
            window.location.href="/random/";
            return true;
        }
        clearStream();
        var container = document.getElementsByClassName('stream')[0];
        var component = React.createElement(InitStream, {random: parseInt($(".stream-row").attr("data-random"))});

        React.render(component, container);
        
    });

    $("#search").on("submit", function (e) {
        e.preventDefault();
        if ($(".frontpage").length > 0)
        {
            window.location.href = "/hash/" + $("#search").find("input[type=text]").val().replace("#", "");
            return true;
        }
        clearSearchResult();
        clearStream();
        var container = document.getElementsByClassName('stream')[0];
        var component = React.createElement(InitStream, {hashtag: $("#search").find("input[type=text]").val()});

        React.render(component, container);
    });

    function clearSearchResult() {
        $("#search").find(".searchresult").html("");
    }

    function clearStream() {
        React.unmountComponentAtNode(document.getElementsByClassName('stream')[0]);
        $(".stream").html("");
    }

    $(".toggleform").on("click", function (e) {
        e.preventDefault();
        $("#registerform").toggleClass("hide");
        $("#loginform").toggleClass("hide");
    });
    
    
    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }
        return "";
    }
    function prettyDate (time){
	var date = new Date(time*1000),
		diff = (((new Date()).getTime() - date.getTime()) / 1000),
		day_diff = Math.floor(diff / 86400);
			
	if ( isNaN(day_diff) || day_diff < 0 || day_diff >= 31 )
		return;
			
	return day_diff == 0 && (
			diff < 60 && "just now" ||
			diff < 120 && "1 minute ago" ||
			diff < 3600 && Math.floor( diff / 60 ) + " minutes ago" ||
			diff < 7200 && "1 hour ago" ||
			diff < 86400 && Math.floor( diff / 3600 ) + " hours ago") ||
		day_diff == 1 && "Yesterday" ||
		day_diff < 7 && day_diff + " days ago" ||
		day_diff < 31 && Math.ceil( day_diff / 7 ) + " weeks ago";
    }

    
    
    //start notification block
    var socket;

    
    
    
    try {
            socket = new WebSocket(notification_server);
            
            socket.onopen    = function(msg) { 
                socket.send(JSON.stringify({action: "getNotifications", auth_cookie:getCookie("auth")}));
            };
            socket.onmessage = function(msg) { 
                data=JSON.parse(msg.data);
                
                $(data).each(function(key){
                    
                    if(typeof JSON.parse(data[key].settings).profile_picture !=="undefined")
                        profile_pic='<img  src='+upload_address+JSON.parse(data[key].settings).profile_picture+'>';
                    else
                        profile_pic='<img  src=/public/img/no-profile.jpg>';
                    
                    safe_username=data[key].name.replace(" ", ".")
                    user_link_pic='<a href="/'+safe_username+'">'+profile_pic+'</a>';
                    user_link='<a href="/'+safe_username+'">'+data[key].name+'</a>';
                    
                    $("#notifications").append("\
                    <li class=list-group-item>"+user_link_pic+" \n\
                        "+data[key].message+"<br/>\n\
                        "+user_link+ " "+prettyDate(parseInt(data[key].date))+"\
                    </li>");
                });
            };
            socket.onclose   = function(msg) { 
                $("#notifications").html("Disconected from notification server");
                 
            };
    }
    catch(ex){ 
            $("#notifications").text(ex);
            //console.log(ex); 
    }
    $("#custom_css").html($("#custom_css_input").val());
    $("#custom_css_input").on("keyup", function(){
        $("#custom_css").html($(this).val());
    })
    
    
    
});
