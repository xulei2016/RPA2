<footer class="main-footer">
    <strong>Copyright &copy; 2017-2019 <a href="www.haqh.com">华安futures</a>金融科技部 DESIGN.</strong> All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> {{ (new \App\Http\Controllers\base\BaseController())->get_config(['version_number'])['version_number']??'' }}
    </div>
</footer>