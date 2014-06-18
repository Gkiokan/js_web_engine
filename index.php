<?  /* * * * * * * * * * * * * * * *
     *  Project: Javascript Web Engine (jsWE)
     *  Author: Gkiokan
     *  Date: 04.05.2014
     *  Comment: Game Engine for DOM Webpage.
     *  * * * * * *  * * * * * * * * * * * * */

     
    // Setup some Variables
    
    // Define Querys
    
    
    
    /**** Level Shema *****
     *  BACKGROUND height:100%; bottom:200px;
     *  MIX 50px;
     *  STREET height:200px; bottom:50px;
     *  MIX 50px;
     *  FOREGROUND height:100px; bottom:0px;
     *
     **** END Level Shema **/

?>

<!doctype>
<html>
    <head>
        <title>Javascript Web Engine</title>
        <style>
            body {
                margin:0px;
                padding:0px;
                position: relative;
                overflow: hidden;
                color: #eee;
                width: 100%;
                height: 100%;
                font-family: Verdana;
            }
            
            div {
                -webkit-transition: all .01667s; /* For Safari 3.1 to 6.0 */
                transition: all .01667s;
            }
            #engine {
                display: block;
                position: relative;
                width:100%;
                height: 100%;
                overflow: hidden;
                border: 0px double red;
                background: #222;
            }
            
            #hud {
                display: block;
                z-index:101;
                position: absolute;
                top: 0px; left:0px; right:0px;
                background: rgba(10,10,10,.4);
                height:100px;
            }
            #hd_symbol { color:red; font-weight:bold; }
            
            
            #updates {
                position: absolute;
                width:450px; height:500;
                overflow: auto;
                display: block;
                border: 1px dotted #ccc;
                padding: 10px;
                top:10px; right:10px;
                overflow-x: hidden;
                background: rgba(10,10,10,.3);
            }
            
            /* Player Settings */
            #player {
                width:20px; height:70px;
                display: block;
                z-index: 99;
                position: absolute;
                bottom: 70px; left:150px;
                background: #fff;
            }
            /* Level Settings */
            #level { width:1200px; }
            #level div {
                display: block;
                border: 1px solid #fff;
                left:0px; right:0px;
                font-weight: bold;
            }
            
            #level .foreground {
                position: absolute;
                bottom: 0px;
                background: rgba(100,220,100,.4);
                height:100px;
                z-index:100;
                background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAfElEQVQYV2PkmsklyYAEXPSkn+259FQKWQzEZkRWCFIEU4CumASFU7j+g0xxMZZGt41hz9mnEDFWBilGLmIUgt1IhELOW9KYCkHWITvj6HKI7Yx+x1XBboQB80kvGE7mSaC4F6SYUTgPVWHR6xcMfaKoCsEmoivE8DpUAADdzDEDcHruAgAAAABJRU5ErkJggg==);
            }
            
            #level .street {
                position: absolute;
                bottom: 50px;
                background: rgba(170,110,110,.4);
                height:200px;
                z-index:99;
                background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAAWUlEQVQYV2NkYGAwBuKzXMWt/4E0w7feaikg9RzGZwQJIkmi8EFyjEiSJsgmQU1jBCsAGouhEyomCZKQRLYTphNmNS6dxkCTz6C4AV0nihuQJCWBOp8h8Y0BHzc8WZGx9kMAAAAASUVORK5CYII=);
            }
            
            #level .background {
                position: absolute;
                bottom: 200px;
                background: rgba(60,50,200,.4);
                height: 100%;
                z-index:98;
                background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAATklEQVQYV2PkKm5NYyACMIIUfuutnoVLLcwgvAphikAG4VSIrAhkG1aF6IqwKsSmCEMhcgigexBuNbJJ2DSAFcKCBt0UDF+DFOIKS5hiAAMfSEP+XsKeAAAAAElFTkSuQmCC);
            }
        </style>
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script>
            
        </script>
    </head>
    <body>
        
        
        <div id='engine'>
            <div id='hud'>
                <div id='hp'>
                    <span id='hd_txt'>Health Points</span>
                    <span id='hd_symbol'>&#9829 &#9829 &#9829</span>
                </div>
                <div id='debug'></div>
                <div id='player_pos'></div>
                <div id='updates'><? echo "<pre>"; include_once('updates.jsWE'); echo"</pre>"; ?></div>
            </div>
            
            
            <div id='level'>
                <div class='background'>&nbsp; BG (Background and Scene 3rd Perspective)</div>
                <div class='foreground'>&nbsp; FG (Grass and Something 1st Perspective)</div>
                <div class='street'>STREET</div>
            </div>
            
            <div id='player'></div>
        </div>
        
        <script>
            //function init(){
            //setTimeout(function() {
            //window.requestAnimationFrame(init);    
                
            // Setting up Stuff
            var player = $('#player');
            var playerH = player.css('left');
            var playerV = player.css('bottom');;
            var debugDiv = $('#debug');
            var playerPosDiv = $('#player_pos');
            var speed = 10;
            var interval = 1000 / 60;
            var playerMoving = 1;
            
            // Control config [keypress|keydown]
            var keyW = 119; //87;
            var keyA = 97; // 65;
            var keyS = 115; //83;
            var keyD = 100; //68;
            
            /* #Combolist feature
                keypress -> push KeyCode in array
                if KeyCode is in array do nothing
                keyup -> remove KeyCode from array
                if keypress inTime -> double click KeyCode
                http://jsfiddle.net/vor0nwe/mkHsU/
            */
            
            function debug(input){ debugDiv.html(input); }
            
            function movePlayer(direction, n){
                var playerMoving = setInterval(function(){ 
                var IsPlayerInStreet = checkStreet(direction, n);
                    if(IsPlayerInStreet) {
                        debug('Running '+playerMoving);
                        player.css(direction, n+'='+speed+'px');
                        playerPosDiv.html(parseInt(player.css('left'))+' : '+parseInt(player.css('bottom')));
                    }
                }, interval);
            }
            
            function stopMoving(m){
                clearInterval(m);
                for (var i = 1; i < 999; i++)
                window.clearInterval(i);
                console.log(i);
                debug('Running Stopped '+i+' '+m);
            }
            
            function checkStreet(d, n){
                //console.log(d,n,player.position());
                var moveplayer_check = true;
                var StreetTop = 240;
                var StreetBottom = 60;
                
                // Collision Top Street
                if (d=='bottom' && parseInt(player.css('bottom'))>=StreetTop && n=='+') {
                    debug('StreetTop reached '+StreetTop);
                    moveplayer_check = false;
                }
                if (d=='bottom' && parseInt(player.css('bottom'))<=StreetBottom && n=='-') {
                    debug('StreetBottom reached '+StreetBottom);
                    moveplayer_check = false;
                }
                
                if (!moveplayer_check) {
                    stopMoving(playerMoving)
                }
                
                return moveplayer_check;
            }
            
            function checkControl(c){
                //console.log('player '+player);
                //console.log('checkControl '+c);
                if(c==keyW) { movePlayer('bottom', '+'); } // player.css('bottom', '+='+speed+'px') }
                if(c==keyS) { movePlayer('bottom', '-'); } // player.css('bottom', '-='+speed+'px') }
                if(c==keyA) { movePlayer('left', '-'); } // player.css('left', '-='+speed+'px') }
                if(c==keyD) { movePlayer('left', '+'); } // player.css('left', '+='+speed+'px') }
            }
            
            window.addEventListener('keypress', function(e){
                e.preventDefault();
                //console.log(e);
                keyCode = e.keyCode || window.e.keyCode;
                checkControl(keyCode);
                //debug('keydown event '+keyCode);
            });
            
            window.addEventListener('keyup', function(u){
                stopMoving(playerMoving);
            });
            
            //}, 1000 / 60); }
            //init();
        </script>
        
    </body>
</html>