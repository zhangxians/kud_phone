<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>宽带拨号记录 - @yield('title')</title>
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
            .nav-item span{ padding: 0 20px;}
            .checked span{color: #58c6ff;}
        </style>
        @yield('style')
     <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    </head>
    <body>
    <input id="_token" hidden value="{{ csrf_token() }}">

    <div class="header">
            <span style="display: block;float: right;"><a href="/logout">登出</a></span>
        <div class="nav-item" style="float: left;">
            <a href="/user" @if(Request::path()=='user') class="checked" @endif ><span>员工账号</span></a>
            <a href="/ip" @if(Request::path()=='ip') class="checked" @endif><span>登录限制</span></a>
            <a href="/customer" @if(Request::path()=='customer') class="checked" @endif><span>用户</span></a>
        </div>

        </div>


    @yield('content')

    </body>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="/js/message.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    @yield('script')
</html>
