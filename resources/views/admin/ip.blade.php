@extends('admin._layout')
@section('title', '登录限制 ')


@section('content')
    <div class="content">
        <table class="table table-bordered" id="userTable">
            <tr class="success">
                <td>IP 地址</td>
                <td>操作</td>
            </tr>
            @foreach($ips as $i)
                <tr>
                    <td>{{$i->ip}}</td>
                    <td>
                        <button class="editIpBtn" data-id="{{$i->id}}" data-ip="{{$i->ip}}" data-status="{{$i->status}}" >编辑</button>
                    </td>
                </tr>
            @endforeach

        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="IpEditorModal" tabindex="-1" role="dialog" aria-labelledby="UserEditorModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="UserEditorModalLabel">ip编辑</h4>
                </div>
                <div class="modal-body">
                    id
                    <input disabled="disabled" class="editId editUserInput">
                    ip地址
                    <input class="editIp editUserInput">
                    状态 0为正常 1为禁用
                    <input class="editStatus editUserInput">
                    <button type="button" onclick="editIpSubmit()" class="btn btn-primary">修改</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('style')
    <style>
        .content{width: 100%;display: flex;flex-wrap: wrap;padding: 10px 5px;}
        .content span{ padding: 5px 10px;margin: 5px;}
        .content{width: 100%;display: flex;flex-wrap: wrap;padding: 10px 5px;}
        .content span{ padding: 5px 10px;margin: 5px;}
        .model-body{display: flex;}
        .btn-primary{text-align: center;margin-left: 45%;!important;}
        .editUserInput{width: 100%;height:40px; padding: 10px;margin:5px;margin-bottom: 20px;}
    </style>
@endsection

@section('script')
    <script>


        $(document).delegate(".editIpBtn","click",function () {
            $('.editId').val($(this).attr('data-id'));
            $('.editIp').val($(this).attr('data-ip'));
            $('.editStatus').val($(this).attr('data-status'));
            $('#IpEditorModal').modal({})
        });

        /**
         * 修改ip地址
         */
        function editIpSubmit() {
            $.ajax({
                url:'/ip/edit',
                type:'put',
                data:{
                    id:$('.editId').val(),
                    ip:$('.editIp').val(),
                    status:$('.editStatus').val(),
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
        }

    </script>

@endsection