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
            .content{width:85%;min-height: 20px;margin:130px auto;}
            .content .content-list{padding: 10px 10px;display: flex;}
            .btn-info{width: 100%;margin-top: 30px;height: 40px;}

        </style>

     <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
     <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
     <script src="/js/message.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="header">
            <input id="_token" hidden value="{{ csrf_token() }}">
            <span>当前 IP：<span style="color: red;">{{ $ip }}</span></span>
        </div>


        <div class="content">
            <form>
                <div class="form-group">
                    <label for="username">登录账号</label>
                    <input type="email" class="form-control" id="username" placeholder="登录账号">
                </div>
                <div class="form-group">
                    <label for="password">登录密码</label>
                    <input type="password" class="form-control" id="password" placeholder="Password">
                </div>
                <button type="button" onclick="login()" class="btn btn-info">立 即 登 录</button>
            </form>
        </div>


    </body>
<script>
    function login(){
        var _data ={
            username:$('#username').val(),
            password:$('#password').val(),
            _token:$('#_token').val(),
        };
        $.ajax({
            url:'/login',
            type:'post',
            data:_data,
            success: function (resData) {
                if(resData.code===0){
                    toast({'content':resData.msg,'time':1000});
                    setTimeout(function(){window.location.href=resData.data?resData.data:'/'; }, 1000);
                }else {
                    toast({'content':resData.msg,'time':2000});
                }
            },
            error: function(e) {
                toast({'content':'操作失败！','time':2000});
            }
        });
    }
</script>
</html>
