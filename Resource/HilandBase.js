/**
 * Created by xiedalie on 2016/8/18.
 */
function isUndefined($data){
    if (typeof($data) == "undefined") {
        return true;
    }else{
        return false;
    }
}

function genGuid() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

/**
 * 获取地址栏中查询字符串的信息
 * @param name
 * @returns {*}
 * @sample 使用样例
 * alert(GetQueryString("参数名1"));
 * alert(GetQueryString("参数名2"));
 */
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}
