<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="@{{ csrf_token }}">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0,minimum-scale=1.0,user-scalable=0"/>
    <link rel="stylesheet" href="{{asset('css/index/mediator/skin.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/include/bootstrap3/css/bootstrap.min.css')}}">
    <title>通过crm查看休眠客户</title>
    <style>
        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
            text-align: center;
        }
        .row{
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center">如何通过crm查看休眠客户</h2>
    <br style="margin-top:20px;">
    <h3>营业部如何查看休眠客户</h3>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <img width="100%" src="{{URL::asset('/images/index/instruction/crm/showSleepCustomer_yyb.gif')}}" alt="">
        </div>
    </div>
    <br>
    <hr>
    
    <div class="text-center" style="line-height:60px;">华安期货-金融科技部</div>
</div>
</body>

<!-- jQuery -->
<script src="{{URL::asset('/include/jquery/jquery.min.js')}}"></script>
<script src="{{URL::asset('/include/bootstrap3/js/bootstrap.min.js')}}"></script>


</html>
