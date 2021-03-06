@extends('admin._layout')
@section('title', '客户管理')


@section('content')
    <div class="content">
        <table class="table table-bordered" id="userTable">
            <tr class="success">
                <td>基本信息<button class="userAddBtn">新增用户</button></td>
                <td>操作</td>
            </tr>

        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">发送消息</h4>
                </div>
                <div class="modal-body">
                    <textarea class="message"></textarea>
                    <button type="button" onclick="setMessage()" class="btn btn-primary">发送</button>
                </div>
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">关 闭</button>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="UserEditorModal" tabindex="-1" role="dialog" aria-labelledby="UserEditorModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="UserEditorModalLabel">用户编辑</h4>
                </div>
                <div class="modal-body">
                    id
                    <input disabled="disabled" class="editId editUserInput">
                    名称
                    <input class="editUserName editUserInput">
                    密码
                    <input class="editPassword editUserInput">
                    <button type="button" onclick="editUser()" class="btn btn-primary">修改</button>
                </div>
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">关 闭</button>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>


    <div class="modal fade" id="UserAddModal" tabindex="-1" role="dialog" aria-labelledby="UserEditorModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="UserEditorModalLabel">新增用户</h4>
                </div>
                <div class="modal-body">
                    名称
                    <input class="addUserName editUserInput">
                    密码
                    <input class="addPassword editUserInput">
                    类型 1为外呼 2为业务员
                    <input class="addType editUserInput">
                    <button type="button" onclick="addUser()" class="btn btn-primary">创建</button>
                </div>
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">关 闭</button>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .content{width: 100%;display: flex;flex-wrap: wrap;padding: 10px 5px;}
        .content span{ padding: 5px 10px;margin: 5px;}
        .setMessage{}
        .model-body{display: flex;}
        .message{width:100%;height: 120px;padding: 10px;}
        .btn-primary{text-align: center;margin-left: 45%;!important;}
        .userList{display: none;}

        .editUserInput{width: 100%;height:40px; padding: 10px;margin:5px;margin-bottom: 20px;}
    </style>
@endsection

@section('script')
    <script>

        setChart();
        onWs();
        var msgId = 0;
        function setChart(){
            $.ajax({
                url:'/user/page/list',
                type:'get',
                data:{},
                success: function (resData) {
                    if(resData.code===0){
                        resData.data.forEach(it=>{
                            var _html = '            <tr>' +
                                '                <td>'+it.username+'</td>\n' +
                                '                <td>'+it.username+'</td>\n' +
                                '                <td>'+it.ip+'</td>\n' +
                                '                <td>'+it.updated_at+'</td>\n' +
                                '            </tr>';


                            var _html = '<tr>' +
                                '<td> 账号：<a href="/login/log?user_id='+it.id+'">'+it.username+'</a>'+
                                '<br><br> 今日已拨：<a href="/customer/list?user_id='+it.id+'&isAll=1" style="color:red;">'+it.customer_count+'</a>'+
                                '<br>状态：'+(it.status==0?'<span style="color:#06ad0c;">正常</span>':'<span style="color:red;">禁用中</span>')+
                                '<br>是否在线：<input class="userList" value="'+it.id+'">'+((it.online!=1&&it.username!='sadmin')?'<span style="color:#ffa509;">离线</span>':'<span style="color:#58c6ff;">在线</span>')+
                                '<br>最后登录ip：'+it.ip+
                                '<br>最后登录时间：'+it.updated_at+
                                '</td>'
                                +
                                '<td>' +
                                '<button style="margin-top:8px;" class="changeStatus" data-status="'+it.status+'" data-id="'+it.id+'">'+(it.status==0?'禁用':'启用')+'</button>' +
                                '<br>' +
                                '<button style="margin-top:8px;" class="userEditBtn" data-name="'+it.username+'" data-id="'+it.id+'">编辑</button>' +
                                '<br>' +
                                '<button style="margin-top:8px;" class="setMessage" data-status="'+it.online+'" data-id="'+it.id+'">发消息</button>' +
                                '</td>' +
                                '</tr>';


                            $('#userTable').find('tbody').eq(0).append(_html);

                        });



                    }else {
                        toast({'content':resData.msg,'time':2000});
                    }
                },
                error: function(e) {
                    toast({'content':'操作失败！','time':2000});
                }
            });

        }

        $(document).delegate(".changeStatus","click",function () {
            $.ajax({
                url:'/user',
                type:'put',
                data:{
                    id:$(this).attr('data-id'),
                    status:1-parseInt($(this).attr('data-status')),
                    _token:$('#_token').val(),
                },
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

        });


        $(document).delegate(".setMessage","click",function () {
            if($(this).attr('data-status')=='1'){
                $('#myModal').modal({})
                msgId = $(this).attr('data-id');
            }else {
                toast({'content':'该员工已经离线','time':1000});
            }

        });

        $(document).delegate(".userEditBtn","click",function () {
            $('.editId').val($(this).attr('data-id'));
            $('.editUserName').val($(this).attr('data-name'));
            $('#UserEditorModal').modal({})
        });


        /**
         * 修改user
         */
       function editUser(){
           $.ajax({
               url:'/user/edit',
               type:'post',
               data:{
                   id:$('.editId').val(),
                   name:$('.editUserName').val(),
                   password:$('.editPassword').val(),
                   _token:$('#_token').val(),
               },
               success: function (resData) {
                   toast({'content':resData.msg,'time':2000});
                   $('#UserEditorModal').modal('hide')
               },
               error: function(e) {
                   $('#UserEditorModal').modal('hide')

                   toast({'content':'操作失败！','time':2000});
               }
           });
        }

        $(document).delegate(".userAddBtn","click",function () {
            $('#UserAddModal').modal({})
        });
        /**
         * 新增user
         */
       function addUser(){
           $.ajax({
               url:'/user/create',
               type:'post',
               data:{
                   name:$('.addUserName').val(),
                   password:$('.addPassword').val(),
                   type:$('.addType').val(),
                   _token:$('#_token').val(),
               },
               success: function (resData) {
                   toast({'content':resData.msg,'time':2000});
                   $('#UserAddModal').modal('hide')
                   setTimeout(function(){window.location.reload(); }, 2000);
               },
               error: function(e) {
                   $('#UserAddModal').modal('hide')

                   toast({'content':'操作失败！','time':2000});
               }
           });
        }


        /**
         * 发送message
         */
       function setMessage(){
           $.ajax({
               url:'/user/set/message',
               type:'post',
               data:{
                   id:msgId,
                   message:$('.message').val(),
                   _token:$('#_token').val(),
               },
               success: function (resData) {
                   toast({'content':resData.msg,'time':2000});
                   $('.message').val('');
               },
               error: function(e) {
                   $('.message').val('');
                   toast({'content':'操作失败！','time':2000});
               }
           });
        }



        function onWs() {
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

                if(str.status === 1 ||str.status === '1'){
                    $('.userList').each(function () {
                        console.log($(this).val());
                        console.log(str.user_id);
                        console.log($(this).next().val());
                        if(parseInt($(this).val())===str.user_id){
                            $(this).parent().find('span').eq(1).html(str.type===1?'离线':'在线').css('color',(str.type===1)?'#ffa509':'#58c6ff');
                            $(this).parent().next().find('.setMessage').eq(0).attr('data-status',str.type===1?'0':'1');
                        }
                    })
                }else {
                    alert({ title: ' ', content: str.msg, doneText: '关闭' });
                }
                // toast({'content':event.data,'time':2000, 'style': 'background-color:#FFB800;'});
            };

            //[【于指定连接关闭后的回调函数。】
            ws.onclose = function (evt) {
                onWs();
                console.log("Connection closed.");
            };
        }




    </script>
@endsection