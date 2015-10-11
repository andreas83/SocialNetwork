
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
            imgpath = this.props.data.url;
            
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
                FilePath = data.src;

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
