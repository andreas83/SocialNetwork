
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
            if($(".stream-row").attr("data-wayback")!="")
            {
                this.setID(parseInt($(".stream-row").attr("data-wayback"))+1);
                $(".stream-row").attr("data-wayback", "");
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
                    if (user_settings.autoplay == "no")
                        this.setAutoplayOff();
                    if (user_settings.mute_video == "yes")
                        this.setMuted();
                    
                     this.setState({
                        loadingFlag:false,
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.error(this.props.url, status, err.toString());
                }.bind(this)
            });
        }
        ,
        render: function () {
            
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

        React.render(
                <InitStream data={data}  /> ,
                document.getElementsByClassName('stream')[0]
        );

    