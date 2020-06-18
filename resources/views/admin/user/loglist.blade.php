@extends('admin._layout')
@section('title', '登录登出记录')


@section('content')
    <div class="content">
        <table class="table table-bordered" id="userTable">
            <tr class="success">
                <td>登录登出记录</td>
            </tr>
        </table>
        <ul class="layui-timeline">

            @foreach($logs as $k=>$log)
                @if($log['type']==0)
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                        <div class="layui-timeline-content layui-text">
                            <div class="layui-timeline-title">{{ $log['created_at'] }}
                                &nbsp;&nbsp;&nbsp;&nbsp;<span style="color: green;">登录</span></div>
                        </div>
                    </li>
                @elseif($log['type']==1)
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                        <div class="layui-timeline-content layui-text">
                            <div class="layui-timeline-title">{{ $log['created_at'] }}
                                &nbsp;&nbsp;&nbsp;&nbsp;<span style="color: red;">退出</span></div>
                        </div>
                    </li>
                @endif
            @endforeach

        </ul>

    </div>

@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="https://www.layuicdn.com/layui/css/layui.css"/>
    <style>
        .content {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            padding: 10px 5px;
        }

        .content span {
            padding: 5px 10px;
            margin: 5px;
        }
    </style>
@endsection

@section('script')
    <script src="https://www.layuicdn.com/layui/layui.js"></script>
    <!--您的Layui代码start-->
    <script type="text/javascript">

    </script>
@endsection