function formatDate(date, fmt) {
    if (/(y+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, (date.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    let o = {
        'M+': date.getMonth() + 1,
        'd+': date.getDate(),
        'h+': date.getHours(),
        'm+': date.getMinutes(),
        's+': date.getSeconds()
    };
    for (let k in o) {
        if (new RegExp(`(${k})`).test(fmt)) {
            let str = o[k] + '';
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? str : padLeftZero(str));
        }
    }
    return fmt;
};
 
function padLeftZero(str) {
    return ('00' + str).substr(str.length);
}


function formatDateX(dt,level){
    var y = dt.getFullYear();
    //这里month得加1
    var m = dt.getMonth()+1;
    var d = dt.getDate();
    var hh = dt.getHours()
    var mm = dt.getMinutes()
    var ss = dt.getSeconds();
    if(level==0){
      return `${y}`;
    }else if(level==1){
      return `${y}-${m}`;
    }else if(level==2){
      return `${y}-${m}-${d}`;
    }else if(level==3){
      return `${y}-${m}-${d}:${hh}`;
    }else if(level==4){
      return `${y}-${m}-${d}:${hh}:${mm}`;
    }else {
      return `${y}-${m}-${d}:${hh}:${mm}:${ss}`;
    }
  }