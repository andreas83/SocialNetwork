<?php
include("header.php");
include("menu.php");
?>    
<div class="col-md-7 col-sm-8 col-xs-12 ">


    <div class="row">

        <div class="col-md-12">
            <h1>API Documentation</h1>

            <?php
            if (!isset($api_key)) {
                echo "<p>" . _("You need an account for valid API Key") . "</p>";
                echo '<a class="btn btn-success c" href="/user/register/  ">GET API Key</a>';
            } else {
                ?>
                <label for="api_key">API KEY</label>
                <input class="form-control" id="api_key" type="text" name="api_key" value="<?php echo(isset($api_key) ? $api_key : "") ?>">
            <?php
            } ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <h2>Data type</h2>
            <ul class="nav nav-tabs ">
                <li role="presentation" class="active"><a aria-controls="contenthelp" role="tab" data-toggle="tab" href="#contenthelp">Content</a></li>
                <li role="presentation" ><a aria-controls="commenthelp" role="tab" data-toggle="tab" href="#commenthelp">Comment</a></li>
            </ul>
        </div>

    </div>
    <div class=" tab-content row">
        <div class="col-md-12 content tab-pane active" role="tabpanel" id="contenthelp">

            <div>


                <ul class="nav nav-tabs " role="tablist">
                    <li role="presentation" class="active"><a href="#get" aria-controls="home" role="tab" data-toggle="tab">GET Content</a></li>
                    <li role="presentation" ><a href="#postcontent" aria-controls="home" role="tab" data-toggle="tab">POST Content</a></li>
                    <li role="presentation"><a href="#update" aria-controls="update" role="tab" data-toggle="tab">Update Content</a></li>
                    <li role="presentation"><a href="#delete" aria-controls="delete" role="tab" data-toggle="tab">Delete Content</a></li>

                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="get">
                        <h2>Get Content</h2>

                        <pre class="SourceCode">
Get 5 cat pictures
                        <code class="hljs javascript">
            
    var param = {"hash":"cat", "show": 5 }
    $.ajax({
       url: "/api/content/",
       method: "GET",
       data:param

    }).done(function( json ) {
        console.log(json);
    });
        
                        </code>
                        </pre>

                        <p>Another example</p>
                        <pre class="SourceCode">
                        <code class="hljs javascript">
    //Get 5 content elements from specific user
    var param = {"user":"chuck noris", "show": 5 }
    
    //Get 5 content elements from specific id
    var param = {"id":"123", "show": 5 }
        
                        </code>
                        </pre>

                    </div>
                    <div role="tabpanel" class="tab-pane" id="postcontent">

                        <h2>Post Content</h2>
                        <pre class="SourceCode">
Simple example, just text
                        <code class="hljs javascript">
    
    var text="new content";
    var api_key="";
    $.ajax({
            url: '/api/content/',
            data: { "content": text, "api_key" : api_key},
            type: 'POST',
            success: function(result) { 
                        console.log(result);
                    }
            });
        
                        </code>
                        </pre>
                        <pre class="SourceCode">
Website and Text 
                        <code class="hljs javascript">
    
    var text="content with website";
    var url="https://fsfe.org/";
    var metadata = {"type":"www", "url":url}
    var api_key="";
    $.ajax({
            url: '/api/content/',
            data: { "content": text, "metadata": JSON.stringify(metadata), "api_key" : api_key},
            type: 'POST',
            success: function(result) { 
                        console.log(result);
                    }
            });
        
                        </code>
                        </pre>
                        <pre class="SourceCode">
Text and Image 
                        <code class="hljs javascript">
    
    var text="new content with image";
    var image="http://u.dasmerkendienie.com/03f99096092a63bfba23486718f7690fba666e658311d671a72d5962af4b7ba16daa9104a038730fad3e3690cea9db5288413ff3f9ed9c9fe1ab166fa93dce2c.gif";
    var metadata = {"type":"img", "url":image}
    var api_key="";
    $.ajax({
            url: '/api/content/',
            data: { "content": text, "metadata": JSON.stringify(metadata), "api_key" : api_key},
            type: 'POST',
            success: function(result) { 
                        console.log(result);
                    }
            });
        
                        </code>
                        </pre>

                    </div>
                    <div role="tabpanel" class="tab-pane" id="update">

                        <h2>Update Content</h2>
                        <pre class="SourceCode">
                        <code class="hljs javascript">
    var id="123"; //id of content element you wish to update;
    var text="new content";
    var api_key="";
    $.ajax({
            url: '/api/content/'+id,
            data: { "content": text, "api_key" : api_key},
            type: 'PUT',
            success: function(result) { 
                        console.log(result);
                    }
            });
        
                        </code>
                        </pre>

                    </div>
                    <div role="tabpanel" class="tab-pane" id="delete">

                        <h2>Delete Content</h2>
                        <pre class="SourceCode">
                        <code class="hljs javascript"> 
    var id="123"; //id of content element you wish to delete;
    var api_key="";
    $.ajax({
            url: '/api/content/'+id,
            param: {"api_key":api_key},
            type: 'DELETE',
            success: function(result) {
                if(result.status=="deleted"){
                    console.log("element deleted");
                }
            }
        });
        
                        </code>
                        </pre>

                    </div>

                </div>

            </div>

        </div>

    

    <div role="tabpanel" class="col-md-12 tab-pane comment" id="commenthelp">
       <div>


                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#getcomment" aria-controls="home" role="tab" data-toggle="tab">GET Comment</a></li>
                    <li role="presentation"><a href="#postcomment" aria-controls="postcomment" role="tab" data-toggle="tab">Post Comment</a></li>
                    

                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="getcomment">
                        <h2>Get Comment</h2>

                        
                        <pre class="SourceCode">
                        <code class="hljs javascript">
            
    var content_id=123;
    $.ajax({
       url: "/api/comment/"+content_id,
       method: "GET"
      

    }).done(function( json ) {
        console.log(json);
    });
        
                        </code>
                        </pre>

                        

                    </div>
                    <div role="tabpanel" class="tab-pane" id="postcomment">

                        <h2>Post Comment</h2>
                        <pre class="SourceCode">
                        <code class="hljs javascript">
    var id="123"; //id of content element you wish to comment;
    var comment="new comment";
    var api_key="";
    $.ajax({
            url: '/api/comment/'+id,
            data: { "text": comment, "api_key" : api_key},
            type: 'PUT',
            success: function(result) { 
                    console.log(result);
                }
            });
        
                        </code>
                        </pre>

                    </div>
                </div>
       </div></div>

    <div class="param score hide">
        <h2>Not ready jet</h2>
    </div>

    <div class="param hash hide">
        <h2>Not ready jet</h2>
    </div>


</div>


<?php
include("footer.php");
?>