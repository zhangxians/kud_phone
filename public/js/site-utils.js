Array.prototype.indexOf = function(val) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == val) return i;
    }
    return -1;
};
Array.prototype.remove = function(val) {
    var index = this.indexOf(val);
    if (index > -1) {
        this.splice(index, 1);
    }
};
function isArray(o){
    return Object.prototype.toString.call(o)=='[object Array]';
}

/**
 * 包含全屏的弹出层
 * @param title 标题
 * @param url iframe地址
 * @param w 宽度
 * @param h 高度
 * @param shadeClose 是否点击遮罩关闭
 * @param callback 回调函数
 */
function layer_box(title,url,w,h,callback){
    if(w=="full"||h=="full"){
        layer_full(title,url,function(){
            if(callback){
                callback();
            }
        });
    }else{
        layer_show(title,url,w,h,false,function(){
            if(callback){
                callback();
            }
        });
    }
}

function shade_box(title,url,w,h,callback){
    if(w=="full"||h=="full"){
        layer_full(title,url,function(){
            if(callback){
                callback();
            }
        });
    }else{
        layer_show(title,url,w,h,true,function(){
            if(callback){
                callback();
            }
        });
    }
}

/**
 * 全屏弹出层
 * @param title 标题
 * @param url iframe地址
 * @param callback 回掉函数
 */
function layer_full(title,url,callback){
    callback=callback||function(){}
    var index=layer.open({
        type: 2,
        title: title,
        content: url,
        end: callback
    });
    layer.full(index);
}

/**
 * 弹出层
 * @param $title 标题
 * @param $url iframe地址
 * @param $w 宽度
 * @param $h 高度
 * @param $shadeClose 是否点击遮罩关闭
 * @param callback 回掉函数
 */
function layer_show($title,$url,$w,$h,$shadeClose,callback){
    var $area=[$w, $h];
    var config={
        type: 2
        ,maxmin: true
        ,shadeClose:true
        ,title: $title
        ,area: $area
        ,shade:0.6
        ,content: $url
        ,end: callback
    };
    if(ismobile()){
        config['maxmin']=false;
        config['shade']=0.8;
        config['closeBtn']=0;
    }
    var index=layui.layer.open(config);
    // if ($w == null || $w == '') {
    //     $w=800;
    // };
    // if ($h == null || $h == '') {
    //     $h=($(window).height());
    // };
    // callback=callback||function(){}
    // var $window_h = $().height()===0?document.body.clientHeight:$(window).height();
    // var $window_w = $(window).width()===0?document.body.clientWidth:$(window).width();
    // if(window.parent){
    //     var documentHeight = window.parent.document.documentElement.clientHeight;
    //     $window_h=documentHeight;
    // }
    // /*减去导航的宽度和banner的高度*/
    // $window_h-=52;
    // $window_w-=201;
    // /*top和left不能为负*/
    // $h=($h>$window_h?$window_h:$h);
    // $w=($w>$window_w?$window_w:$w);
    // var $area=[$w+'px', $h+'px'];
    // var $top=($window_h-$h)/2-52;
    // $top=$top>0?$top:0;
    // var $left=($window_w-$w)/2;
    // var $offset=[$top+"px",$left+"px"];
    //
    // var index=layui.layer.open({
    //     type: 2
    //     ,maxmin: true
    //     ,title: $title
    //     ,area: $area
    //     ,shade: [0.1, '#fffff']
    //     ,offset:$offset
    //     ,shadeClose:$shadeClose||false
    //     ,content: $url
    //     ,end: callback
    // });
}


/**
 * Ajax请求
 * @param _url 请求地址
 * @param _type 请求类型post、get、put、delete...
 * @param _data 传递给后台的表单数据
 * @param callback 回掉函数
 */
function req_ajax(_url,_type,_data,callback) {
    var load1=layer.load(1);
    try{
        $.ajax({
            url:_url,
            type:_type,
            data:_data,
            success: function (resData) {
                try {
                    var res =null;
                    try {
                        res =eval('('+resData+')');
                    } catch (e) {
                        res=resData;
                    }
                    dealResCode(res,callback);

                    layer.close(load1);
                } catch (e) {
                    layer.close(load1);
                    console.log(res);
                    console.log(e.message);
                }finally {
                    layer.close(load1);
                }
            },
            error: function(e) {
                console.log(e)
                layer.close(load1);
                layer.alert(e.message,{icon:2});
            }
        })
    }catch (e) {
        layer.close(load1);
        console.log(res);
        console.log(e.message);
    }
}


function dealResCode(res,callback) {
    switch(res.code){
        case 0:
            if(res.msg!=''){
                layer.msg(res.msg,{icon:1,time:1000},function () {
                    if(callback){
                        callback(res);
                    }
                });
            }else{
                if(callback){
                    callback(res);
                }
            }
            break;
        case 1:
            layer.alert(res.msg,{icon:2},function (index) {
                if(callback){
                    callback(res);
                }
                layer.close(index)
            });
            break;
        case 1001:
            layer.alert('需要退出重新登录',{icon:2}, function(index){
                top.window.location.href='/oauth/redirect/driver/epassport';
            });
            break;
    }
}


/**
 * 多标签切换组件
 */
var topWindow=$(window.top.document);
/*设置tab标签宽度*/
function tabNavallwidth(){
    var taballwidth=0,
        $tabNav = topWindow.find(".acrossTab"),
        $tabNavWp = topWindow.find(".Hui-tabNav-wp"),
        $tabNavitem = topWindow.find(".acrossTab li"),
        $tabNavmore =topWindow.find(".Hui-tabNav-more");
    if (!$tabNav[0]){return}
    $tabNavitem.each(function(index, element) {
        taballwidth+=Number(parseFloat($(this).width()+200))});
    $tabNav.width(taballwidth+205);
    var w = $tabNavWp.width();
    if(taballwidth+205>w){
        $tabNavmore.show();
    }
    else{
        $tabNavmore.hide();
        $tabNav.css({left:0});
    }
}

/**
 * 创建一个iframe，添加新窗口的时候使用
 * @param href
 * @param titleName
 */
function creatIframe(href,titleName){
    /*var topWindow=$(window.parent.document);*/
    var show_nav=topWindow.find('#min_title_list');

    show_nav.find('li').removeClass("active");
    var iframe_box=topWindow.find('#iframe_box');
    if(iframe_box.length==0){
        window.location.href=href;
    }
    show_nav.append('<li class="active"><span data-href="'+href+'">'+titleName+'</span><i>x</i></li>');
    tabNavallwidth();
    var iframeBox=iframe_box.find('.show_iframe');
    iframeBox.hide();
    /*iframe_box.append('<div class="show_iframe"><iframe frameborder="0" src='+href+'></iframe></div>');*/

    iframe_box.append('<div class="show_iframe"><div class="loading" style="top:40%;left:45%;position: absolute;width: 50px;height: 50px;' +
        '"></div></div>');

    var showBox=iframe_box.find('.show_iframe:visible');
    var iframe = document.createElement("iframe");
    iframe.frameBorder = "0";
    iframe.src =href;
    if (iframe.attachEvent){
        iframe.attachEvent("onload", function(){
            showBox.find('.loading').hide();
        });
    } else {
        iframe.onload = function(){
            showBox.find('.loading').hide();
        };
    }
    showBox.append(iframe);
}


/**
 * 添加新窗口，多标签页面
 * 例：<a data-url="" onclick="addwindow(this)" href="javascript:void(0)">name</a>
 * */
function addwindow(_this,titleName){
    var _href=$(_this).attr('data-url');
    if(_href=="#"){
        layer.msg('该模块暂未开放！',{icon:2,time:2000});
        return;
    }
    if(_href){
        var bStop=false;
        var bStopIndex=0;
        // window.history.pushState(null, null,'#'+_href);
        var _titleName=$(_this).html();
        if(titleName!=undefined){
            _titleName=titleName;
        }
        var show_navLi=topWindow.find("#min_title_list li");

        if(show_navLi.length>=20){
            aCloseIndex=1;
            $('#min_title_list').find('li').eq(aCloseIndex).remove();
            $('#iframe_box').find('.show_iframe').eq(aCloseIndex).remove();
            tabNavallwidth();

            // layer.msg('您打开的标签太多了，请关闭一些再操作！');
            // return false;
        }

        show_navLi.each(function() {
            if($(this).find('span').attr("data-href")==_href){
                bStop=true;
                bStopIndex=show_navLi.index($(this));
                return false;
            }
        });
        if(!bStop){
            creatIframe(_href,_titleName);
        }
        else{
            var iframe_box=topWindow.find("#iframe_box");
            var currentIndex=show_navLi.index($("#min_title_list li.active"));
            show_navLi.removeClass("active").eq(bStopIndex).addClass("active");
            if(currentIndex==bStopIndex){
                layer.msg('当前界面就是 '+_titleName,{icon:0,time:2000});
                iframe_box.find(".show_iframe").hide().eq(bStopIndex).show().find("iframe").attr("src",_href);
                /*
                layer.confirm('当前界面就是 '+_titleName+' 需要刷新吗？',{icon: 3, title:'提示'}, function(index){
                   iframe_box.find(".show_iframe").hide().eq(bStopIndex).show().find("iframe").attr("src",_href);
                   layer.close(index);
                });
                */
            }else{
                iframe_box.find(".show_iframe").hide().eq(bStopIndex).show();
            }
        }
    }
}
$(function(){
    $(window).resize(function () {
        tabNavallwidth();
    })
    /*标签宽度设置*/
    tabNavallwidth();
    /**
     * 标签 切换
     */
    var num=0;
    var oUl=$("#min_title_list");
    $(document).on("click","#min_title_list li",function(){
        var bStopIndex=$(this).index();
        var iframe_box=$("#iframe_box");
        $("#min_title_list li").removeClass("active").eq(bStopIndex).addClass("active");
        iframe_box.find(".show_iframe").hide().eq(bStopIndex).show();
    });
    $(document).on("click","#min_title_list li>i",function(){
        var aCloseIndex=$(this).parents("li").index();
        $(this).parent().remove();
        $('#iframe_box').find('.show_iframe').eq(aCloseIndex).remove();
        num==0?num=0:num--;
        tabNavallwidth();
    });
    $(document).on("dblclick","#min_title_list li",function(){
        var aCloseIndex=$(this).index();
        var iframe_box=$("#iframe_box");
        if(aCloseIndex>0){
            $(this).remove();
            $('#iframe_box').find('.show_iframe').eq(aCloseIndex).remove();
            num==0?num=0:num--;
            $("#min_title_list li").removeClass("active").eq(aCloseIndex-1).addClass("active");
            iframe_box.find(".show_iframe").hide().eq(aCloseIndex-1).show();
            tabNavallwidth();
        }else{
            return false;
        }
    });
    function toNavPos(){
        oUl.stop().animate({'left':-num*100},100);
    }
    $('#js-tabNav-next').click(function(){
        num==oUl.find('li').length-1?num=oUl.find('li').length-1:num++;
        toNavPos();
    });
    $('#js-tabNav-prev').click(function(){
        num==0?num=0:num--;
        toNavPos();
    });
});

/* 设置下拉选框默认选中值 */
function setSelected() {
    var hasSelected=false;
    $('.layui-form select').each(function () {
        var $v=$(this).attr('data-selected');
        if(typeof($v)=="string"){
            hasSelected=true;
            $(this).val($v);
        }
    });
    if(hasSelected){
        layui.form.render('select');
    }
}

/* Tips */
$(function () {
    var tip_layer=null;
    $('.help-tip').hover(function () {
        $(this).addClass('help-tip-s1')
        tip_layer=layer.tips($(this).attr('data-tip'),$(this), {
            tips: [4, '#e36159']
        });
    },function () {
        $(this).removeClass('help-tip-s1')
        layer.close(tip_layer)
    })
});

/*
多级字段处理
 */
var laytable_field=function(d){
    try {
        var field='d.'+this.field;
        var item=eval((field));
        if(item){
            return item;
        }
        return '';
    }catch (e){
        return '';
    }
}

// 密码强度
function password_score(sValue) {
    var modes = 0;
    //正则表达式验证符合要求的
    if (sValue.length < 8) return 0;
    if (/\d/.test(sValue)) modes++; //数字
    if (/[a-z]/.test(sValue)) modes++; //小写
    if (/[A-Z]/.test(sValue)) modes++; //大写
    if (/\W/.test(sValue)) modes++; //特殊字符
    if (sValue.length >=12) modes++;

    return modes;
}

function ismobile() {
    if ((navigator.userAgent.match(/(phone|pad|pod|iPhone|iPod|ios|iPad|Android|Mobile|BlackBerry|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS|Symbian|Windows Phone)/i))){
        return true;
    }
    return false;
}
