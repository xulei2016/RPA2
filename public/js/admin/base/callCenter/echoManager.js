var EchoManager = EchoManager || {};
EchoManager.prototype = {
    customerEvent : 'CallCenterCustomerEvent',
    managerEvent : 'CallCenterManagerEvent',
    customerChannelPrefix : 'customer_',
    managerChannelPrefix : 'call_center_manager_',
    echo:null,
    templateCacheKey:'template_list',
    manager_id:null,
    sendByManagerUrl:'/call_center/sendByManager',
    leaveUrl:'/call_center/leave',
    logoutUrl:'/call_center/logout',
    onlineCustomerUrl:'/call_center/getOnlineCustomerList',
    onlineManagerUrl:'/call_center/getOnlineManagerList',
    templateUrl:'/call_center/template_list_background',
    getRecordById:'/call_center/getRecordById',
    getRecord:'/call_center/getRecordList',
    getCustomerInfo:'/call_center/getCustomerInfo',
    connectNumber:0,
    init:function () {
        this.echo = window.Echo;
        this.initListen();
        this.getOnlineCustomerList();
        this.getOnlineManagerList();
        this.getTemplateList();
    },
    initListen:function(){
        this.leaveChannel('customer_change');
        this.subscribePublic('customer_change', 'CallCenterCustomerChangeEvent', this.onCustomerChange);
        this.leaveChannel(this.managerChannelPrefix+this.manager_id);
        this.subscribePrivate(this.managerChannelPrefix+this.manager_id, this.managerEvent, this.onServiceMessage)

    },
    setConfig:function(){},
    setFunc:function(funcs){
        if(funcs.hasOwnProperty('onCustomerChange')) {
            this.onCustomerChange = funcs.onCustomerChange;
        }
        if(funcs.hasOwnProperty('onCustomerMessage')) {
            this.onCustomerMessage = funcs.onCustomerMessage;
        }

    },
    subscribePublic:function(channel, event, func){
        this.leaveChannel(channel);
        console.log("订阅频道...channel:"+channel+" event:"+event);
        this.echo.channel(channel).listen(event, (e) => {
            func(e);
        });
    },
    subscribePrivate:function(channel, event, func){
        this.leaveChannel(channel);
        console.log("订阅频道...channel:"+channel+" event:"+event);
        this.echo.private(channel).listen(event, (e) => {
            func(e)
        });
    },
    leaveChannel:function (channel){
        console.log('离开频道'+channel);
        this.echo.leave(channel);
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
    getOnlineCustomerList:function(){
        this.axios(this.onlineCustomerUrl, 'get', {}).then(r => {
            for(let u of r) {
                this.addItem(u);
                this.addContent(u);
                if(u.manager_id && u.manager_id == this.manager_id) {
                    this.connectNumber++;
                    this.subscribePrivate(this.customerChannelPrefix+u.customer_id, this.customerEvent, this.onCustomerMessage);
                }
            }
        }).catch(error => {
            console.log(error);
        });
    },
    getOnlineManagerList:function(){
        this.axios(this.onlineManagerUrl, 'get', {}).then(r=>{
            for(let u of r) {
                this.addManager(u)
            }
        }).catch(error => {
            console.log(error);
        })
    },
    getTemplateList:function(){
        this.axios(this.templateUrl, 'get', {manager_id:this.manager_id}).then(r=>{
            var t = {};
            for(let item of r) {
                t['template_'+item.id] = item.answer;
                this.addTemplateItem(item);
            }
            localStorage.setItem(this.templateCacheKey, JSON.stringify(t));
        }).catch(error => {
            console.log(error);
        })
    },
    addContent:function(u){
        var content_id = 'content_customer_'+u.customer_id;
        var content = '<div class="chat-content" id="'+content_id+'"></div>';
        $('.chat-content-out').append(content);
    },
    addItem:function(u){
        var ul = $('.online-users-info ul');
        var className = u.status == 1?'free':'busy';
        var manager_id = u.manager_id?u.manager_id:'';
        var li = '<li ' +
            'customer_id="'+u.customer_id+'"' +
            'id="customer_'+u.customer_id+'"' +
            'record_id="'+u.record_id+'"' +
            'customer_avatar="'+u.customer_avatar+'"' +
            'customer_name="'+u.customer_name+'"' +
            'manager_id="'+manager_id+'"' +
            '>'+u.customer_name+'<span class="'+className+'"></span></li>';
        ul.append(li);
    },
    removeItem:function(data){
        let id = "customer_"+data.customer_id;
        $("#"+id).remove();
    },
    changeItem:function(data){
        let id = "customer_"+data.customer_id;
        var className = data.status == 1?'free':'busy';
        $("#"+id+" span").attr('class', className).parent().attr('manager_id', data.manager_id);
    },
    transferService:function(customer_id, manager_id){
        let channel = "customer_"+customer_id;
        console.log("客服转移 channel:"+channel);
        this.axios("/call_center/transfer", 'post', {customer_id:customer_id, manager_id:manager_id}).then( r => {
            EchoManager.prototype.leaveChannel(channel);
            $('#customer_'+customer_id).attr('manager_id', manager_id);
            $('.swal2-confirm').click();
            $('#tab_customer_'+customer_id+' .tabDel').click();
        }).catch(error => {
            console.log(error)
        });
    },
    chatWithCustomer:function(customer_id, record_id){
        this.axios("/call_center/connect", 'post',{manager_id:this.manager_id, customer_id: customer_id, record_id:record_id}).then(r => {
            this.subscribePrivate(this.customerChannelPrefix + customer_id, this.customerEvent, this.onCustomerMessage);
        }).catch(error => {
            console.log(error)
        });
    },
    onCustomerChange:function (data){
        if(data.category === "event") {
            console.log(data);
            switch (data.type) {
                case 'customer_add':
                    EchoManager.prototype.addItem(data.data);break;
                case 'customer_remove':
                    EchoManager.prototype.removeItem(data.data);
                    $('#tab_customer_'+data.data.customer_id+' .tabDel').click();
                    break;
                case 'customer_change':
                    EchoManager.prototype.changeItem(data.data);break;
                case 'manager_remove' :
                    EchoManager.prototype.removeManager(data.data);break;
                case 'manager_add' :
                    if(data.data.id == this.manager_id) break;
                    EchoManager.prototype.addManager(data.data);break
            }
        }
    },
    onServiceMessage:function(data) {
        console.log("收到客服信息");
        console.log(data);
        if(data.category === "event") {
            switch (data.type) {
                case 'manager_change':
                    EchoManager.prototype.subscribePrivate(EchoManager.prototype.customerChannelPrefix + data.content, EchoManager.prototype.customerEvent, EchoManager.prototype.onCustomerMessage);
                    $('#customer_'+data.content).attr('manager_id', data.to);
                    break;
            }
        }
    },
    send:function(customer_id, content, type = 'message'){
        this.axios(this.sendByManagerUrl, 'post', {customer_id:customer_id,manager_id:this.manager_id,type:type,content:content,sender:'manager'})
    },
    managerLeave:function(){
        this.axios(this.leaveUrl, 'post', {manager_id:this.manager_id}).then(r => {
            console.log(r);
        }).catch(error => {
            console.log(error)
        })
    },
    addManager:function(u){
        if(u.id == this.manager_id) return;
        var ul = $(".online-operators-info ul");
        var li = '<li class="text-center" manager_id="'+u.id+'" id="manager_item_'+u.id+'">'+u.realName+'</li>';
        ul.append(li);
    },
    removeManager:function(u) {
        $('#manager_item_'+u.id).remove();
    },
    addTemplateItem:function(item){
        var ul = $('.template ul');
        var li = '<li template_id="'+item.id+'" id="template_'+item.id+'">'+item.content+'</li>';
        ul.append(li)
    }
};