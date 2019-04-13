<head>
<title>实时监控用户鼠标移动demo</title>
</head>
<style type="text/css">
    .ii{
        background-color: pink;
        position: absolute;
    }
</style>
</head>
<body></body>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
    var left = top = 20 ;
    var ws = new WebSocket("ws://192.168.5.99:9001");
    ws.onopen = function(event) {} ;
    ws.onclose = function(event) {} ;
    ws.onmessage = function(event) {
        var event =  eval('(' + event.data + ')');
        switch(event.code)
        {
            case 1 :
                $("body").append("<div class='ii fd_"+event.data+"' style='left:50px;top:50px;'>"+event.data+"</div>") ;
                left = parseInt($('.ii').css('left')) ;
                top = parseInt($('.ii').css('top')) ;
                break ;
            case 2 :
                if($('.fd_' + event.data.client_id).length > 0)
                {
                    $('.fd_' + event.data.client_id).css({'left':event.data.left , 'top':event.data.top}) ;
                } else {
                    $("body").append("<div class='ii fd_"+event.data.client_id+"' style='left:"+event.data.left+"px;top:"+event.data.top+"px;'>"+event.data.client_id+"</div>") ;
                }
                break ;
            case 3 :
                if($('.fd_' + event.data).length > 0)
                {
                    $('.fd_' + event.data).remove() ;
                }
                break ;
        }
    };
    $(document).bind('mousemove', function (e) {
        var eLeft = parseInt(e.pageX) ;
        var eTop = parseInt(e.pageY) ;
        ws.send('{"code": 2 ,"left":'+eLeft+' , "top":'+eTop+'}') ;
        $('.ii').css({left:eLeft , top:eTop}) ;
    })
</script>
</html>