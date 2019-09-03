// 客户版
var EchoClient = EchoClient || {};
EchoClient.prototype = {
    manager_id: null,
    customer_id: null,
    record_id :null,
    avatar:null,
    echo:null,
    private_channel:null,
    sound_flag: 1,
    config: {
        cache_key:'customer_info',
        cache_config_key:'call_center_config',
        cache_manager_key:'manager_info',
        message_url: '/call_center/send',
        logout_url : '/call_center/logout',
        login_url : '/call_center/login',
    },
    init: function(data){
        this.echo = window.Echo;
        this.customer_id = data.customer_id;
        this.record_id = data.record_id;
        this.private_channel = data.channel;
        this.event = data.event;
        this.initSubscribe();
    },
    setFunc: function(funcs){
        if(funcs.hasOwnProperty('publicEventFunc')) {
            this.publicEventFunc = funcs.publicEventFunc;
        }
        if(funcs.hasOwnProperty('privateEventFunc')) {
            this.privateEventFunc = funcs.privateEventFunc;
        }
    },
    setCustomerId: function (customer_id){
        this.customer_id = customer_id;
    },
    setManagerId: function(manager_id) {
        this.manager_id = manager_id;
    },
    setPublicEventFunc:function(func) {
        this.publicEventFunc = func;
    },
    setPrivateEventFunc:function(func) {
        this.privateEventFunc = func;
    },
    axios: function(url, method, data){
        return new Promise(function (resolve, reject){
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: url,
                data : data,
                method: method,
                dataType:'json',
                success:function (r){
                    if(r.code === 200) {
                        resolve(r.data);
                    } else {
                        reject(r.info);
                    }
                },
                error:function (r) {
                    reject(r)
                }
            });
        });
    },
    subscribePublic:function(channel, event, func){
        console.log("订阅频道...channel:"+channel+" event:"+event);
        this.echo.channel(channel).listen(event, (e) => {
            func(e);
        });
    },
    subscribePrivate:function(channel, event, func){
        console.log("订阅频道...channel:"+channel+" event:"+event);
        this.echo.private(channel).listen(event, (e) => {
            func(e)
        });
    },
    leaveChannel:function (channel){
        this.echo.leave(channel);
    },
    send:function(content, type = 'message'){
        var record_id;
        if(this.record_id) {
            record_id = this.record_id;
        } else {
            var customer = localStorage.getItem(this.config.cache_key);
            customer = JSON.parse(customer);
            record_id = customer.record_id
            
        }
        var data = {
            customer_id:this.customer_id,
            manager_id:this.manager_id ? this.manager_id : 0,
            record_id:record_id,
            content:content,
            sender:'customer',
            type:type
        };
        this.axios(this.config.message_url, 'post', data).then((r) => {
        }).catch(error => {});
    },
    logout: function(flag = false){
        var info = localStorage.getItem(this.config.cache_key);
        info = JSON.parse(info);
        this.axios(this.config.logout_url, 'post',{customer_id:info.customer_id}).then((r) => {
            this.leaveChannel(this.private_channel);
            this.customer_id = null;
            this.manager_id = null;
            localStorage.removeItem(this.config.cache_key);
            localStorage.removeItem(this.config.cache_config_key);
            localStorage.removeItem(this.config.cache_manager_key);
            if(!flag) {
                window.location.href = this.config.login_url;
            }
        })
    },
    login:function(data){
        var _this = this;
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: this.config.login_url,
            data:data,
            method:'post',
            dataType: 'json',
            success:function(res){
                console.log(res);
                if(res.code !== 200) {
                    alert('登录失败');
                    return;
                }
                alert('登录成功');
                var r = res.data;
                var info = r.info;
                this.record_id = info.record_id;
                info.avatar = data.avatar;
                localStorage.setItem(_this.config.cache_key, JSON.stringify(info));
                localStorage.setItem(_this.config.cache_config_key, JSON.stringify(r.config));
                window.location.href = r.info.href;
            }
        })
    },
    initSubscribe:function(){
        this.subscribePrivate(this.private_channel, this.event, this.privateEventFunc);
    },
    soundPlay:function(){
        if(this.sound_flag) {
            document.getElementById("video").play();
            setTimeout(function(){
                document.getElementById("video").pause();
            },2000)
        }
    },
    setSound:function(r){
        this.sound_flag = r;
    },
    saveManagerInfo:function(data){
        console.log(data);
        this.manager_id = data.id;
        localStorage.setItem(this.config.cache_manager_key, JSON.stringify(data));
    }
};
