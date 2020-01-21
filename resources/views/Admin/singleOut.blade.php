@component('Admin.widgets.viewForm', ['title' => ' '])
    @slot('formContent')


        <div class="flex-column">
            Login out
            <h3 class="text-danger text-center">登出通知</h3>
            <article style="margin: 10% 0;background-color: #b03a3a;padding: 50px 10px;color: #fff;">
                该账号已在其他设备登录，您被迫登出。
                <br>
                <b>若非您本人操作，请立即锁定账号或联系金融科技部处理！！！</b>
            </article>
        </div>
        <hr>
        <div class="flex-row">
            <div class="d-flex justify-content-around">
                <a url="./admin" class="btn btn-default">重新登录</a>
                <a url="./admin/lock" class="btn btn-primary">账号锁定</a>
            </div>
        </div>


    @endslot

    @slot('formScript')
    @endslot
@endcomponent