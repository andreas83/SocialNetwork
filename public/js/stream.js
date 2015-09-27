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

});
