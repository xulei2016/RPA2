@component('mail::message')
    <?php
    $list = json_decode($mail->content, true);
    ?>
    <h1 style="text-align: center">{{$mail->title}}</h1><div></div>
    <p style="text-align:right;margin-right:20%">-- @php echo date('Y-m-d'); @endphp</p>
    @if($list)
        <table style="width:1000px;border: 1px solid #429fff;font-family: Arial;border-collapse: collapse;">
            <tr>
                <th style="width:12%; border: 1px solid #429fff;background-color: #d2e8ff;font-weight: bold; padding-top: 4px;padding-bottom: 4px;padding-left: 10px;padding-right: 10px;text-align: center;">交易所</th>
                <th style="width:7%; border: 1px solid #429fff;background-color: #d2e8ff;font-weight: bold; padding-top: 4px;padding-bottom: 4px;padding-left: 10px;padding-right: 10px;text-align: center;">品种</th>
                <th style="width:7%; border: 1px solid #429fff;background-color: #d2e8ff;font-weight: bold; padding-top: 4px;padding-bottom: 4px;padding-left: 10px;padding-right: 10px;text-align: center;">上市<br>合约</th>
                <th style="width:7%; border: 1px solid #429fff;background-color: #d2e8ff;font-weight: bold; padding-top: 4px;padding-bottom: 4px;padding-left: 10px;padding-right: 10px;text-align: center;">下市<br>合约</th>
                <th style="width:7%; border: 1px solid #429fff;background-color: #d2e8ff;font-weight: bold; padding-top: 4px;padding-bottom: 4px;padding-left: 10px;padding-right: 10px;text-align: center;">调整<br>合约</th>
                <th style="width:20%; border: 1px solid #429fff;background-color: #d2e8ff;font-weight: bold; padding-top: 4px;padding-bottom: 4px;padding-left: 10px;padding-right: 10px;text-align: center;">类型</th>
                <th style="width:7%; border: 1px solid #429fff;background-color: #d2e8ff;font-weight: bold; padding-top: 4px;padding-bottom: 4px;padding-left: 10px;padding-right: 10px;text-align: center;">交易<br>手续费</th>
                <th style="width:7%; border: 1px solid #429fff;background-color: #d2e8ff;font-weight: bold; padding-top: 4px;padding-bottom: 4px;padding-left: 10px;padding-right: 10px;text-align: center;">日内费用</th>
                <th style="width:7%; border: 1px solid #429fff;background-color: #d2e8ff;font-weight: bold; padding-top: 4px;padding-bottom: 4px;padding-left: 10px;padding-right: 10px;text-align: center;">调整前<br>手续费</th>
                <th style="width:7%; border: 1px solid #429fff;background-color: #d2e8ff;font-weight: bold; padding-top: 4px;padding-bottom: 4px;padding-left: 10px;padding-right: 10px;text-align: center;">调整前<br>日内费用</th>
                <th style="width:7%; border: 1px solid #429fff;background-color: #d2e8ff;font-weight: bold; padding-top: 4px;padding-bottom: 4px;padding-left: 10px;padding-right: 10px;text-align: center;">调整日期</th>
            </tr>
            @foreach($list as $v)
                <tr align="center">
                    <td style="border: 1px solid #429fff;padding: 4px;">{!! $v['jys'] !!}</td>
                    <td style="border: 1px solid #429fff;padding: 4px;">{!! $v['pz'] !!}</td>
                    <td style="border: 1px solid #429fff;padding: 4px;">{{$v['hydm_on']}}</td>
                    <td style="border: 1px solid #429fff;padding: 4px;">{{$v['hydm_off']}}</td>
                    <td style="border: 1px solid #429fff;padding: 4px;">{{$v['hydm_tz']}}</td>
                    <td style="border: 1px solid #429fff;padding: 4px;">{!! $v['typeName'] !!}</td>
                    <td style="border: 1px solid #429fff;padding: 4px;">{!! $v['sxf'] !!}</td>
                    <td style="border: 1px solid #429fff;padding: 4px;">{!! $v['rnfy'] !!}</td>
                    <td style="border: 1px solid #429fff;padding: 4px;">{!! $v['sxf_before'] !!}</td>
                    <td style="border: 1px solid #429fff;padding: 4px;">{!! $v['rnfy_before'] !!}</td>
                    <td style="border: 1px solid #429fff;padding: 4px;">{{$v['real_date']}}</td>
                </tr>
            @endforeach
        </table>
    @else

        {!! $mail->content !!}


    @endif


@endcomponent