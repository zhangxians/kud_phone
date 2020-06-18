@extends('admin._layout')
@section('title', '登录登出记录')


@section('content')
    <div class="content">
        <table class="table table-bordered" id="userTable">
            <tr class="success">
                <td>登录登出记录</td>
            </tr>

            @foreach($logs as $log)
                <tr>
                    <td>{{$log}}   <a href="/login/log/list?user_id={{request('user_id')}}&date={{$log}}"><button style="float: right;">详情</button></a></td>
                </tr>
            @endforeach
        </table>
    </div>

@endsection

@section('style')
    <style>
        .content{width: 100%;display: flex;flex-wrap: wrap;padding: 10px 5px;}
        .content span{ padding: 5px 10px;margin: 5px;}
    </style>
@endsection

@section('script')
    <script>


    </script>
@endsection