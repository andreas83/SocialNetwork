

    
    var NotificationBox = React.createClass({
            getInitialState: function () {
            return {data:[], init:true};
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
                    if(typeof data.notificaton!="undefined")
                    {
                        //play only sound on new notifications
                        if(this.state.init===false)
                            new Audio('/public/notification.mp3').play();
                        
                        this.setState({
                            data:data.notificaton,
                            init:false
                        });
                    }
                    
                
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
