
    var GroupBox = React.createClass({
            getInitialState: function () {

            return {data: []};
        },
        componentDidMount: function () {
 
            this.loadGroupsFromServer();
         
        },

        loadGroupsFromServer: function () {
            $.ajax({
                url: '/api/group/',
                dataType: 'json',
                cache: false,
                success: function (data) {
                    this.setState({data: data});
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log(err.toString());
                }.bind(this)
            });
        },
        showGroup: function(group,event) {
           
        },
       
        clickFollow: function(group_id, e){
            if(user_id==0)
            {
                window.location.href="/user/register/";
            }
            $.ajax({
                url: '/api/group/'+group_id+'/add/'+user_id,
                dataType: 'json',
                type: 'POST',
                
                success: function(data) {
                  this.setState({data: data});
                }.bind(this),
                error: function(xhr, status, err) {
                  console.error(this.props.url, status, err.toString());
                }.bind(this)
              });

        },
        render: function(){
            rows=[];
            numrows= this.state.data.length;
            
                    
            for (var i=0; i < numrows; i++) {
                group=this.state.data[i];
                img_src=upload_address+group.image;
                
                rows.push(
                <div onClick={this.showGroup.bind(this, group)} className="col-md-6 GroupItem">
                    <div className="col-md-4">
                        <img className="img-responsive" src={img_src} />
                    </div>
                    <div className="col-md-8">
                        <div className="headerline">
                            <h1>{group.name}</h1>
                        </div>
                        <div className="follower"><i className="fa fa-users" aria-hidden="true"></i>  {group.cnt} follower</div>
                        <div className="follower"><i className="fa fa-envelope-o" aria-hidden="true"></i> {group.content_cnt} posts</div>
                    </div>
                </div>);
                

               
                
            }
            return(
            
            <div className="GroupBox">
               {rows}
            </div>
            
            
            )
        }
    });
