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
@endsection

@section('style')
    <style>
        .content{width: 100%;display: flex;flex-wrap: wrap;padding: 10px 5px;}
        .content span{ padding: 5px 10px;margin: 5px;}
    </style>
@endsection

@section('script')
    <script>

        setChart();
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
                                '<td> 姓名：'+it.username+
                                '<br>状态：'+(it.status==0?'<span style="color:#06ad0c;">正常</span>':'<span style="color:red;">禁用中</span>')+
                                '<br>是否在线：'+(it.online==1?'<span style="color:#58c6ff;">在线</span>':'<span style="color:#ffa509;">离线</span>')+
                                '<br>账号：'+it.username+
                                '<br>最后登录ip：'+it.ip+
                                '<br>最后登录时间：'+it.updated_at+
                                '</td>'
                                +
                                '<td>' +
                                '<button style="margin:auto;" class="changeStatus" data-status="'+it.status+'" data-id="'+it.id+'">'+(it.status==0?'禁用':'启用')+'</buttom>' +
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
        })
    </script>
@endsection