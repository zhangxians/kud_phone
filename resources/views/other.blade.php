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
            .nav-item span{ padding: 0 20px;}
            .checked span{color: #58c6ff;}


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
            .content{width: 100%;display: flex;flex-wrap: wrap;padding: 10px 5px;}
            .content span{ padding: 5px 10px;margin: 5px;}
            .content .pagination li a, .content .pagination li span{float: none;}
            .addUserName{width: 100%;padding: 5px;}
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
            <span style="display: block;float: right;"><a href="/logout">登出</a></span>
            <span>当前 IP：<span style="color: red;">{{ $ip }}</span></span>
            <div class="nav-item" style="float: left;">
                <a href="/need/call" @if(Request::path()=='need/call') class="checked" @endif><span>待联系</span></a>
                <a href="/" @if(Request::path()=='/') class="checked" @endif ><span>拨号</span></a>
            </div>
        </div>


        <div class="content">
            <table class="table table-bordered" id="userTable">
                <tr class="success">
                    <td>基本信息</td>
                    <td style="min-width: 80px;">操作</td>
                </tr>

                @foreach($users as $u)
                    <tr>
                        <td>
                            姓名：  <span style="color:red;">{{$u->name??''}}</span><br>
                            电话：
                            @if ($u->phone1)  &nbsp;&nbsp;   {{$u->phone1??''}} @endif
                            @if ($u->phone2)  &nbsp;&nbsp;   {{$u->phone2??''}} @endif
                            @if ($u->phone3)  &nbsp;&nbsp;   {{$u->phone3??''}} @endif
                            @if ($u->phone4)  &nbsp;&nbsp;   {{$u->phone4??''}} @endif
                            @if ($u->phone5)  &nbsp;&nbsp;   {{$u->phone5??''}}  @endif


                            <br>
                            地址： {{ mb_substr($u->address??'',0,-15) }}<br>
                            套餐： {{$u->package??''}}<br>
                            备注： {{$u->desc??''}}<br>
                            操作用户： {{$u->user->username??'' }}<br>
                            最后操作时间： {{$u->updated_at }}<br>
                        </td>
                        <td>
                            <button onclick="receivingOrder('{{$u->id}}','{{$u->desc}}')" >处理</button>
                        </td>
                    </tr>
                @endforeach
            </table>
            <div class="pull-right paginate">
                {{ $users->links() }}
            </div>
        </div>

        <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="UserEditorModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="UserEditorModalLabel">订单处理</h4>
                    </div>
                    <div class="modal-body">
                        备注

                        <textarea id="orderDesc" class="addUserName"> </textarea>

                        <br><br>
                        处理类型
                        <br>
                        <select id="orderType" style="width: 50%;height: 40px;">
                            <option value="1">空号</option>
                            <option value="2">未接听</option>
                            <option value="3">无意愿</option>
                            <option value="4">有意愿，再联系</option>
                            <option value="5">待办理</option>
                        </select>
                        <br>
                        <br>
                        <button type="button" onclick="updateOrder()" class="btn btn-primary">确 定</button>
                    </div>
                    {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">关 闭</button>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>

    </body>
<script>


    var orderId = 0;
    function receivingOrder(id,desc){
        console.log(id)
        orderId = id;
        $('#orderDesc').text(desc);
        $('#orderModal').modal({})
    }

    function updateOrder() {

        var _data ={
            id:orderId,
            type:$('#orderType').val(),
            desc:$('#orderDesc').val(),
            _token:$('#_token').val(),
            t1:1
        }

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


</script>
</html>
