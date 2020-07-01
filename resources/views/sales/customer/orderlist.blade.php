@extends('sales._layout')
@section('title', '客户管理')


@section('content')
    <div class="content">
        <div id="data-dat" data-dat="{{$data}}"></div>
        <div id="main" style="width:100%;height:240px;"></div>

        <table class="table table-bordered" id="userTable">
            <tr class="success">
                <td>基本信息</td>
                <td style="min-width: 120px;">详情</td>
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
                       地址： {{$u->address??''}}<br>
                       套餐： {{$u->package??''}}<br>
                       操作用户： {{$u->user->username??'' }}<br>
                       最后操作时间： {{$u->updated_at }}<br>
                    </td>
                    <td>

                        @if ($u->type==0) <span style="color: red;">未处理</span> @endif
                        @if ($u->type==1) <span style="color: green;">成功办理</span> @endif
                        @if ($u->type==2) <span style="color: blue;">拒绝办理</span> @endif
                        @if ($u->type==3) <span style="color: grey;">其他</span> @endif
                        <br>{{$u->desc??''}}<br>
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="pull-right paginate">
            {{ $users->links() }}
        </div>
    </div>
@endsection

@section('style')
    <style>
        .content{width: 100%;display: flex;flex-wrap: wrap;padding: 10px 5px;}
        .content span{ padding: 5px 10px;margin: 5px;}
        .content .pagination li a, .content .pagination li span{float: none;}
    </style>
@endsection

@section('script')
    <script src="https://cdn.bootcss.com/echarts/3.8.5/echarts.min.js"></script>

    <script>

        var resData = JSON.parse($('#data-dat').attr('data-dat'));
        receivingOrder();
        console.log();
        function receivingOrder(){

            var myChart = echarts.init(document.getElementById('main'));
            myChart.setOption({
                series : [
                    {
                        name: '访问来源',
                        type: 'pie',    // 设置图表类型为饼图
                        radius: '65%',  // 饼图的半径，外半径为可视区尺寸（容器高宽中较小一项）的 55% 长度。
                        data:resData,
                        // 数据数组，name 为数据项名称，value 为数据项值
                    }
                ],
                color:['#749f83','#c23531', '#546570']
            })
        }


    </script>

@endsection