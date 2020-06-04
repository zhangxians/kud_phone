/**
 * 表格界面的一些辅助方法
 */
function showGender(gender) {
    switch(gender){
        case 1:
            return '男';
        case 2:
            return '女';
        default:
            return '';
    }
}
/* 清空查询 */
function clearQuery() {
    $('#keyword').val('');
    page_reload();
}
/* 批量删除恢复 */
function batch_update(title,url,_is_restore,table_id) {
    table_id=table_id||'lay-table-1';
    var checkStatus = layui.table.checkStatus(table_id)
        ,data = checkStatus.data;
    var table_checked_id=[];
    for(var i=0;i<data.length;i++){
        if(data[i].id!==null){
            table_checked_id.push(data[i].id);
        }
    }
    if(table_checked_id.length==0){
        layer.msg('请先选择行再操作！',{icon:3,time:3000});
        return;
    }
    var _data={
        ids:JSON.stringify(table_checked_id),
        is_restore:_is_restore,
        _token:$('#_token').val()
    }
    field_update(title,url,_data,function (res) {
        if (res.code==0){
            page_refresh();
        }
    })
}


/**
 * Put请求 更新数据库某个值
 * @param title
 * @param url
 * @param val
 * @param token
 * @param callback
 */
function field_update(title,url,_data,callback){
    layer.confirm('确认要'+title+'？',{icon: 3, title:title}, function(index){
        _data['_method']='PUT';
        req_ajax(url,'POST',_data,callback);
    });
}

/**
 * 给下拉选框设置年份
 */
function dropdownlist_year() {
    var year_option='',this_year=(new Date).getFullYear();
    for(var i=this_year;i>=2016;i--){
        year_option+='<option value="'+i+'">'+i+'</option>';
    }
    $('#year').html(year_option);
    var dselected=$('#year').attr('data-selected');
    if(dselected!=undefined&&dselected!=""){
        $('#year').val(dselected)
    }
    layui.form.render('select');
}

/** 模糊搜索 **/
function fuzzy_search() {
    $('#fuzzy').val('1')
    page_reload('lay-table-1',function () {
        $('#fuzzy').val('')
    });
}

/** index页面相关方法 **/
/**
 * 搜索重新请求
 */
function page_reload(table_id,callback){
    var table=layui.table;
    table_id=table_id||'lay-table-1';
    $where={keyword:$('#keyword').val()}
    $('.js-url-args').each(function(){
        var name=$(this).prop('name');
        $where[name]=$(this).val()
    });
    table.reload(table_id, {
        where:$where,
        done: function(res, curr, count){
             if(callback){
                 callback(res, curr, count)
             }
        }
    });
}

/**
 * 页面刷新 触发的页面上 “到第x页 确定”的按钮事件
 */
function page_refresh() {
    var $page_btn=$('.layui-laypage-skip .layui-laypage-btn');
    if($page_btn.length>0){
        $page_btn.trigger('click');
    }else{
        location.replace(location.href);
    }
}


/**
 * 监听工具条
 */
function toolbar_event(table_filter,callback) {
    var table=layui.table;
    table.on('tool('+table_filter+')', function(obj){
        var data = obj.data
        var title=$(this).html()
        var url=$(this).attr('data-url')
        if(obj.event === 'box'){
            var wh=$(this).attr('data-val').split(',');
            layer_box(title,url,wh[0],wh[1],function () {
                // page_refresh();
            })
        }
        else if(obj.event === 'field_update'){
            var _val=$(this).attr('data-val');
            var _data={
                id:data.id,
                val:_val,
                _token:$('#_token').val()
            }
            field_update(title,url,_data,function () {
                page_refresh();
            })
        }
        else if(obj.event === 'destroy'){
            layer.confirm('确认要'+title+'吗？',{icon: 3, title:title}, function(index){
                var _data={
                    _token:$('#_token').val()
                }
                _data['_method']='DELETE';
                req_ajax(url,'POST',_data,function () {
                   // page_refresh();
                });
            });
        }
        else if(obj.event === 'restore'){
            layer.confirm('确认要'+title+'吗？',{icon: 3, title:title}, function(index){
                var _data={
                    _token:$('#_token').val(),
                    is_restore:1
                }
                _data['_method']='DELETE';
                req_ajax(url,'POST',_data,function () {
                   // page_refresh();
                });
            });
        }
        else if(obj.event === 'post'){
            layer.confirm('确认要'+title+'吗？',{icon: 3, title:title}, function(index){
                var _data={
                    _token:$('#_token').val()
                }
                req_ajax(url,'POST',_data,function () {
                    page_refresh();
                });
            });
        }
        if(callback){
            callback(obj);
        }
    });
}

/**
 * 字段排序
 */
function field_sort_envent(table_filter,table_id) {
    var table=layui.table;
    table_id=table_id||'lay-table-1';
    table.on('sort('+table_filter+')', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        //console.log(obj.field); //当前排序的字段名
        //console.log(obj.type); //当前排序类型：desc（降序）、asc（升序）、null（空对象，默认排序）
        //console.log(this); //当前排序的 th 对象
        table.reload(table_id, {
            initSort: obj //记录初始排序，如果不设的话，将无法标记表头的排序状态。 layui 2.1.1 新增参数
            ,where: { //请求参数
                field: obj.field //排序字段
                ,order: obj.type //排序方式
            }
        });
    });
}
