<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>期货居间人自律承诺书</title>
</head>
<body>
    <h3>自律承诺附件</h3>
    <h2 style="text-align: center;">期货居间人自律承诺书</h2>
    <p>
        本人/单位：{{ $flow->info->name }}<br>
        身份证号码/统一社会信用代码证：{{ $flow->zjbh }}<br>
        是华安期货有限责任公司的居间人，自愿履行以下承诺： <br>
    </p>
    <p>
        一、以真实身份从事居间业务，不属于下列情形：无民事行为能力或者限制民事行为能力的自然人或中国国家机关、事业单位；期货业务相关单位的工作人员及其直系近亲属以及具有中间介绍资格的证券公司期货业务经办人员；证券、期货市场禁止进入者以及其他规定不得从事期货居间业务的单位或个人。
    </p>
    <p>
        二、遵守国家法律法规和期货公司的相关规章制度，遵守《安徽省辖区期货经营机构居间业务自律规定》等自律管理规定，不存在以下情形：<br>
        1、以期货公司、期货公司下属机构和期货公司工作人员的名义开展经营活动；<br>
        2、代理客户办理账户开立、销户、结算签字，或者资金存取、划转、查询等事宜；<br>
        3、提供、传播虚假或者误导客户的信息，或者诱使客户进行不必要的期货买卖；<br>
        4、接受客户委托，代理客户从事期货交易；<br>
        5、代客操作或者代客理财；<br>
        6、向客户承诺期货交易的损益；<br>
        7、利用期货公司居间人身份变相非法集资或融资；<br>
        8、为客户提供集中交易的场所，或以期货公司的名义设立经营服务网点；<br>
        9、从事配资活动，向客户提供交易软件；<br>
        10、有其他损害期货公司和客户利益的行为；<br>
        11、有其他法律、法规或规范性文件禁止的行为。<br>
    </p>
    <p>
        三、忠实履行自己的中间介绍义务，积极促成符合期货交易适当性要求的投资者与期货公司签订期货合同，不阻挠或妨碍投资者与华安期货有限责任公司的签约活动，不损害投资者及华安期货有限责任公司的合法权益。
    </p>
    <p>
        四、向投资者如实告知自己的居间身份及职责，告知投资者华安期货有限责任公司的情况、有关签订期货经纪合同的事项，告知投资者期货交易的功能和风险。
    </p>
    <p>
        五、主动告知客户自己从其交易佣金中获取居间报酬。
    </p>
    <p>
        六、通过适当方法了解投资者的风险偏好，并按照华安期货有限责任公司期货投资者适当性及投资者教育工作要求，做好权限内的投资者服务工作。
    </p>
    <p>
        七、为华安期货有限责任公司保守商业机密，为投资者保守商业秘密和个人隐私，不泄露或传递给他人。
    </p>
    <p>
        八、在签订居间协议后开展居间业务，并据此行使有关权利，承担有关义务，接受有关制度的约束。
    </p>
    <p>
        九、自行承担介绍客户的居间活动费用。除按协议约定享有居间报酬外，不从华安期货有限责任公司获取其他任何经济利益。
    </p>
    <p>
        十、促成客户与华安期货有限责任公司签署期货经纪合同后，按要求进行居间客户关系申报，未按要求申报客户归属关系的，不再追认。
    </p>
    <p>
        十一、当发生居间客户投诉时，积极配合华安期货有限责任公司妥善解决；
    </p>
    <p>
        十二、承诺将华安期货有限责任公司作为在期货行业的唯一居间合作伙伴；
    </p>
    <p>
        十三、主动接受华安期货有限责任公司的相关居间业务培训，每年参加后续培训不少于10小时；
    </p>
    <p>
        十四、接受华安期货有限责任公司以电话、短信或邮件等方式的回访业务；
    </p>
    <p>
        十五、联系方式发生变化时应及时告知华安期货有限责任公司并办理变更业务。
    </p>
    <p>
        十六、若本人/单位违反上述自律承诺，甘愿华安期货有限责任公司单方面解除居间协议、不予本人/单位续签居间协议、扣发相应返佣，并自行承担相应责任。
    </p>

    <div style="margin-top: 100px;">
        <div style="float:left;width: 50%;height: 10px;">

        </div>
        <div style="float:left;">
            <p>承诺人(机构加盖公章)：<img width="120" height="120" src="{{ $flow->base64_sign_img }}" alt=""></p>
            <p>日期：{{ date(' Y 年 m 月 d 日',strtotime($flow->part_b_date)) }}</p>
        </div>
    </div>
</body>
</html>