
    var Author = React.createClass({
        
    render: function () {
        
        var imgpath = this.props.author.profile_picture;
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
    },
    
    render: function () {
        
        var content=this.props.data.text;
        var re = /(\<code[\]\>[\s\S]*?(?:.*?)<\/code\>*?[\s\S])|(#\S*)/gi; 
        
        var m;
        var hash;
        var tmp_content=content;
        while ((m = re.exec(content)) !== null) {
            if (m.index === re.lastIndex) {
                re.lastIndex++;
            }

            if(typeof m[2] !="undefined")
            {
                hash=m[2].replace("#","");
                tmp_content=tmp_content.replace(m[2], '<a href="/hash/'+hash+'">#'+hash+'</a>');
            }
            
        }


        
        
        return (
                <div>
                    <div className="text" >
                        <span dangerouslySetInnerHTML = {{__html: tmp_content}} />
                    </div>
                    <div className="action">
                        <a className="btn save hide btn-success">Save</a>
                    </div>
                </div>
            );
    }
    });