@extends('admin._layout')
@section('title', '客户管理')


@section('content')
    <div class="content">
    </div>
    <div id="main" style="width:100%;height:40%;"></div>
@endsection

@section('style')
    <style>
        .content{width: 100%;display: flex;flex-wrap: wrap;padding: 10px 5px;}
        .content span{ padding: 5px 10px;margin: 5px;}
    </style>
@endsection

@section('script')
    <script src="https://cdn.bootcss.com/echarts/3.8.5/echarts.min.js"></script>
    <script>

        setChart();
        function setChart(){
            $.ajax({
                url:'/customer/page/list',
                type:'get',
                data:{},
                success: function (resData) {
                    if(resData.code===0){
                        var _html = '';
                        resData.data.forEach(it=>{
                            _html+='<span>'+it.name+' : <span style="color:red;">'+it.value+'</span></span>';
                        });
                        $('.content').html(_html);
                        var myChart = echarts.init(document.getElementById('main'));
                        myChart.setOption({
                            series : [
                                {
                                    name: '访问来源',
                                    type: 'pie',    // 设置图表类型为饼图
                                    radius: '55%',  // 饼图的半径，外半径为可视区尺寸（容器高宽中较小一项）的 55% 长度。
                                    data:resData.data

                                    // 数据数组，name 为数据项名称，value 为数据项值
                                }
                            ]
                        })

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