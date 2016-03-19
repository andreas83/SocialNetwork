

    
    var NotificationBox = React.createClass({
            getInitialState: function () {
            return {data:[]};
        },
        
    

        componentDidMount: function () {
           
            var socket;
            try {
                socket = new WebSocket(notification_server);

                socket.onopen = function (msg) {

                    socket.send(JSON.stringify({action: "getNotifications", auth_cookie: getCookie("auth")}));
                };
                socket.onmessage = function (msg) {
                    
                    data = JSON.parse(msg.data);
                    if(data.length>0)
                    {
                        document.getElementById("NotificationBox").style.visibility ="visible";
                        
                    }
                    else
                    {
                        document.getElementById("NotificationBox").style.visibility ="hidden";
                        
                        
                    }
                    this.setState({
                        data:data
                    });
                    
                
                }.bind(this);
                socket.onclose = function (msg) {

                };
            }
            catch (ex) {

                console.log(ex); 
            }
            
        },
        
       
       
       
        render: function(){
            var li=[];
            for(notification in this.state.data){

                notification=this.state.data[notification];
                console.log(notification);
                if (typeof JSON.parse(notification.settings).profile_picture !== "undefined")
                {
                    var img_src=upload_address+JSON.parse(notification.settings).profile_picture;
                    profile_pic = <img src={img_src} />;
                }
                else
                    profile_pic = <img src="/public/img/no-profile.jpg"/>;

                safe_username = notification.name.replace(" ", ".")
                user_link_pic = <a href={safe_username}>{profile_pic}</a>;
                user_link = <a href={safe_username}>{notification.name}</a>;

                
                li.push(<p>{user_link_pic} {user_link} <span dangerouslySetInnerHTML = {{__html: notification.message}} /></p>);
                
            }
            return(
                <div ref="notification">
                    
                    {li}
                    
                </div>
            )
        }
    });
