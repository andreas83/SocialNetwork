
    var GroupBox = React.createClass({
            getInitialState: function () {
            return {data: []};
        },
        componentDidMount: function () {
            
          
        },

        loadCommentsFromServer: function () {
            $.ajax({
                url: '/api/groups/' + this.props.id,
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
        handleChange: function(event) {
           
        },
       
        
        render: function(){
            
            return(
            
            <div className=" ">
               
            </div>
            
            
            )
        }
    });
