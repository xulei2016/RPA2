<?php

namespace App\Events\Sync;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SynCustomer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var $data
     */
    protected $data;

    /**
     * @var $type
     */
    protected $type;

    /**
     * Create a new event instance.
     *
     * @param array $data
     * @param int $type
     */
    public function __construct(array $data, int $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getData(){
        $data = '{"status":200,"msg":[{"ID":"9110009123","YYB":"110","YBYYB":"110","KHH":"110009123","ZJZH":"110009123","KHXM":"河南省墨添网络科技有限公司","KHZT":"0","ZJLB":"0","KHJB":null,"KHLX":"1","KHRQ":"20200109","XHRQ":"0","BZ":null,"JYR":null,"KTYW":null,"QQKTRQ":null,"ZCZHZT":"0","KHQC":null,"DHQH":null,"CZ":null,"YZBM":"450000","DH":"13938560737","SJ":"13938560737","GTSJ":"13938560737","DZ":"中国河南省郑州市金水区金水路125号A-1210号","SFZDZ":"中国河南省郑州市金水区金水路125号A-1210号","EMAIL":null,"ZJBH":"MA3XBBM3-7","QQ":null,"MSN":null,"MDRXM":null,"IBYYB":null,"HYQK":null,"GJ":"156","PROVINCE":null,"CITY":null,"SEC":null,"SEX":"3","CSRQ":"0","FWXM":null,"ZJKSRQ":"20160630","ZJJSRQ":"20991231","FXDJ":null,"FXYS":"5","SMZT":null,"QTZY":null,"ZYDM":null,"TBTS":null,"NL":null,"GLZT":null,"JBGXRQ":null,"JBJSJG":null,"JBJSRQ":null,"KHFZ_HS":"1101","YYB_HS":"110","XLDM":null,"KHXZ":null,"ZY":null},{"ID":"9118006090","YYB":"118","YBYYB":"118","KHH":"118006090","ZJZH":"118006090","KHXM":"王鹏","KHZT":"0","ZJLB":"1","KHJB":null,"KHLX":"0","KHRQ":"20200109","XHRQ":"0","BZ":null,"JYR":null,"KTYW":null,"QQKTRQ":null,"ZCZHZT":"0","KHQC":null,"DHQH":null,"CZ":null,"YZBM":"450044","DH":"18810348982","SJ":"18810348982","GTSJ":"18810348982","DZ":"中国河南省郑州市惠济区观湖公寓1#1125室","SFZDZ":"河南省荥阳市豫龙镇郑上路75号院17号楼2单元1108室","EMAIL":null,"ZJBH":"410323198711025073","QQ":null,"MSN":null,"MDRXM":null,"IBYYB":null,"HYQK":null,"GJ":"156","PROVINCE":null,"CITY":null,"SEC":null,"SEX":"1","CSRQ":"19871102","FWXM":null,"ZJKSRQ":"20180525","ZJJSRQ":"20380525","FXDJ":null,"FXYS":"5","SMZT":null,"QTZY":null,"ZYDM":"个体工商户、私营企业主","TBTS":null,"NL":null,"GLZT":null,"JBGXRQ":null,"JBJSJG":null,"JBJSRQ":null,"KHFZ_HS":"1180","YYB_HS":"118","XLDM":"硕士","KHXZ":null,"ZY":null}]}';
        $data = json_decode($data, true);
        return $data;
        return $this->data;
    }

    /**
     * @return int
     */
    public function getType(){
        return $this->type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
