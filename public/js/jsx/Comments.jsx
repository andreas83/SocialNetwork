
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

    var CommentBox = React.createClass({
        getInitialState: function () {
            return {data: []};
        },
        componentDidMount: function () {
            this.loadCommentsFromServer();
            bindMention();
            //setInterval(this.loadCommentsFromServer, 10000);
        },
        loadCommentsFromServer: function () {
            $.ajax({
                url: '/api/comment/' + this.props.id,
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
                url: '/api/comment/' + this.props.id,
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

    var CommentHint = React.createClass({
      
        getInitialState: function() {
            return { showComment: false };
        },
        handleClick: function(e){
            if(this.state.showComment==true)
            {
                show=false;
            }
            else
            {
                show=true;
            }

            this.setState({ showComment: show });
        },
        render: function(){
            
            return (<div className="CommentBox">
                        <span onClick={this.handleClick.bind(this, this.props.id)} className="btn fa fa-comments"></span>
                        { this.state.showComment ? <CommentBox id={this.props.id} /> : null }
                    </div>
                    
                );

        }
        });

    var CommentForm = React.createClass({
        handleSubmit: function (e) {
            e.preventDefault();

            var text = ReactDOM.findDOMNode(this.refs.text).value.trim();
            if (!text) {
                return;
            }

            this.props.onCommentSubmit({text: text})
            ReactDOM.findDOMNode(this.refs.text).value = '';
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

            var imgpath = this.props.author.profile_picture;
            if(typeof(this.props.author.profile_picture)=="undefined")
            {
                imgpath = "/public/img/default-profile.png";
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