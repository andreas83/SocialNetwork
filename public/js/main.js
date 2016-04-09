 $(document).ready(function () {
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            $(".fileinfo").html(label);
        input.trigger('fileselect', [numFiles, label]);
    });
    
    

    function readURL(input) {
        if (input.files && input.files[0]) {

            var files = input.files;
            $('#uploadPreview').html("");
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

    $(".showGroup").on("click", function (e) {
        e.preventDefault();
        
        React.unmountComponentAtNode(container);
        
        
    });

    $("#MobileMenu").on("click", function (e) {
        e.preventDefault();
        $(".menuLeft").toggleClass("hidden-xs");
        $(".menuLeft").toggleClass("mobile");
        
    });
    
    $(".toggleform").on("click", function (e) {
        e.preventDefault();
        $("#registerform").toggleClass("hide");
        $("#loginform").toggleClass("hide");
    });
    
    $("#passsword_reset").click(function(){
        $("#registerform").addClass("hide");
        $("#loginform").addClass("hide");
        $(this).hide();
        $("#password_reset_form").removeClass("hide");
    });




    $("#custom_css").html($("#custom_css_input").val());
    $("#custom_css_input").on("keyup", function () {
        $("#custom_css").html($(this).val());
    })



});


function Replacehashtags(string) {
    string = string.replace(/#(\S*)/g, '<a class="hash" href="/hash/$1">#$1</a>');
    string = string.replace(/@(\S*)/g, '<a class="user" href="/$1">@$1</a>');

    return string;
}

function prettyDate(time) {
    var date = new Date(time * 1000),
            diff = (((new Date()).getTime() - date.getTime()) / 1000),
            day_diff = Math.floor(diff / 86400);

    if (isNaN(day_diff) || day_diff < 0 || day_diff >= 31)
        return;

    return day_diff == 0 && (
            diff < 60 && "just now" ||
            diff < 120 && "1 minute ago" ||
            diff < 3600 && Math.floor(diff / 60) + " minutes ago" ||
            diff < 7200 && "1 hour ago" ||
            diff < 86400 && Math.floor(diff / 3600) + " hours ago") ||
            day_diff == 1 && "Yesterday" ||
            day_diff < 7 && day_diff + " days ago" ||
            day_diff < 31 && Math.ceil(day_diff / 7) + " weeks ago";
}


function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1);
        if (c.indexOf(name) == 0)
            return c.substring(name.length, c.length);
    }
    return "";
}


function swap(json) {
    var ret = {};
    for (var key in json) {
        ret[json[key]] = key;
    }
    return ret;
}

function bindMention(){
        $('textarea').textcomplete('destroy');

        $('textarea').textcomplete([
        { // mention strategy
          match: /(^|\s)#(\w*)$/,
          search: function (term, callback) {

            $.getJSON('/api/hashtags/'+term)
              .done(function (resp) { 
                  var data=[];
                  $(resp).each(function(key, val){
                      data.push(val.hashtag);
                  });
                  callback(data);
                })
              .fail(function ()     { callback([]);   });
          },
          replace: function (value) {

            return '$1#' + value + ' ';
          },
          cache: true
        },
        { // mention strategy
          match: /(^|\s)@(\w*)$/,
          search: function (term, callback) {

            $.getJSON('/api/users/'+term)
              .done(function (resp) { 
                  var data=[];
                  $(resp).each(function(key, val){
                      settings=JSON.parse(val.settings);

                      data.push([val.name, settings.profile_picture]);
                  });
                  callback(data);
                })
              .fail(function ()     { callback([]);   });
          },
          template: function (value) {

              var img;
              if(typeof(value[1])!="undefined")
              {
                  img='<img  width="20" src="'+upload_address+value[1] + '"></img>';
              }
              else{
                  img='<img width="20" src=/public/img/no-profile.jpg>';
              }
                return img+'  ' + value[0];
        },
          replace: function (value) {

            return '$1@' + value[0].replace(" ", ".") + ' ';
          },
          cache: true
        },

      ], { maxCount: 20, debounce: 500 });
    }