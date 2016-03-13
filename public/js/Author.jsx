
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
            editBtn=
        <div className="dropdown pull-right">
        <button className="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <span className="caret"></span>
        </button>
                    <ul className="dropdown-menu">
                        <li><a href="#" onClick={this.props.editContent}>Edit</a></li>
                        <li><a href="#" onClick={this.props.deleteContent}>Delete</a></li>
                        <li><a href="#" onClick={this.props.reportContent}>Report</a></li>
                    </ul>
        </div>;
        }
        else
        {
            editBtn=
        <div className="dropdown pull-right">
        <button className="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <span className="caret"></span>
        </button>
                    <ul className="dropdown-menu ">
                        <li><a href="#" onClick={this.props.reportContent}>Report</a></li>
                    </ul>
        </div>;
        }
        var permalink="/permalink/"+this.props.contentID;
        var authorlink="/"+this.props.author.name.replace(" ", ".");

        


        return (
            <div className="author">
                <div className="col-md-10 col-xs-10">
                    <img className="img-circle" src={imgpath} />
                    <strong>
                            <a href={authorlink}>{this.props.author.name}</a>
                    </strong> {this.prettyDate(this.props.time)}
                    <br/>
                    <a href={permalink}>#{this.props.contentID}</a> 
                </div>
                <div className="col-md-2 col-xs-2">
                    {editBtn}
                </div>
                
            </div>
            );
    },
    prettyDate:function (time){
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
        var re = /(\<code[\]\>[\s\S]*?(?:.*?)<\/code\>*?[\s\S])|(#\S*)|(@\S*)/gi; 
        
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
            if(typeof m[3] !="undefined")
            {
                user=m[3].replace("@","");
                tmp_content=tmp_content.replace(m[3], '<a href="/'+user+'">@'+user+'</a>');
                
            }

            
        }

        tmp_content=tmp_content.replace(/\r\n/g, "<br/>");

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