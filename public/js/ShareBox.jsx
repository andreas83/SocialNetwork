
var ShareBox = React.createClass({
        getInitialState: function () {
        return {data: []};
    },
    componentDidMount: function () {

        this.setState({
            isMetaLoading:false,
        });

        share_area= document.getElementById('share_area');
        share_area.addEventListener('input', this.handleInput);


    },


    /*
     * @todo remove jquery 
     */
    closePreview: function(e){

        $(".preview").hide();
        $("#img").val("");
        $("#metadata").val("");
        this.setState({
            isMetaLoading:false,
        });
    },




    renderPreview: function(scope) {
        if($("#share_area").val() == this.state.data.url)
            $("#share_area").val("");

        $(".preview").hide();
        $(".preview."+scope).show();

    },
    handleInput: function (event) {


            var urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
            if ($("#share_area").val().match(urlRegex)) {
                url = $("#share_area").val().match(urlRegex);

                if(this.state.isMetaLoading)
                    return false;

                this.setState({
                    isMetaLoading:true,
                });
                this.serverRequest = $.get('/api/metadata/?url=' + url, function (data) {
                    this.setState({
                        data:data
                    });

                    this.renderPreview(data.type);



                  }.bind(this));

            }
    },
    render: function(){

        return(<div>
            <form method="post" action="/api/content/" encType="multipart/form-data">
                    <div className="row">


                    <div className="col-md-11">
                        <textarea id="share_area" placeholder="" name="content" rows="3" className="form-control"></textarea>

                        <div className="row preview www">

                            <p className="text-right">
                                <button className="btn btn-info" onClick={this.closePreview}>
                                    <span className="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </button>
                            </p>
                            <div className="col-md-3">
                                <img className="img-responsive" src={this.state.data.og_img} id="og_img" />
                            </div>
                            <div className="col-md-9">
                                <h3 id="og_title">{this.state.data.og_title}</h3>

                                <p id="og_desc">{this.state.data.og_description}</p>

                            </div>
                            <div className="col-md-12">
                                <a href={this.state.data.url} id="www_link">{this.state.data.url}</a>
                            </div>
                        </div>
                        <div className="row preview img">
                            <div className="col-md-12">
                                <p className="text-right">
                                    <button className="btn btn-info" onClick={this.closePreview}>
                                        <span className="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </button>
                                </p>
                                <img className="img-responsive" src={this.state.data.url} id="preview_img" />
                            </div>

                        </div>
                        <div className="row preview upload">
                            <div className="col-md-12">
                                <p className="text-right">
                                    <button className="btn btn-info" onClick={this.closePreview}>
                                        <span className="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </button>
                                </p>
                                <div id="uploadPreview"></div>
                            </div>

                        </div>
                        <div className="row preview video">
                            <div className="col-md-12">
                                <p className="text-right">
                                    <button className="btn btn-info" onClick={this.closePreview}>
                                        <span className="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </button>
                                </p>

                                <div id="video_target" className="embed-responsive embed-responsive-16by9"  dangerouslySetInnerHTML = {{__html: this.state.data.html}}></div>
                            </div>

                        </div>
                        <input type="hidden" name="metadata" id="metadata" value={JSON.stringify(this.state.data)} />
                        <input type="text" name="mail" className="hide" value="" />
                        </div>



                        <div className="col-md-5">
                            <span className="btn btn-lg btn-warning btn-file">
                            <i className="glyphicon glyphicon-cloud-upload"></i> Upload
                            <input type="file" id="img" multiple name="img[]" className="form-control" />
                            </span>
                        <button className="btn btn-lg btn-info "><i className="glyphicon glyphicon-heart"></i> Share!</button>
                        <p className="fileinfo"></p>
                        </div>
                    </div>

                    </form></div>

        )
    }
});
