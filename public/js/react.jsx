
    function Replacehashtags(string){
    
        return string.replace(/#(\S*)/g,'<a class="hash" href="/hash/$1">#$1</a>');
    }
    
    var StreamBox  = React.createClass({
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
    
    var StreamList = React.createClass({
       
       
    

    render: function () {
    var streamNodes = this.props.data.map(function (data) {
        
        var editContent = function(){
            var streamItem=$(".stream-item[data-id="+data.stream.id+"]");
            streamItem.find(".text").attr("contenteditable", "true").focus();
            streamItem.find(".text").html(streamItem.find(".text").text());
            streamItem.find(".action .save").removeClass("hide");
            streamItem.find(".action .save").click(function(){
                $.ajax({
                url: '/api/content/'+data.stream.id,
                data: { "content": streamItem.find(".text").text() },
                type: 'PUT',
                success: function(result) {
                    if(result.status=="done"){
                        streamItem.find(".action .save").addClass("hide");
                        streamItem.find(".text").attr("contenteditable", "false");
                        streamItem.find(".text").html(Replacehashtags(streamItem.find(".text").html()));
                        
                    }
                }
                });
            });
        }
        var deleteContent = function(){
            $.ajax({
                url: '/api/content/'+data.stream.id,
                type: 'DELETE',
                success: function(result) {
                    if(result.status=="deleted"){
                        $(".stream-item[data-id="+data.stream.id+"]").remove();
                    }
                }
            });
        }
        return (
                <div data-id={data.stream.id} className="row stream-item">
                    <Author editContent={editContent} deleteContent={deleteContent} id={data.author.id} author={data.author} contentID={data.stream.id} time={data.stream.date}></Author>
                    <AuthorText id={data.stream.id} data={data.stream}></AuthorText>   
                    <Content id={data.stream.id} data={data.stream}></Content>
                    <Likebox id={data.stream.id}></Likebox>
                    <CommentBox id={data.stream.id} data=""></CommentBox>
                </div>
                );
    });


    return (
        <div className = "stream">
            {streamNodes}
        </div>
        
        );
    }
    });


    
    var Content = React.createClass({

    render: function () {


        var imgpath="";
        if(this.props.data.type=="generic")
        {
            return (<div className = "generic"></div>);
        }
        if(this.props.data.type=="img")
        {
            imgpath="/public/upload/" + this.props.data.url;
            
            return (<div className = "img"><img className="img-responsive" src={imgpath} /></div>);
        }
        if(this.props.data.type=="upload")
        {
            return (<Upload id={this.props.data.id} upload={this.props.data} />);
        }
        if(this.props.data.type=="www")
        {
            return (<WWW id={this.props.data.id}  meta={this.props.data} />);
        }
        if(this.props.data.type=="video")
        {
            return (<Video id={this.props.data.id}  meta={this.props.data} />);
        }
    }
    });


    var Upload = React.createClass({

    render: function () {
        var ImgPath, FilePath ="";
        var Img="";
        var Files="";   
        // 
        if(typeof(this.props.upload.files)!="undefined")
        {
            
            var Files = this.props.upload.files.map(function (data) {
                FilePath="/public/upload/" + data.src;

                    if(data.type.match("image"))
                    {
                        return (
                        <img className="img-responsive" src={FilePath} />
                        );
                    }
                    return (
                    <p><a href={FilePath} target="_blank"><span className="glyphicon glyphicon-circle-arrow-down"></span>  {data.name}</a></p>
                    );

            });
        }
        


        return (
            <div className = "upload">
                {Files}
            </div>
            );
    }
    });


    var WWW = React.createClass({

    render: function () {


        return (
            <div className = "www">
                <a href={this.props.meta.url}>
                <img className="img-responsive" src={this.props.meta.og_img} />
                
                <h2>{this.props.meta.og_title}</h2>
                </a>
                <p>{this.props.meta.og_description}</p>
            </div>
            );
    }
    });


    var Video = React.createClass({

    render: function () {
        return (
            <div className = "video">
                <div dangerouslySetInnerHTML={{__html: this.props.meta.html}} />
            </div>
            );
    }
    });

    var CommentList = React.createClass({
    render: function () {
                var commentNodes = this.props.data.map(function (comment) {
                    return (
                            <Comment author={comment.author}>
                            {comment.text}
                            </Comment>
                            );
                });
                return (
                        <div className = "commentList">
                            {commentNodes}
                        </div>
                        );
            }
        });

    var Author = React.createClass({
        
    render: function () {
        
        var imgpath = "/public/upload/" + this.props.author.profile_picture;
        if(typeof(this.props.author.profile_picture)=="undefined")
        {
            imgpath="/public/img/default-profile.png";
        }
        var editBtn;
        if(this.props.id==user_id)
        {
            editBtn=<ul className="AuthorMenu">
                        <li className="btn btn-info" onClick={this.props.editContent}>Edit</li>
                        <li className="btn btn-warning" onClick={this.props.deleteContent}>Delete</li>
                    </ul>;
        }
        var permalink="/permalink/"+this.props.contentID;
        var authorlink="/"+this.props.author.name.replace(" ", ".");
        return (
            <div className="author">
                <div className="left">
                    <img className="img-circle" src={imgpath} />
                    <strong>
                            <a href={authorlink}>{this.props.author.name}</a>
                    </strong>
                    <br/>
                    <a href={permalink}>#{this.props.contentID}</a>
                </div>
                <div className="right">
                    {editBtn}
                </div>
                
            </div>
            );
    }
    });

    var AuthorText = React.createClass({

    componentDidMount: function(){
        var domNode = this.getDOMNode();
        var nodes = domNode.querySelectorAll('code');
        if (nodes.length > 0) {
            for (var i = 0; i < nodes.length; i=i+1) {
                $( nodes[i] ).wrap( '<pre className="SourceCode"></pre>' );
                hljs.highlightBlock(nodes[i]);

            }
        }
        
        nodes = domNode.querySelectorAll('.text>span');
        if (nodes.length > 0) {
            for (var i = 0; i < nodes.length; i=i+1) {
                if(nodes[i].childNodes.length>0)
                {
                    if(typeof(nodes[i].childNodes[0].nodeValue)=="string")
                    {
                        
                        $(nodes[i]).html(nodes[i].childNodes[0].nodeValue.replace(/#(\S*)/g,'<a class="hash" href="/hash/$1">#$1</a>'));
                        
                        
                    }
                    console.log(nodes[i].childNodes[0].nodeType);
                }
                //$(nodes[i].childNodes[0].nodeValue).text().replace(/#(\S*)/g,'<a class="hash" href="/hash/$1">#$1</a>');
            }
        }

         
    },
    
    render: function () {
        
        var content=this.props.data.text;
        
        
        return (
                <div>
                    <div className="text" >
                        <span dangerouslySetInnerHTML = {{__html: content}} />
                    </div>
                    <div className="action">
                        <a className="btn save hide btn-success">Save</a>
                    </div>
                </div>
            );
    }
    });



    var CommentBox = React.createClass({
        getInitialState: function () {
            return {data: []};
        },
        componentDidMount: function () {
            this.loadCommentsFromServer();
            //setInterval(this.loadCommentsFromServer, 10000);
        },
        loadCommentsFromServer: function () {
            $.ajax({
                url: '/api/comments/' + this.props.id,
                dataType: 'json',
                cache: false,
                success: function (data) {
                    this.setState({data: data});
                }.bind(this),
                error: function (xhr, status, err) {
                    console.error(this.props.url, status, err.toString());
                }.bind(this)
            });
        },
        handleCommentSubmit: function (comment) {

            $.ajax({
                url: '/api/comments/' + this.props.id,
                dataType: 'json',
                type: 'POST',
                data: comment,
                success: function (data) {
                    this.setState({data: data});
                }.bind(this),
                error: function (xhr, status, err) {
                    if(err.toString()=="Forbidden")
                    {
                        alert("Please login");
                    }
                    console.error(this.props.url, status, err.toString());

                }.bind(this)
            });
        },
        render: function () {

            var commentForm="";
            if(user_id > 0)
            {
                commentForm= <CommentForm onCommentSubmit = {this.handleCommentSubmit} />;
            }
            return (
                    <div className = "commentBox">
                        {commentForm}
                        <CommentList data = {this.state.data} />
                    </div>
                    );
        }
    });

    var CommentList = React.createClass({
        render: function () {
            
            var commentNodes = this.props.data.map(function (comment) {
                
                return (
                        <Comment author={comment.author}>
                        {comment.text}
                        </Comment>
                        );
            });
            return (
                    <div className = "commentList">
                        {commentNodes}
                    </div>
                    );
        }
    });

    var CommentForm = React.createClass({
        handleSubmit: function (e) {
            e.preventDefault();

            var text = React.findDOMNode(this.refs.text).value.trim();
            if (!text) {
                return;
            }

            this.props.onCommentSubmit({text: text})
            React.findDOMNode(this.refs.text).value = '';
            return;
        },
        render: function () {
            return (
                <form className = "commentForm" onSubmit={this.handleSubmit} >
                    <textarea className="form-control" ref="text" ></textarea>
                    <input className="btn btn-success col-xs-12 col-md-2" type="submit" value="Comment" />
                </form>
                );
                }
            });

    var Comment = React.createClass({

        render: function () {

            var imgpath = "/public/upload/" + this.props.author.profile_picture;
            if(typeof(this.props.author.profile_picture)=="undefined")
            {
                imgpath="/public/img/default-profile.png";
            }
            
            
            var authorLink="/"+this.props.author.name.replace(" ", ".");
            return (
                <div className = "comment">
                    <img className="img-circle" src = {imgpath} />
                    <h4 className = "commentAuthor">
                    <a href={authorLink}>{this.props.author.name}</a>
                    </h4>
                    <span dangerouslySetInnerHTML = {{__html: this.props.children.toString()}} />

                </div>

                );
        }
        });
    var Likebox = React.createClass({
            getInitialState: function () {
            return {data: []};
        },
        componentDidMount: function () {
            this.loadLikesFromServer();
            
        },
        loadLikesFromServer: function () {
            $.ajax({
                url: '/api/score/' + this.props.id,
                dataType: 'json',
                cache: false,
                success: function (data) {
                    this.setState({like: data.like, dislike: data.dislike});
                }.bind(this),
                error: function (xhr, status, err) {
                    console.error(this.props.url, status, err.toString());
                }.bind(this)
            });
        },


        handleSubmit: function (target, e) {

            e.preventDefault();

            
            $.ajax({
            url: '/api/score/'+target+'/' + this.props.id,
            method:"POST",
            dataType: 'json',
            cache: false,
            success: function (data) {
                this.setState({like: data.like, dislike: data.dislike});
            }.bind(this),
            error: function (xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });

        },
        render: function(){
            
            return(
            <div>
            <form className="Likebox" onSubmit={this.handleSubmit}> 
                <span onClick={this.handleSubmit.bind(this, "add")} dest="up" className="glyphicon glyphicon-chevron-up btn"> {this.state.like}</span>
                <span onClick={this.handleSubmit.bind(this, "sub")} dest="down" className="glyphicon glyphicon-chevron-down btn"> {this.state.dislike}</span>
            </form>
            </div>
            )
        }
    });


        var data={}
        var isLoading = false;
        var endofdata = false;

        React.render(
                <StreamBox data={data}  /> ,
                document.getElementsByClassName('stream')[0]
        );

    