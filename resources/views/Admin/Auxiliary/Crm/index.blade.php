@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid" id="fxq">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <input class="form-control" id="zjzh" type="text" placeholder="资金账号">

                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-success" id="query">查询</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body" id="customer">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            $('#query').on('click', function(){
                var zjzh = $('#zjzh').val();
                if(!zjzh) return false;
                $.get('/admin/rpa_crm_customer_query?zjzh='+zjzh, function(res){
                    if(res.code == 200) {
                        $('#customer').html(res.data);
                    }
                })
            })
        })
    </script>
@endsection
