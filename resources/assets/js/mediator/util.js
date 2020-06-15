var prefix = "/mediator/";

// 日期格式化
function dateFormat(fmt, date){ //日期格式化
    let ret;
    let opt = {
        "Y+": date.getFullYear().toString(),        // 年
        "m+": (date.getMonth() + 1).toString(),     // 月
        "d+": date.getDate().toString(),            // 日
        "H+": date.getHours().toString(),           // 时
        "M+": date.getMinutes().toString(),         // 分
        "S+": date.getSeconds().toString()          // 秒
        // 有其他格式化字符需求可以继续添加，必须转化成字符串
    };
    for (let k in opt) {
        ret = new RegExp("(" + k + ")").exec(fmt);
        if (ret) {
            fmt = fmt.replace(ret[1], (ret[1].length == 1) ? (opt[k]) : (opt[k].padStart(ret[1].length, "0")))
        }
    }
    return fmt;
}

//获取秒数S
function getSec(str) {
   let number=str.substring(1,str.length)*1;
   let format = str.substring(0,1);
   if (format === "s") {
        return number*1000;
   } else if (format === "h") {
       return number*60*60*1000;
   } else if (format === "d") {
       return number*24*60*60*1000;
   }
}

export default {
    getDate:(date) => { // 返回年月日
        return dateFormat("YYYY-mm-dd", date)
    },
    next:(flag = false) => {
        setTimeout(function(){
            let url = prefix + "goNext";
            if(flag) {
                url += '?back=1';
            }
            window.location.href = url;
        }, 500);
    },
    goBack:() => {
        setTimeout(function(){
            window.location.href = prefix + "goBack";
        }, 500);
    },
    panel:() => {
        window.location.href = prefix;
    },
    goLogin: () => {
        window.location.href = prefix + "login"
    },
    setCookie: (name, value, time) => {
        var strsec = getSec(time);
        var exp = new Date();
        exp.setTime(exp.getTime() + strsec*1);
        document.cookie = name + "="+ escape (value) + ";path=/mediator;expires=" + exp.toGMTString();
    },
     getCookie:(name) => {
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr = document.cookie.match(reg)) {
            return unescape(arr[2]);
        } else {
            return null;
        }
    },
    delCookie:(name) => {
        var exp = new Date();
        exp.setTime(exp.getTime() - 1);
        var cval=getCookie(name);
        if(cval!=null) {
            document.cookie= name + "="+cval+";expires="+exp.toGMTString();
        }
    },
    dataURLtoFile:(dataurl, filename) => { // 将base64转换为file文件
        let arr = dataurl.split(',');
        let mime = arr[0].match(/:(.*?);/)[1];
        let bstr = atob(arr[1]);
        let n = bstr.length;
        let u8arr = new Uint8Array(n);
        while (n--) {
            u8arr[n] = bstr.charCodeAt(n)
        }
        return new File([u8arr], filename, {type: mime})
    }
}


