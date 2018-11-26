@component('admin.widgets.editForm')
    @slot('formContent')
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">名称</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" placeholder="请使用英文名称" required>
                </div>
            </div>
            <div class="form-group">
                <label for="desc" class="col-sm-2 control-label">简述</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="desc" id="desc" placeholder="简述" required>
                </div>
            </div>
            <div class="form-group">
                <label for="guard_name" class="col-sm-2 control-label">权限树</label>
                <div class="col-sm-10">
                    <select name="pid" id="select2-menu" class="form-control pid" id="select2-menu">
                        <option value="">顶级权限</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="guard_name" class="col-sm-2 control-label">所属分组</label>
                <div class="col-sm-10">
                    <select name="guard_name" class="form-control" id="guard_name">
                        <option value="admin" selected>后台管理</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="sort" class="col-sm-2 control-label">排序</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="5" name="sort" id="sort" placeholder="排序">
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-sm-2 control-label">状态</label>
                <div class="col-sm-10">
                    <label><input type="radio" class="minimal icheck" name="status" value="1" checked>启用</label>
                    <label><input type="radio" class="minimal icheck" name="status" value="0">禁用</label>
                </div>
            </div>
            <input type="hidden" name="table" value="1" id="table">
    @endslot
@endcomponent
<script>
    $(function(){
        //初始化
        function init(){
            bindEvent();
        }

        //绑定事件
        function bindEvent(){

            $("#select2-menu").select2({
                "allowClear":true,
                "placeholder":"父级菜单",
            });

            $.post('/admin/sys_permission/getTree', {}, function(json){
                if(200 == json.code){
                    html = initTree(json.data);
                    $('#select2-menu').append(html);
                }else{
                    Swal(json.info, '', 'error');
                }
            });
        }

        function initTree(data){
            var num = data.length;
            let html = '';
            for(let i = 0;i < num; i++){
                let json = data[i];
                html += "<option value ="+json.id+" table="+json.table+">"+ moreString(json['table']) + json.desc +"</option>"
                if(json.hasOwnProperty('child')){
                    html += initTree(json.child);
                }
            }
            return html;
        }

        function moreString(n){
            let html = '&nbsp;&nbsp;';
            let i = 0;
            while(i < n){
                i++;
                html += html;
            }
            return html;
        }

        //添加
        function add(e){
            let table = $('#modal #select2-menu option:selected').attr('table');
            $('#modal #table').val(table);
            RPA.ajaxSubmit(e.parents('#form'), FormOptions);
        }
        
        //提交信息的表单配置
        var FormOptions={
            url:'/admin/sys_permission',
            success:function(json, xml){
                console.log(json);
                if(200 == json.code){
                    RPA.form.response();
                }else{
                    toastr.error(json.info);
                }
            },
            error:RPA.errorReponse
        };

        init();
    });

</script>