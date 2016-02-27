
    function Replacehashtags(string){
    
        return string.replace(/#(\S*)/g,'<a class="hash" href="/hash/$1">#$1</a>');
    }
    
    var InitStream  = React.createClass({
        getInitialState: function () {
           
           return {data:[]}
            
        },
        componentDidMount: function () {
            this.loadStreamFromServer();
            
            document.addEventListener('scroll', this.handleScroll);
            
            //@todo better soloution would be to save the complete data as state
            window.onpopstate = (event) => {
                window.location.href=event.state.url;
            };


            
        },
        componentWillUnmount() {
            document.removeEventListener('scroll', this.handleScroll);
        },
        
        loadStreamFromServer: function () {
            
            var hash="";
            var user="";

            var show =5;
            var lastid="";


            if (this.id>0 || typeof(id)=="undefined")
            {
                this.setID(parseInt($(".stream-item").last().attr("data-id")));
            }
            if($(".stream-row").attr("data-permalink")>0)
            {
                
                this.setID(parseInt($(".stream-row").attr("data-permalink"))+1);
                show = 1;
                this.setState({
                        endofData:true,
                    });
            }
            if($(".stream-row").attr("data-random")>0)
            {
                function getRandomInt(min, max) {
                    return Math.floor(Math.random() * (max - min + 1)) + min;
                }
                this.setID(getRandomInt(1, parseInt($(".stream-row").attr("data-random"))+1));
                
        
                
                show = 1;
                this.setState({
                        endofData:true,
                        random:true
                    });
            }

            
            
            if($(".stream-row").attr("data-hash")!="")
            {
                hash=$(".stream-row").attr("data-hash");
            } 
            

            if($(".stream-row").attr("data-user")!="")
            {
                user=$(".stream-row").attr("data-user");
            } 

            if(typeof this.props.hashtag !="undefined")
            {
                //we do a full page load
                //when the search is called via /user or /permalink
                //reson: url reflect content
               
                if(show==1 || user!="")
                    window.location.href="/hash/"+this.props.hashtag.replace("#", "");
                else
                    hash = this.props.hashtag.replace("#", "");
            }
            if(this.state.lastID==this.id)
            {
                this.setState({
                    endofData:true,
                });
            }
            this.state.lastID=this.id;

            $(".spinner").show();
            $.ajax({
                url: '/api/content/?id=' + this.id +'&hash='+hash+'&user='+user+'&show='+show,
                dataType: 'json',
                cache: false,
                success: function (data) {
                    
                    data=this.state.data.concat(data);
                    
                    if($(".stream-row").attr("data-user")!="")
                    {
                        $("#custom_css").html(data[0].author.custom_css); 
                    }
                   
                    this.setState({data:data});
                    if (user_settings==false ||user_settings.autoplay == "no")
                    {
                        this.setAutoplayOff();
                    }
                    if (user_settings==false || user_settings.mute_video == "yes")
                    {
                        this.setMuted();
                    }
                    if(this.state.random)
                    {
                        url = "/permalink/"+data[0].stream.id;
                        var stateObj = { id: data[0].stream.id, url: url };
                        history.pushState(stateObj, "irgendwas", url);
                        

                    }
                    
                     this.setState({
                        loadingFlag:false,
                    });
                    $(".spinner").hide(); 
                }.bind(this),
                error: function (xhr, status, err) {
                    console.error(this.props.url, status, err.toString());
                }.bind(this)
            });
        }
        ,
        render: function () {

            if(user_settings.show_nsfw=="false"  && $(".stream-row").attr("data-hash")=="nsfw")
            {

                return (
                    <div className="content">
                        You disabled not safe for work content
                    </div>
                    );
            }
            if(user_settings==false && $(".stream-row").attr("data-hash")=="nsfw")
            {
                return (
                    <div className="content">
                        You need to be over +18 to watch nsfw content, 
                        please <a href="/user/register/">register here.</a>
                    </div>
                    );
            }
            return (
                    <div className="content">
                        <StreamList data={this.state.data}/>
                    </div>
                    );
        },
        setAutoplayOff: function() {

            $('video').each(function (index) {
                $("video").get(index).pause();
            });
        },
        setMuted: function() {

            $("video").prop('muted', true);
        },
        setID: function(id){
            this.id=id;
        },
        
        
        handleScroll(event) {
            
            if(this.state.endofData)
            {
                return true;
            }
            //this function will be triggered if user scrolls
            var windowHeight = $(window).height();
            var inHeight = window.innerHeight;
            var scrollT = $(window).scrollTop();
            var totalScrolled = scrollT+inHeight;
            if(totalScrolled+100>windowHeight){ //user reached at bottom
                if(!this.state.loadingFlag){ //to avoid multiple request
                    this.setState({
                        loadingFlag:true,
                    });

                    this.loadStreamFromServer();
                }
            }

            
        }
    });
    
        var data={}
        var isLoading = false;
        var endofdata = false;

        ReactDOM.render(
                <InitStream data={data}  /> ,
                document.getElementsByClassName('stream')[0]
        );
        ReactDOM.render(
                <ShareBox data={data}  /> ,
                document.getElementById("ShareBox")
        );

    