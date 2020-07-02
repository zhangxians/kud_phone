@extends('sales._layout')
@section('title', '客户管理')


@section('content')
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
                        <option value="0">待处理</option>
                        <option value="1">成功办理</option>
                        <option value="2">拒绝办理</option>
                        <option value="3">再联系</option>
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

@endsection

@section('style')
    <style>
        .content{width: 100%;display: flex;flex-wrap: wrap;padding: 10px 5px;}
        .content span{ padding: 5px 10px;margin: 5px;}
        .content .pagination li a, .content .pagination li span{float: none;}
        .addUserName{width: 100%;padding: 5px;}
    </style>
@endsection

@section('script')
    <script>


        var orderId = 0;
        function receivingOrder(id,desc){
            console.log(id)
            orderId = id;
            $('#orderDesc').text(desc);
            $('#orderModal').modal({})
        }
        
        function updateOrder() {

            const _data = {
                id:orderId,
                type:$('#orderType').val(),
                desc:$('#orderDesc').val(),
                _token:$('#_token').val(),
            };

            $.ajax({
                url:'/order/update',
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

@endsection