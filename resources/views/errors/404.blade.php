<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>管理中心 - 404</title>
    <link href="/css/error.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div class="figure">
    <img src="/images/404_error-illo.png" alt="Error: 404"/>
</div>
<div id="errorbox">
    <div class="not-found">
        <h1>Not Found (404)</h1> {{ $message ?? '操作错误！'}}
        <div class="not-found--links"> 您可以访问一下链接:
            <ul>
                <li><a href="/logout">退出登录</a></li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>
