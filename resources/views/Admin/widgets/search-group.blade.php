<div class="panel panel-default panel-collapse collapse" id="search-group">
    <div class="panel-heading">查询条件</div>
    <div class="panel-body">
        <form id="formSearch" class="form-horizontal">
            <div class="form-group row" style="margin-top:15px">

                {{ $searchContent }}

                <div class="col-sm-2" style="text-align:left;">
                    <button type="button" style="margin-left:10px" id="search-btn" class="btn btn-primary">查询</button>
                    <button type="reset" id="reset" class="btn btn-default">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>