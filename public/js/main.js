$(document).ready(function () {
    
    var isMetaLoading = false;
    $("#share_area").on("keyup", function () {
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
        $.get('/api/hashtags/' + $(this).val().replace("#", ""), function (data) {
            $(data).each(function (res, d) {
                $("#search").find(".searchresult").append("<li>#" + d.hashtag + "</li>")

            });
            $("#search").find(".searchresult li").click(function () {
                if ($(".frontpage").length > 0)
                {
                    window.location.href = "/hash/" + $(this).text().replace("#", "");
                    return true;
                }
                clearSearchResult();
                clearStream();

                //set input from search res
                $("#search").find("input[type=text]").val($(this).text());

                //cnt click 
                var container = document.getElementsByClassName('stream')[0];

                var component = React.createElement(StreamBox, {hashtag: $(this).text()});
                React.render(component, container);


            });
        });
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
        var component = React.createElement(StreamBox, {hashtag: $("#search").find("input[type=text]").val()});

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
});