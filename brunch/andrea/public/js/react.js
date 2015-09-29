'use strict';

function Replacehashtags(string) {

    return string.replace(/#(\S*)/g, '<a class="hash" href="/hash/$1">#$1</a>');
}

var StreamBox = React.createClass({
    displayName: 'StreamBox',

    getInitialState: function getInitialState() {

        return { data: [] };
    },
    componentDidMount: function componentDidMount() {
        this.loadStreamFromServer();

        document.addEventListener('scroll', this.handleScroll);
    },
    componentWillUnmount: function componentWillUnmount() {
        document.removeEventListener('scroll', this.handleScroll);
    },

    loadStreamFromServer: function loadStreamFromServer() {

        var hash = "";
        var show = 5;
        var lastid = "";
        if (this.id > 0 || typeof id == "undefined") {

            this.setID(parseInt($(".stream-item").last().attr("data-id")));
        }
        if ($(".stream-row").attr("data-permalink") > 0) {

            this.setID(parseInt($(".stream-row").attr("data-permalink")) + 1);
            show = 1;
            this.setState({
                endofData: true
            });
        }
        if ($(".stream-row").attr("data-wayback") != "") {

            this.setID(parseInt($(".stream-row").attr("data-wayback")) + 1);
            $(".stream-row").attr("data-wayback", "");
        }

        if ($(".stream-row").attr("data-hash") != "") {
            hash = $(".stream-row").attr("data-hash");
        }
        if (typeof this.props.hashtag != "undefined") {
            hash = this.props.hashtag.replace("#", "");
        }

        if (this.state.lastID == this.id) {
            this.setState({
                endofData: true
            });
        }
        this.state.lastID = this.id;

        $.ajax({
            url: '/api/content/?id=' + this.id + '&hash=' + hash + '&show=' + show,
            dataType: 'json',
            cache: false,
            success: (function (data) {

                data = this.state.data.concat(data);

                this.setState({ data: data });
                if (user_settings.autoplay == "no") this.setAutoplayOff();
                if (user_settings.mute_video == "yes") this.setMuted();

                this.setState({
                    loadingFlag: false
                });
            }).bind(this),
            error: (function (xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }).bind(this)
        });
    },

    render: function render() {

        return React.createElement(
            'div',
            { className: 'content' },
            React.createElement(StreamList, { data: this.state.data })
        );
    },
    setAutoplayOff: function setAutoplayOff() {

        $('video').each(function (index) {
            $("video").get(index).pause();
        });
    },
    setMuted: function setMuted() {

        $("video").prop('muted', true);
    },
    setID: function setID(id) {
        this.id = id;
    },

    handleScroll: function handleScroll(event) {

        if (this.state.endofData) {
            return true;
        }
        //this function will be triggered if user scrolls
        var windowHeight = $(window).height();
        var inHeight = window.innerHeight;
        var scrollT = $(window).scrollTop();
        var totalScrolled = scrollT + inHeight;
        if (totalScrolled + 100 > windowHeight) {
            //user reached at bottom
            if (!this.state.loadingFlag) {
                //to avoid multiple request
                this.setState({
                    loadingFlag: true
                });
                this.loadStreamFromServer();
            }
        }
    }
});

var StreamList = React.createClass({
    displayName: 'StreamList',

    render: function render() {
        var streamNodes = this.props.data.map(function (data) {

            var editContent = function editContent() {
                var streamItem = $(".stream-item[data-id=" + data.stream.id + "]");
                streamItem.find(".text").attr("contenteditable", "true").focus();
                streamItem.find(".action .save").removeClass("hide");
                streamItem.find(".action .save").click(function () {
                    $.ajax({
                        url: '/api/content/' + data.stream.id,
                        data: { "content": streamItem.find(".text").text() },
                        type: 'PUT',
                        success: function success(result) {
                            if (result.status == "done") {
                                streamItem.find(".action .save").addClass("hide");
                                streamItem.find(".text").attr("contenteditable", "false");
                                streamItem.find(".text").html(Replacehashtags(streamItem.find(".text").html()));
                            }
                        }
                    });
                });
            };
            var deleteContent = function deleteContent() {
                $.ajax({
                    url: '/api/content/' + data.stream.id,
                    type: 'DELETE',
                    success: function success(result) {
                        if (result.status == "deleted") {
                            $(".stream-item[data-id=" + data.stream.id + "]").remove();
                        }
                    }
                });
            };
            return React.createElement(
                'div',
                { 'data-id': data.stream.id, className: 'row stream-item' },
                React.createElement(Author, { editContent: editContent, deleteContent: deleteContent, id: data.author.id, author: data.author, contentID: data.stream.id, time: data.stream.date }),
                React.createElement(AuthorText, { id: data.stream.id, data: data.stream }),
                React.createElement(Content, { id: data.stream.id, data: data.stream }),
                React.createElement(Likebox, { id: data.stream.id }),
                React.createElement(CommentBox, { id: data.stream.id, data: '' })
            );
        });

        return React.createElement(
            'div',
            { className: 'stream' },
            streamNodes
        );
    }
});

var Content = React.createClass({
    displayName: 'Content',

    render: function render() {

        var imgpath = "";
        if (this.props.data.type == "generic") {
            return React.createElement('div', { className: 'generic' });
        }
        if (this.props.data.type == "img") {
            imgpath = "/public/upload/" + this.props.data.url;

            return React.createElement(
                'div',
                { className: 'img' },
                React.createElement('img', { className: 'img-responsive', src: imgpath })
            );
        }
        if (this.props.data.type == "upload") {
            return React.createElement(Upload, { id: this.props.data.id, upload: this.props.data });
        }
        if (this.props.data.type == "www") {
            return React.createElement(WWW, { id: this.props.data.id, meta: this.props.data });
        }
        if (this.props.data.type == "video") {
            return React.createElement(Video, { id: this.props.data.id, meta: this.props.data });
        }
    }
});

var Upload = React.createClass({
    displayName: 'Upload',

    render: function render() {
        var imgpath = "";
        var Images = this.props.upload.img.map(function (data) {
            imgpath = "/public/upload/" + data;
            return React.createElement('img', { className: 'img-responsive', src: imgpath });
        });

        return React.createElement(
            'div',
            { className: 'upload' },
            Images
        );
    }
});

var WWW = React.createClass({
    displayName: 'WWW',

    render: function render() {

        return React.createElement(
            'div',
            { className: 'www' },
            React.createElement(
                'a',
                { href: this.props.meta.url },
                React.createElement('img', { className: 'img-responsive', src: this.props.meta.og_img }),
                React.createElement(
                    'h2',
                    null,
                    this.props.meta.og_title
                )
            ),
            React.createElement(
                'p',
                null,
                this.props.meta.og_description
            )
        );
    }
});

var Video = React.createClass({
    displayName: 'Video',

    render: function render() {
        return React.createElement(
            'div',
            { className: 'video' },
            React.createElement('div', { dangerouslySetInnerHTML: { __html: this.props.meta.html } })
        );
    }
});

var CommentList = React.createClass({
    displayName: 'CommentList',

    render: function render() {
        var commentNodes = this.props.data.map(function (comment) {
            return React.createElement(
                Comment,
                { author: comment.author },
                comment.text
            );
        });
        return React.createElement(
            'div',
            { className: 'commentList' },
            commentNodes
        );
    }
});

var Author = React.createClass({
    displayName: 'Author',

    render: function render() {

        var imgpath = "/public/upload/" + this.props.author.profile_picture;

        var editBtn;
        if (this.props.id == user_id) {
            editBtn = React.createElement(
                'ul',
                { className: 'AuthorMenu' },
                React.createElement(
                    'li',
                    { className: 'btn btn-info', onClick: this.props.editContent },
                    'Edit'
                ),
                React.createElement(
                    'li',
                    { className: 'btn btn-warning', onClick: this.props.deleteContent },
                    'Delete'
                )
            );
        }
        var permalink = "/permalink/" + this.props.contentID;

        return React.createElement(
            'div',
            { className: 'author' },
            React.createElement(
                'div',
                { className: 'left' },
                React.createElement('img', { className: 'img-circle', src: imgpath }),
                React.createElement(
                    'strong',
                    null,
                    this.props.author.name
                ),
                React.createElement('br', null),
                React.createElement(
                    'a',
                    { href: permalink },
                    '#',
                    this.props.contentID
                )
            ),
            React.createElement(
                'div',
                { className: 'right' },
                editBtn
            )
        );
    }
});

var AuthorText = React.createClass({
    displayName: 'AuthorText',

    render: function render() {

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'text' },
                React.createElement('span', { dangerouslySetInnerHTML: { __html: Replacehashtags(this.props.data.text) } })
            ),
            React.createElement(
                'div',
                { className: 'action' },
                React.createElement(
                    'a',
                    { className: 'btn save hide btn-success' },
                    'Save'
                )
            )
        );
    }
});

var CommentBox = React.createClass({
    displayName: 'CommentBox',

    getInitialState: function getInitialState() {
        return { data: [] };
    },
    componentDidMount: function componentDidMount() {
        this.loadCommentsFromServer();
        //setInterval(this.loadCommentsFromServer, 10000);
    },
    loadCommentsFromServer: function loadCommentsFromServer() {
        $.ajax({
            url: '/api/comments/' + this.props.id,
            dataType: 'json',
            cache: false,
            success: (function (data) {
                this.setState({ data: data });
            }).bind(this),
            error: (function (xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }).bind(this)
        });
    },
    handleCommentSubmit: function handleCommentSubmit(comment) {

        $.ajax({
            url: '/api/comments/' + this.props.id,
            dataType: 'json',
            type: 'POST',
            data: comment,
            success: (function (data) {
                this.setState({ data: data });
            }).bind(this),
            error: (function (xhr, status, err) {
                if (err.toString() == "Forbidden") {
                    alert("Please login");
                }
                console.error(this.props.url, status, err.toString());
            }).bind(this)
        });
    },
    render: function render() {

        var commentForm = "";
        if (user_id > 0) {
            commentForm = React.createElement(CommentForm, { onCommentSubmit: this.handleCommentSubmit });
        }
        return React.createElement(
            'div',
            { className: 'commentBox' },
            commentForm,
            React.createElement(CommentList, { data: this.state.data })
        );
    }
});

var CommentList = React.createClass({
    displayName: 'CommentList',

    render: function render() {

        var commentNodes = this.props.data.map(function (comment) {

            return React.createElement(
                Comment,
                { author: comment.author },
                comment.text
            );
        });
        return React.createElement(
            'div',
            { className: 'commentList' },
            commentNodes
        );
    }
});

var CommentForm = React.createClass({
    displayName: 'CommentForm',

    handleSubmit: function handleSubmit(e) {
        e.preventDefault();

        var text = React.findDOMNode(this.refs.text).value.trim();
        if (!text) {
            return;
        }

        this.props.onCommentSubmit({ text: text });
        React.findDOMNode(this.refs.text).value = '';
        return;
    },
    render: function render() {
        return React.createElement(
            'form',
            { className: 'commentForm', onSubmit: this.handleSubmit },
            React.createElement('textarea', { className: 'form-control', ref: 'text' }),
            React.createElement('input', { className: 'btn btn-success col-xs-12 col-md-2', type: 'submit', value: 'Comment' })
        );
    }
});

var Comment = React.createClass({
    displayName: 'Comment',

    render: function render() {

        var imgpath = "/public/upload/" + this.props.author.profile_picture;
        var rawMarkup = marked(this.props.children.toString(), { sanitize: true });
        return React.createElement(
            'div',
            { className: 'comment' },
            React.createElement('img', { className: 'img-circle', src: imgpath }),
            React.createElement(
                'h3',
                { className: 'commentAuthor' },
                this.props.author.name
            ),
            React.createElement('span', { dangerouslySetInnerHTML: { __html: rawMarkup } })
        );
    }
});
var Likebox = React.createClass({
    displayName: 'Likebox',

    getInitialState: function getInitialState() {
        return { data: [] };
    },
    componentDidMount: function componentDidMount() {
        this.loadLikesFromServer();
    },
    loadLikesFromServer: function loadLikesFromServer() {
        $.ajax({
            url: '/api/score/' + this.props.id,
            dataType: 'json',
            cache: false,
            success: (function (data) {
                this.setState({ like: data.like, dislike: data.dislike });
            }).bind(this),
            error: (function (xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }).bind(this)
        });
    },

    handleSubmit: function handleSubmit(target, e) {

        e.preventDefault();

        $.ajax({
            url: '/api/score/' + target + '/' + this.props.id,
            method: "POST",
            dataType: 'json',
            cache: false,
            success: (function (data) {
                this.setState({ like: data.like, dislike: data.dislike });
            }).bind(this),
            error: (function (xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }).bind(this)
        });
    },
    render: function render() {

        return React.createElement(
            'div',
            null,
            React.createElement(
                'form',
                { className: 'Likebox', onSubmit: this.handleSubmit },
                React.createElement(
                    'span',
                    { onClick: this.handleSubmit.bind(this, "add"), dest: 'up', className: 'glyphicon glyphicon-chevron-up btn' },
                    ' ',
                    this.state.like
                ),
                React.createElement(
                    'span',
                    { onClick: this.handleSubmit.bind(this, "sub"), dest: 'down', className: 'glyphicon glyphicon-chevron-down btn' },
                    ' ',
                    this.state.dislike
                )
            )
        );
    }
});

var data = {};
var isLoading = false;
var endofdata = false;

React.render(React.createElement(StreamBox, { data: data }), document.getElementsByClassName('stream')[0]);
