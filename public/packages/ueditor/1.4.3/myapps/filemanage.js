/**
 * Created by HaiMian on 2015/9/23.
 */

var domain='http://newoa.maccura.com';
//-----------附件管理
function opIFrame() {
    var src=domain+'/files/filelistbyother.asp?fresh='+Math.random();
    var iframe7html = '<div  class="reveal-modal">' +
        '<iframe style="width:760px;height:510px;border:solid 0px;" src="' + src + '"></iframe>' + '</div>';
    var d = dialog({
        id:"ifram-file",
        title: "选择附件",
        content: iframe7html,
        quickClose: false
    });
    d.showModal();
}

function opIFrameForImg() {
    var dialoghtml="<div id='upimg_setimgDiv'><ul style='list-style: none;line-height: 30px;'><li>选择图片：<span id='upimg_selimgbox'><input type='button' onclick='opIFrame()' value='选择..'/></span></li>" +
        "<li>长度：<input type='text' style='width: 50px;' id='upimg_width' value=''/> px　宽度：<input type='text' id='upimg_height' style='width: 50px;' value=''/> px</li>" +
        "<li style='margin-top: 10px;'><input type='button' style='margin-left: 120px;' id='upimg_ok' onclick='upimg_ok()' value='确定'/>　<input type='button' onclick='ipimg_cancle()' value='取消'/></li></ul></div>";
    var d = dialog({
        id:"dialog-img",
        title: "选择图片",
        content: dialoghtml,
        quickClose: false
    });
    d.showModal();
}
//关闭窗口
function closeIFrame(iframeid) {
    dialog.get(iframeid).close().remove();
}

function upimg_ok(){
    var imgurl=$("#upimg_ok").attr('data-imgurl');
    if(imgurl){
        var upimg_w=$("#upimg_width").val();
        var upimg_h=$("#upimg_height").val();
        ue.setContent('<img style="width:'+upimg_w+'px;height:'+upimg_h+'px;" src="'+imgurl+'"/>', true);
    }
    closeIFrame("dialog-img");
}
function ipimg_cancle(){
    closeIFrame("dialog-img");
}


//HTML特性postMessage API
window.addEventListener('message',function(e){
    var gdata=e.data;//返回的数据
    if(gdata!=""){
        var fileurl=domain+gdata;

        //根据是否有#setimgDiv 判断是选择文件还是选择图片
        var slen=$("#upimg_setimgDiv").length;
        if(slen==1){
            $("#upimg_ok").attr('data-imgurl',fileurl);
            $("#upimg_selimgbox").html('<img  style="width:250px;cursor:pointer;border:1px solid #ccc;" onclick="opIFrame()" src="'+fileurl+'"/>');
        }
        else{
            var url=gdata.split("/");
            var filename=url[url.length-1];
            ue.setContent('附件：<a href="'+fileurl+'">'+filename+'</a> ', true);
        }
    }
    closeIFrame("ifram-file");
},false);

//[附件上传按钮] 在编辑器上新添加一个按钮，并给按钮绑定一个事件
UE.registerUI('filedialog',function(editor,uiName){
    var btn = new UE.ui.Button({
        name:'filelist',
        title:'上传附件',
        //需要添加的额外样式，指定icon图标，这里默认使用一个重复的icon
        cssRules :'background-position: -620px -40px;',
        onclick:function () {
            opIFrame();
        }
    });
    return btn;
},40);

//[图片上传按钮]在编辑器上新添加一个按钮，并给按钮绑定一个事件
UE.registerUI('imgdialog',function(editor,uiName){
    var btn = new UE.ui.Button({
        name:'upimg',
        title:'上传图片',
        //需要添加的额外样式，指定icon图标，这里默认使用一个重复的icon
        cssRules :'background-position: -380px 0px;',
        onclick:function () {
            opIFrameForImg();
        }
    });
    return btn;
},41);
