<template>
  <div>
    
  </div>
</template>

<script>
    // window.io = io
    // window.Echo = new Echo({
    //   broadcaster: 'socket.io',
    //   host: 'http://www.rpa2.com:6001',
    // })
    Echo.private('App.Models.Admin.Admin.SysAdmin.').notification( (res) => {
       if (res.status === 200) {
         console.log(res.message)
       } else {
         console.log('something wrong!')
       }
    })

// RPA.Echo.init('App.Models.Admin.Admin.SysAdmin.' + socket.userId);
//             //消息通知laravel-echo
// Echo.private(model).notification(function(obj){
//     _this.content(obj);
//     let typeName = "";
//     if(obj.typeName == 1){
//         typeName = "系统公告";
//     }else if(obj.typeName == 2){
//         typeName = "RPA通知";
//     }else{
//         typeName = "管理员通知";
//     }
//     let html = "";
//     html += '<div class="notify-wrap">'
//             + '<div class="notify-title">' + typeName + '<span class="notify-off"><i class="icon iconfont">&#xe6e6;</i></span></div>'
//             + '<div class="notify-title"><a href="JavaScript:void(0);" url="/admin/sys_message_list/view/'+ obj.id +'" onclick="operation($(this));" title="查看站内信息">' + obj.title + '</a><div>'
//             + '<div class="notify-content">' + obj.content + '</div>'
//             + '</div>';
    
//     $("body").append(html);
//     $(".notify-wrap").slideDown(2000);
//     setTimeout(function(){
//         $(".notify-wrap").slideUp(2000);
//     },8000);
// });
</script>

<style lang="sass" scoped>