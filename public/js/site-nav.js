/*! jquery.cookie v1.4.1 | MIT */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?a(require("jquery")):a(jQuery)}(function(a){function b(a){return h.raw?a:encodeURIComponent(a)}function c(a){return h.raw?a:decodeURIComponent(a)}function d(a){return b(h.json?JSON.stringify(a):String(a))}function e(a){0===a.indexOf('"')&&(a=a.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return a=decodeURIComponent(a.replace(g," ")),h.json?JSON.parse(a):a}catch(b){}}function f(b,c){var d=h.raw?b:e(b);return a.isFunction(c)?c(d):d}var g=/\+/g,h=a.cookie=function(e,g,i){if(void 0!==g&&!a.isFunction(g)){if(i=a.extend({},h.defaults,i),"number"==typeof i.expires){var j=i.expires,k=i.expires=new Date;k.setTime(+k+864e5*j)}return document.cookie=[b(e),"=",d(g),i.expires?"; expires="+i.expires.toUTCString():"",i.path?"; path="+i.path:"",i.domain?"; domain="+i.domain:"",i.secure?"; secure":""].join("")}for(var l=e?void 0:{},m=document.cookie?document.cookie.split("; "):[],n=0,o=m.length;o>n;n++){var p=m[n].split("="),q=c(p.shift()),r=p.join("=");if(e&&e===q){l=f(r,g);break}e||void 0===(r=f(r))||(l[q]=r)}return l};h.defaults={},a.removeCookie=function(b,c){return void 0===a.cookie(b)?!1:(a.cookie(b,"",a.extend({},c,{expires:-1})),!a.cookie(b))}});

/**
 * 主要存放iframe以外的js，包括导航，页面宽高适配
 */
/*用js控制导航与右边iframe的宽高*/
function resize_nav(){
    //var $window_w = $(window).width()===0?document.body.clientWidth:$(window).width();
    var $window_h = $(window).height()===0?document.body.clientHeight:$(window).height()-100;
    $('.sidebar').css({ 'minHeight': $window_h});
    //var $nav_w=$(".nav").width();
    //$(".contents,#js_iframe_list").width($window_w - $nav_w);
    //$(".contents,#js_iframe_list").height($window_h);
}

/*左侧菜单-隐藏显示*/
function displaynavbar(obj){
    if($(obj).hasClass("open")){
        $(obj).removeClass("open");
        $(".sidebar").animate({'width':'200'},300)
        $(".main-content").animate({'left':'200'},300)

        $('.menu-str').show();
        //$(".nav").width(200);

        $.cookie('displaynavbar', '1', { expires: 30 });
    }else{
        $(obj).addClass("open");
        $(".sidebar").animate({'width':'45'},300)
        $(".main-content").animate({'left':'45'},300)

        $('.submenu').hide();
        $('.menu-str').hide();
        //$(".nav").hide();
        //$(".nav").width(0);

        $.cookie('displaynavbar', '0', { expires: 30 });
    }
    resize_nav();
}

function navCookieInit() {
    var isshow=$.cookie('displaynavbar');
    if(isshow!=null){
        if(isshow=='0'){
            $('.dislpayArrow>a').addClass("open");
            $(".sidebar").css({'width':'45px'})
            $(".main-content").css({'left':'45px'})
            $('.submenu').hide();
            $('.menu-str').hide();
        }
    }
}

function hidenavbar() {
    $('.dislpayArrow>a').addClass("open");
    $(".sidebar").animate({'width':'45'},300)
    $(".main-content").animate({'left':'45'},300)

    $('.submenu').hide();
    $('.menu-str').hide();
}


$(function () {
    /*用js控制导航与右边iframe的宽高*/
    resize_nav();
    /*当窗口大小发生变化时*/
    $(window).resize(function () {
        resize_nav();
    })
    navCookieInit();


    var $nav_tips_layer;

    /** 菜单的hover效果 **/
    $('.sidebar-menu li a').hover(function () {
        $(this).parent('li').siblings('li').find('.menu-content-hover').stop(false, true).animate()
        if (!$(this).parent('li').hasClass('active')) {
            $(this).find('.menu-content-hover').animate({ 'left': '0px' }, 250)
        }
        if($nav_tips_layer!=undefined)
            layer.close($nav_tips_layer);
        var $submenu = $(this).siblings('.submenu')
        if ($submenu.length > 0) {
            // console.log($('.sidebar').width());
            if($('.sidebar').width()<60){
                $nav_tips_layer=layer.tips("<ul class='sort-submenu'>"+$submenu.html()+"</ul>", $(this),{
                    tips: [2, '#131e25'],
                    time: 10000
                });
            }
        }
    }, function () {
        if (!$(this).parent('li').hasClass('active')) {
            $(this).find('.menu-content-hover').animate({ 'left': '-43px' }, 150)
        }
    });

    /** 菜单点击事件 **/
    $('.sidebar-menu li a').click(function () {
        $li = $(this).parent('li')
        $li.siblings('li').find('.menu-content-hover').css({ 'left': '-43px' })
        $li.siblings('li').find('.menu-content-hover').stop(false, true).animate()
        //关闭其它的
        $li.siblings('li').find('.submenu').hide()
        $li.addClass('active').siblings('li').removeClass('active')

        //设置顶部线的颜色
        var color = $(this).find('.menu-content-hover').css('backgroundColor')
        $('.header').css({ 'borderTopColor': color })
        //子菜单
        var $submenu = $(this).siblings('.submenu')
        if ($submenu.length > 0) {
            // if($('.sidebar').width()>60){
            //     $submenu.show(200)
            // }
            $submenu.slideToggle(200)//IE7下面有问题
            // if($submenu.attr("style").toLowerCase().indexOf('display: none')>-1){
            //     $submenu.hide(200)
            // }else{
            //     $submenu.show(200)
            // }
        } else {
            // //TODO::写一个loading
            // //页面跳转
            addwindow(this);
            return false;
            //$('.main-iframe').attr('src',$(this).attr('data-url'))
        }
    });
    $(document).on('click','.sort-submenu a',function () {
        $('.menu-content-hover').css({ 'left': '-43px' })
        addwindow(this);
        return false;
    }).on('mouseleave','.sort-submenu',function () {
        layer.close($nav_tips_layer);
    })


});

