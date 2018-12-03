<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">请选择Excel文件</h4>
</div>
<div class="modal-body">
    <div style="max-height: 300px; overflow: scroll;">
        <ul class="list-group file-group">
        </ul>
    </div>
    {{-- <a href="~/Data/ExcelTemplate/Order.xlsx" class="form-control" style="border:none;">下载导入模板</a> --}}
    <div>
        <a href="javascript:void(0)" class="btn btn-block btn-default" id="txt_file"><i class="glyphicon glyphicon-upload"></i>选择文件上传</a>
        <input type="file" name="txt_file" id="" multiple class="file-loading hidden" />
    </div>
    <div class="report">
        <span class="title"></span>
        <ul class="list-group error-group">
        </ul>
    </div>
</div>
<div class="modal-footer">
    <a class="btn btn-primary submit"><i class="fa fa-save"></i> 开始上传</a>
    <a class="btn btn-danger"><span aria-hidden="true"><i class="fa fa-save"></i> 取消上传</span></a>
</div>
<script src="{{URL::asset('/include/xlsx/xlsx.js')}}"></script>
<script>
    $(function(){
        var total_excel = [];
        var error = {};
        var worksheetIDValue = [];
        var worksheetIDPackage = [];
        var analysisNum = 0;
        var excelList = [];
        var files = [];
        var excel = [   //excel参数表格定位
            ['sqrkck', 'D2'], 
            ['ckdm', 'Q2'],
            ['hyh', 'B3'], 
            ['hymc', 'K3'], 
            ['lxr', 'B4'], 
            ['lxdh', 'K4'], 
            ['khbm', 'B5'], 
            ['khmc', 'K5'], 
            ['khlxr', 'B6'], 
            ['khlxdh', 'K6'], 
            ['scnd', 'B7'], 
            ['spcdsf', 'K7'], 
            ['spcdzz', 'M7'], 
            ['ybsl', 'B8'], 
            ['bzgg', 'M8'], 
            ['jgsjksnf', 'C9'], 
            ['jgsjksyf', 'E9'], 
            ['jgsjjsnf', 'G9'], 
            ['jgsjjsyf', 'I9'], 
            ['jgdwdm', 'M9'], 
            ['jgdw', 'B10'], 
            ['sfwbscd', 'R10'], 
            ['year', 'M22'], 
            ['month', 'O22'], 
            ['day', 'Q22']
        ];

        var package = [     //excel 包数解析
            ['B11','B12','B13'],
            ['F11','F12','F13'],
            ['I11','I12','I13'],
            ['L11','L12','L13'],
            ['O11','O12','O13'],
            ['B15','B16','B17'],
            ['F15','F16','F17'],
            ['I15','I16','I17'],
            ['L15','L16','L17'],
            ['O15','O16','O17']
        ];

        //初始化
        function init(){
            bindEvent();
        }

        //事件绑定
        function bindEvent(){
            $('#modal #txt_file').on('click',function(){
                $(this).next().click();
            });

            $('#modal input[name="txt_file"]').on('change', function (e) {
                files = e.target.files;

                //初始化
                getStart(e);

                //渲染文件
                renderExcelList(files);

                //文件解析
                analysis(files);

                //就绪
                getReady();
            });

            $('#modal .submit').on('click', function(){
                Swal({
                    title: "确认上传?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认",
                    showLoaderOnConfirm: true,
                    cancelButtonText: "取消",
                    preConfirm: function() {
                        return new Promise(function(resolve, reject) {
                            mapExcel();
                        });
                    }
                }).then(function(json) {
                    var json = json.value;
                    Swal(json.info, '', 'success');
                },function(dismiss){
                    Swal(dismiss, '', 'error');
                });
            });
        }

        function mapExcel(){
            for(let e of total_excel){
                // console.log(e);
            }
        }

        //upload file
        function uploadFile(file){
            $.ajax({
                method: 'post',
                url: '/admin/rpa_cotton/importExcel',
                data: {
                    _token:LA.token,
                    file:file
                },
                success: function (json) {
                    if(200 == json.code){
                        resolve(json);
                    }else{
                        reject(json.info);
                    }
                }
            });
            return 
        }

        //解析
        function analysis(files){
            for(let excel of files){
                analysisNum += 1;
                let reader = new FileReader();
                reader.readAsBinaryString(excel);
                reader.onload = function (ev) {
                    if (reader.result) reader.content = reader.result;
                    
                    let data = reader.content;
                    let workbook = XLSX.read(data, { type: 'binary' });
                    let worksheet = workbook.Sheets[workbook.SheetNames[0]];
                    
                    //先要check一下这个表格是不是我们的特定格式表格
                    if ((worksheet["A1"].w == "郑商所棉花交割入库预报表") &&(worksheet["M10"].w == "是否为保税仓单：") &&(worksheet["O2"].w == "代码：")) {
                        let obj = [getValue(worksheet), getPackage(worksheet)];
                        total_excel.push(obj);
                        console.log(total_excel);

                        // $info = '成功解析Excel <b>'+total_excel.length+'</b> 份。'
                        // report($info, true);
                    }
                }
            }
        }

        //获取excel列表   fa-file-excel-o
        function renderExcelList(files){
            var html = '';
            var index = 0;
            for(let excel of files){
                index += 1;
                var size = renderSize(excel.size);
                var name = (excel.name.length > 40) ? excel.name.substr(0, 37)+'...' : excel.name ;
                html += '<li class="list-group-item">'
                    +'<span>'+index+'、</span><span><i class="fa fa-file-excel-o"></i></span>'
                    +'<span>'+name+'</span><span class="pull-right">'+size+'</span></li>';
            }
            $('#modal .modal-body .file-group').html(html);
        }

        //excel参数
        function getValue(worksheet){
            // var a = '';
            // var n = [];
            for(let v of excel){
                try {
                    // a = worksheet[v[1]].w ? worksheet[v[1]].w : errorReport(excel, v[0]) ;
                    // n = [v[0], worksheet[v[1]].w ? worksheet[v[1]].w : errorReport(excel, v[0])];
                    worksheetIDValue.push([v[0], worksheet[v[1]].w ? worksheet[v[1]].w : errorReport(excel, v[0])]);
                } catch (e) {
                    report('表格'+analysisNum+',位置'+v[0]+'的参数出现错误，请检查后尝试!!!', false);
                    continue;
                }
            }
            return worksheetIDValue;
        }

        //包数
        function getPackage(worksheet){
            for(let v of package){
                try {
                    var a = [worksheet[v[0]].w, worksheet[v[1]].w, worksheet[v[2]].w];
                    worksheetIDPackage.push(a);
                } catch (e) {
                    // report('表格'+analysisNum+',位置'+v[0]+'的参数出现错误，请检查确认!!!', false);
                    continue;
                }
            }
            return worksheetIDPackage;
        }

        //初始化
        function getStart(e){
            //禁止解析完成前提交
            $('#modal .modal-footer .submit').addClass('disabled');

            //隐藏input
            $('#modal #txt_file').addClass('hidden');
        }
        
        //ready
        function getReady(e){
            //禁止解析完成前提交
            $('#modal .modal-footer .submit').removeClass('disabled');

            $info = '程序就绪，等待上传！';
            report($info, true);
        }

        //反馈
        function report(mes, status){
            type = status ? 'text-success' : 'text-danger' ;
            status ? error.num + 1 : '' ;
            let info = '';
                info += '<span class="'+type+'"><i class="fa fa-exclamation"></i>'+mes+'</span><br/>';
            
            $('#modal .modal-body .error-group').append(info);
            return '';
        }

        // 格式化文件大小
        // filesize文件的大小,传入的是一个bytes为单位的参数
        function renderSize(value){
            if(null==value||value==''){
                return "0 Bytes";
            }
            var unitArr = new Array("Bytes","KB","MB","GB","TB","PB","EB","ZB","YB");
            var index=0;
            var srcsize = parseFloat(value);
            index=Math.floor(Math.log(srcsize)/Math.log(1024));
            var size =srcsize/Math.pow(1024,index);
            size=size.toFixed(2);//保留的小数位数
            return size+unitArr[index];
        }

        init();
    });
</script>
