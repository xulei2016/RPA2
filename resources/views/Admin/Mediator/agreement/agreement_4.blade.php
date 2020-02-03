<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>居间报酬及支付方式</title>
</head>
<body>
<h3>居间报酬附件</h3>
<h2 style="text-align: center;">居间报酬及支付方式</h2>
<p>
    1、甲方经审核确认乙方与其开发客户的居间关系后，按本协议约定计算居间报酬，相关增值税及附加、风险准备金、投资者保障基金等由甲方按国家有关规定予以扣除（如遇国家政策调整，则按新规定执行）。
</p>
<p>
    2、居间报酬按月计算，按月支付。甲方于次月收到乙方提供的合法合规发票及纳税凭证后的10个工作日内将相关报酬转入乙方指定的同名银行帐户。自然人居间提供劳务费发票及个人所得税纳税凭证，机构居间提供增值费专用发票（发票项目为咨询费）。
</p>
<p>
    3、乙方的收款账户应为其实名银行账户（银行账户户名必须与其姓名或名称一致）。乙方指定的收款银行账户如下：
</p>
<p>
    <span style="font-weight: bold;">
        户名：{{ $flow->bank_username }}<br>
        开户银行：{{ $flow->bank_name.$flow->bank_branch }}（网点名称具体到支行，建议为工行）<br>
        银行帐号：{{ $flow->bank_number }}<br>
    </span>
    上述帐户除非乙方书面正式通知甲方变更以外，均被视为有效帐户。
</p>
<p>
    4、居间报酬计算方法和返还比例：<br>
    居间报酬＝（期货公司佣金净留存－增值税及附加－风险准备金－投资者保障基金－协议约定的其他费用）×返还比例<br>
    甲乙双方约定选择下列 A 类返还比例：<br>
    √ A 统一比例：{{ $flow->rate }}％<br>
    □ B 分段返还比例：<br>
    当期货公司佣金净留存_/_时，返还比例为_/_；<br>
    当期货公司佣金净留存_/_时，返还比例为_/_；<br>
    当期货公司佣金净留存_/_时，返还比例为_/_；<br>
    当期货公司佣金净留存_/_时，返还比例为_/_；<br>
    当期货公司佣金净留存_/_时，返还比例为_/_。<br>
</p>
<div style="margin-top: 0px;">
    <div style="float:left;width: 50%;height: 10px;">

    </div>
    <div style="float:left;">
        <p>乙方签字：<img width="120" height="120" src="{{ $flow->base64_sign_img }}" alt=""></p>
        <p>(机构加盖公章)：</p>
        <p>签署日期：{{ date(' Y 年 m 月 d 日',strtotime($flow->part_b_date)) }}</p>
    </div>
</div>
</body>
</html>