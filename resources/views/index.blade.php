<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>宽带拨号记录</title>
        <style>
            html, body {
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }
            .header{ background-color: #393d49;  width: 100%;   height:50px; line-height: 50px; }
            .header span{ color: white;float: right;margin-right:10px;}
            .content{width:95%;min-height: 20px;margin:30px auto;}
            .content .content-list{padding: 10px 10px;display: flex;}
            .content .content-list span{font-size: 16px;display: block;}
            .content .content-list .title{width: 20%;color: #666e72;}
            .content .content-list .value{width: 70%;letter-spacing: 1px;color: #000000;}
            .content .content-list a{background-color: #393d49;color: white;text-decoration:none;
                letter-spacing: 3px;padding: 2px 15px;border-radius: 8px;margin-top: -5px;margin-left: 20px;}
            .operation{
                width:95%;min-height: 20px;margin:30px auto;
            }
            .operation .operation-btn{
                display: flex;flex-wrap: wrap;justify-content: space-around;
            }
            .operation .operation-btn button{
                margin:15px;height: 40px;width: 35%;
            }
        </style>

     <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
     <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
     <script src="/js/message.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="header">
            <input id="ti" hidden value="{{ $ti }}">
            <input id="id" hidden value="{{ $user['id'] }}">
            <input id="_token" hidden value="{{ csrf_token() }}">
            <span style="display: block;float: right;"><a href="/logout">登出</a></span>
            <span>当前 IP：<span style="color: red;">{{ $ip }}</span></span>
        </div>


        <div class="content">

            <div class="content-list">
                <span class="title">姓 名 : </span>
                <span class="value">{{ $user['name'] }}</span>
            </div>
            <div class="content-list">
                <span class="title">电 话 : </span>
                <span class="value">
                   @if(isset($user['phone1'])&&$user['phone1']) <p>{{ $user['phone1'] }} <a href="tel:{{ $user['phone1'] }}">拨号</a></p> @endif
                   @if(isset($user['phone2'])&&$user['phone2']) <p>{{ $user['phone2'] }} <a href="tel:{{ $user['phone2'] }}">拨号</a></p> @endif
                   @if(isset($user['phone3'])&&$user['phone3']) <p>{{ $user['phone3'] }} <a href="tel:{{ $user['phone3'] }}">拨号</a></p> @endif
                   @if(isset($user['phone4'])&&$user['phone4']) <p>{{ $user['phone4'] }} <a href="tel:{{ $user['phone4'] }}">拨号</a></p> @endif
                   @if(isset($user['phone5'])&&$user['phone5']) <p>{{ $user['phone5'] }} <a href="tel:{{ $user['phone5'] }}">拨号</a></p> @endif
                </span>

            </div>
            <div class="content-list">
                <span class="title">地 址 : </span>
                <span class="value">{{ $user['address'] }}</span>
            </div>
            <div class="content-list">
                <span class="title">套 餐 : </span>
                <span class="value">{{ $user['package'] }}</span>
            </div>
        </div>

        <div class="operation">
            <h5 class="page-header"></h5>
            <div style="width: 80%;margin:auto;">
                <textarea id="desc" class="form-control" rows="3" placeholder="可在此填写备注"></textarea>
            </div>
            <div class="operation-btn">
                <button type="button" onclick="nextUser(1)" class="btn btn-primary">空号</button>
                <button type="button" onclick="nextUser(2)" class="btn btn-success">未接听</button>
                <button type="button" onclick="nextUser(4)" class="btn btn-warning">有意愿，再联系</button>
                <button type="button" onclick="nextUser(5)" class="btn btn-danger">待办理</button>
                <button type="button" onclick="nextUser(3)" class="btn btn-info">无意愿</button>
                {{--<button type="button" onclick="nextUser(0)" class="btn btn-default">跳过</button>--}}
            </div>
        </div>
    </body>
<script>
    function nextUser(type){
        console.log(type)
        let t1 =  parseInt($('#ti').val());
        let t2 =  parseInt((new Date()).getTime()/1000);
        if(t1+10 > t2){
            toast({'content':'间隔时间太短，请在 '+(t1+10-t2)+'秒后再次点击。','time':1000});
            return false;
        }
        var _data ={
            id:$('#id').val(),
            type:type,
            desc:$('#desc').val(),
            _token:$('#_token').val(),
            t1:t1
        };
        $.ajax({
            url:'/update',
            type:'post',
            data:_data,
            success: function (resData) {
                if(resData.code===0){
                    toast({'content':resData.msg,'time':1000});
                    setTimeout(function(){window.location.reload(); }, 1000);
                }else {
                    toast({'content':resData.msg,'time':2000});
                }
            },
            error: function(e) {
                toast({'content':'操作失败！','time':2000});
            }
        });
    }

    var ws = new WebSocket("ws://180.76.100.199/ws?token="+window.localStorage.getItem('token'));
    //readyState属性返回实例对象的当前状态，共有四种。
    //CONNECTING：值为0，表示正在连接。
    //OPEN：值为1，表示连接成功，可以通信了。
    //CLOSING：值为2，表示连接正在关闭。
    //CLOSED：值为3，表示连接已经关闭，或者打开连接失败
    //例如：if (ws.readyState == WebSocket.CONNECTING) { }

    //【用于指定连接成功后的回调函数】
    ws.onopen = function (evt) {
        console.log("Connection open ...");
       // alert("Hello WebSockets!");

        // ws.send("Hello WebSockets!");
    };
    //ws.addEventListener('open', function (event) {
    //    ws.send('Hello Server!');
    //};

    //【用于指定收到服务器数据后的回调函数】
    //【服务器数据有可能是文本，也有可能是二进制数据，需要判断】
    ws.onmessage = function (event) {
        console.log(event.data);
        var str = JSON.parse(event.data);
        alert({ title: ' ', content: str.msg, doneText: '关闭' });
       // toast({'content':event.data,'time':2000, 'style': 'background-color:#FFB800;'});
    };

    //[【于指定连接关闭后的回调函数。】
    ws.onclose = function (evt) {
        console.log("Connection closed.");
    };
</script>
</html>
