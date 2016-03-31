

    var socket;
    var ChatBox = React.createClass({
            getInitialState: function () {
            return {channel: [], activeUser: []};
        },

        componentDidMount: function () {
            


            try {
                socket = new WebSocket(notification_server);

                socket.onopen = function (msg) {

                    socket.send(JSON.stringify({action: "openroom", auth_cookie: getCookie("auth")}));
                };
                socket.onmessage = function (msg) {
                    
                    data = JSON.parse(msg.data);

                    
                    this.setState({activeUser: Object.keys(swap(data.activeUsers)), channel: data.channel.default});
                    var objDiv = document.getElementById("textframe");
                    objDiv.scrollTop = objDiv.scrollHeight;
                }.bind(this);
                socket.onclose = function (msg) {

                };
            }
            catch (ex) {

                console.log(ex); 
            }
            
        },
        
        handleSubmit: function(event) {
            event.preventDefault();
            socket.send(JSON.stringify({action: "chat", text: document.getElementById("chatinput").value,  auth_cookie: getCookie("auth")}));
            document.getElementById("chatinput").value="";
            var objDiv = document.getElementById("textframe");
            objDiv.scrollTop = objDiv.scrollHeight;
        },
       
       
        render: function(){
            
            return(
            <div>
                
                <div id="chat" className="col-md-9 bounceIn">
                    <div id="textframe">
                        {this.state.channel.map(function(chat, i) {
                        chat=Replacehashtags(chat);
                        return (<p dangerouslySetInnerHTML = {{__html: chat}}/>)
                        })}
                    </div>
                    <form className="chatForm" onSubmit={this.handleSubmit}>
                    <input type="text" autoComplete="off" id="chatinput" />
                    </form>
                </div>
                <div id="ChatUsers" className="col-md-3">
                <ul>
                {this.state.activeUser.map(function(user, i) {
                    return (<li><span dangerouslySetInnerHTML = {{__html: user}} /></li>)
                })}
                </ul>
                </div>
                
                
                
            </div>)
        }
    });
