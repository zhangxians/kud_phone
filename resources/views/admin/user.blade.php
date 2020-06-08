@extends('admin._layout')
@section('title', '客户管理')


@section('content')
    <div class="content">
        <table class="table table-bordered" id="userTable">
            <tr class="success">
                <td>基本信息</td>
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
@endsection

@section('style')
    <style>
        .content{width: 100%;display: flex;flex-wrap: wrap;padding: 10px 5px;}
        .content span{ padding: 5px 10px;margin: 5px;}
        .setMessage{}
        .model-body{display: flex;}
        .message{width:100%;height: 120px;padding: 10px;}
        .btn-primary{text-align: center;margin-left: 45%;!important;}
    </style>
@endsection

@section('script')
    <script>

        setChart();
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
                                '<td> 账号：'+it.username+
                                '<br> 今日已拨：<span style="color:red;">'+it.customer_count+'</span>'+
                                '<br>状态：'+(it.status==0?'<span style="color:#06ad0c;">正常</span>':'<span style="color:red;">禁用中</span>')+
                                '<br>是否在线：'+((it.online!=1&&it.username!='sadmin')?'<span style="color:#ffa509;">离线</span>':'<span style="color:#58c6ff;">在线</span>')+
                                '<br>最后登录ip：'+it.ip+
                                '<br>最后登录时间：'+it.updated_at+
                                '</td>'
                                +
                                '<td>' +
                                '<button style="margin:auto;" class="changeStatus" data-status="'+it.status+'" data-id="'+it.id+'">'+(it.status==0?'禁用':'启用')+'</button>' +
                                '<br><br>' +
                                '<button style="margin:auto;" class="setMessage" data-status="'+it.online+'" data-id="'+it.id+'">发消息</button>' +
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

            console.log();
            console.log();
        });


        $(document).delegate(".setMessage","click",function () {
            if($(this).attr('data-status')=='1'){
                $('#myModal').modal({})
                msgId = $(this).attr('data-id');
            }else {
                toast({'content':'该员工已经离线','time':1000});
            }

        });


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
    </script>
@endsection