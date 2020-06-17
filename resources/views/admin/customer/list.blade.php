@extends('admin._layout')
@section('title', '客户管理')


@section('content')
    <div class="content">
        <table class="table table-bordered" id="userTable">
            <tr class="success">
                <td>基本信息</td>
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
                       {{--ip： {{$u->ip??''}}<br>--}}
                       操作用户： {{$u->user->username??'' }}<br>
                       最后操作时间： {{$u->updated_at }}<br>
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="pull-right paginate">
            {{ $users->appends(['type'=>$type,'user_id'=>$user_id,'isAll'=>$isAll])->links() }}
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

@endsection