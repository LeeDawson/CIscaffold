/**
 * Created by garbin on 2017/2/1.
 */

var submitIng = false;

var locale = {
    "format": 'YYYY-MM-DD h:mm A',
    "separator": " 至 ",
    "applyLabel": "确定",
    "cancelLabel": "取消",
    "fromLabel": "起始时间",
    "toLabel": "结束时间'",
    "customRangeLabel": "自定义",
    "weekLabel": "W",
    "daysOfWeek": ["日", "一", "二", "三", "四", "五", "六"],
    "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
    "firstDay": 1,
};

$(document).ready(
    function () {
            $('.clear-filter').click(
                function () {
                    $('.filter-box input').val('');
                    $('.filter-box select').val(-1);
                    $(this).prev().trigger('click');
                }
            );
    }
);

var uniIsnull = function ( par) {
    if(par == '' || typeof(par) == 'undefined' || par == null) {
        return true;
    }
    return false;
}

var jumpTo = function ( jumpUrl) {
    window.location.href = jumpUrl;
    return false;
}

var showError = function ( errMessge , jumpUrl) {
    alert(errMessge);
    if(!uniIsnull(jumpUrl)) {
        window.location.href = jumpUrl;
    }
}

var showSuccess = function ( message , jumpUrl) {
    alert(message);
    if(!uniIsnull(jumpUrl)) {
        window.location.href = jumpUrl;
    }
}

var uniAjax = function ( url , data) {
    var rsJson = '';
    $.ajax(
        {
            url:url,data:data,type:'POST',dataType:'json',async:false,
            success:function ( data) {
                rsJson = data;
            },error: function (e) {
                rsJson = { "status":"error","msg": "系统错误,请检查问题" };
            }
        }
    );
    return rsJson;
}

var getLocalTime = function (nS) {
    var date = new Date(parseInt(nS) * 1000);
    Y = date.getFullYear() + '-';
    M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
    D = date.getDate() + ' ';
    h = date.getHours() + ':';
    m = date.getMinutes() + ':';
    s = date.getSeconds();
    return Y+M+D+h+m+s;
}

var adminBase = function () {

    this.flushVerifyCode = function ( targetObj) {
        var url  = baseUrl + 'index/flush_verify_code';
        var data = 'num=' + Math.random();
        var rsJson = uniAjax(url , data);
        rsJson.status == 'success' && targetObj.attr('src' , rsJson.data.imageUrl);
    }

    this.removeData = function ( tableName , field , value , ifLogic) {

    }

}