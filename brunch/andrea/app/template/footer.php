<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/0.3.2/marked.min.js"></script>

<script type="text/jsx">
    


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
            if (typeof id == "undefined")
                id = 0;
            else
                id=$(".stream-item").last().attr("data-id");

            if (typeof hash == "undefined")
                hash = "";
            
            $.ajax({
                url: '/api/content/?id=' + id+"&show=5",
                dataType: 'json',
                cache: false,
                success: function (data) {
                    
                    data=this.state.data.concat(data);
                    this.setState({data:data});
                    isLoading=false;
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
                        <StreamList  data={this.state.data}/>        
                    </div>
                    );
        },
        handleScroll(event) {
            
            if ($(window).scrollTop() + 50 >= ($(document).height() - $(window).height()))
            {
                if (endofdata)
                return false;

                if (isLoading) {

                    return false; // don't make another request, let the current one complete, or
                    // ajax.abort(); // stop the current request, let it run again
                }
                isLoading=true;
                this.loadStreamFromServer();
            }
            
        }
    });
    
    var StreamList = React.createClass({


    render: function () {
    var streamNodes = this.props.data.map(function (data) {
        var markdown = marked(data.stream.text, {sanitize: true});
        return (
                <div data-id={data.stream.id} className="stream-item">
                    <Author id={data.author.id} author={data.author}></Author>
                    <div className="text"><span dangerouslySetInnerHTML = {{__html: markdown}} /></div>
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
        
        var Images = this.props.upload.img.map(function (data) {
            imgpath="/public/upload/" + data;
        return (
                <img className="img-responsive" src={imgpath} />
                );
        });


        return (
            <div className = "upload">
                {Images}              
            </div>
            );
    }
    });


    var WWW = React.createClass({

    render: function () {


        return (
            <div className = "www">
                <a href={this.props.meta.og_img}>
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
        return (
            <div className="author">
                <img className="img-circle" src={imgpath} />

                <strong>
                        {this.props.author.name}
                </strong>
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

            return (
                    <div className = "commentBox">
                   
                    <CommentForm onCommentSubmit = {this.handleCommentSubmit} />
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
                    <input type="submit" value="Post" />
                </form>
                );
                }
            });

            var Comment = React.createClass({

            render: function () {

                var imgpath = "/public/upload/" + this.props.author.profile_picture;
                var rawMarkup = marked(this.props.children.toString(), {sanitize: true});
                return (
                    <div className = "comment">
                        <img className="img-circle" src = {imgpath} />
                        <h3 className = "commentAuthor">
                        {this.props.author.name}
                        </h3>
                        <span dangerouslySetInnerHTML = {{__html: rawMarkup}} />

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


            handleSubmit: function (e) {
                e.preventDefault();
                
                var type="add";
                if($(e.target).hasClass("active"))
                {
                    type="sub";
                }
                
                $(e.target).toggleClass("active");    
                $.ajax({
                url: '/api/score/'+type+'/' + this.props.id,
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
                console.log(this.state.dislike);
                return(
                <div>
                <form className="Likebox" onSubmit={this.handleSubmit}> 
                    <span onClick={this.handleSubmit} className="glyphicon glyphicon-chevron-up btn"> {this.state.like}</span>
                    <span onClick={this.handleSubmit}  className="glyphicon glyphicon-chevron-down btn"> {this.state.dislike}</span>
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

    
  


</script>

</body>
</html>