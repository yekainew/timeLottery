<?php $para = $args[0];
if ($para['type'] == 1) {
    $actionNo = date('Ymd-', strtotime($para['actionTime'])) . substr($para['actionNo'] + 1000, 1);
    if ($para['actionNo'] == 120) {
        $actionNo = date('Ymd-', strtotime($para['actionTime']) - 24 * 3600) . substr($para['actionNo'] + 1000, 1);
    }

} else if ($para['type'] == 12) {
//新疆时时彩
    $actionNo = date('Ymd-', strtotime($para['actionTime'])) . substr($para['actionNo'] + 100, 1);
} else if ($para['type'] == 60) {
//天津时时彩
    $actionNo = date('17md', strtotime($para['actionTime'])) . substr($para['actionNo'] + 1000, 1);
} else if ($para['type'] == 61 || $para['type'] == 62 || $para['type'] == 75 || $para['type'] == 76 || $para['type'] == 86) {
//系统时时彩
    $actionNo = date('Ymd-', strtotime($para['actionTime'])) . substr($para['actionNo'] + 1000, 1);

} else if ($para['type'] == 25) {
    $actionNo = date('md', strtotime($para['actionTime'])) . substr($para['actionNo'] + 100, 1);

} else if ($para['type'] == 5) {
//分分彩
    $actionNo = date('Ymd-', strtotime($para['actionTime'])) . substr($para['actionNo'] + 10000, 1);
} else if ($para['type'] == 14 || $para['type'] == 26) {
//2分彩 5分彩
    $actionNo = date('Ymd-', strtotime($para['actionTime'])) . substr($para['actionNo'] + 1000, 1);

} else if ($para['type'] == 6) {
//广东11选5
    $actionNo = date('17md', strtotime($para['actionTime'])) . substr($para['actionNo'] + 100, 1);
} else if ($para['type'] == 7) {
//山东11选5
    $actionNo = date('17md', strtotime($para['actionTime'])) . substr($para['actionNo'] + 100, 1);
} else if ($para['type'] == 15) {
//上海11选5
    $actionNo = date('17md', strtotime($para['actionTime'])) . substr($para['actionNo'] + 100, 1);
} else if ($para['type'] == 16) {
//江西11选5
    $actionNo = date('Ymd-', strtotime($para['actionTime'])) . substr($para['actionNo'] + 100, 1);
} else if ($para['type'] == 67 || $para['type'] == 68) {
//系统11选5
    $actionNo = date('Ymd-', strtotime($para['actionTime'])) . substr($para['actionNo'] + 1000, 1);

} else if ($para['type'] == 9 || $para['type'] == 10) {
// 福彩3D 排列3
    $actionNo = date('Yz', $this->time) - 7;
    $actionNo = substr($actionNo, 0, 4) . substr(substr($actionNo, 4) + 1000, 1);

} else if ($para['type'] == 79) {
//江苏快3
    $actionNo = date('Ymd', strtotime($para['actionTime'])) . substr($para['actionNo'] + 100, 1);
} else if ($para['type'] == 63 || $para['type'] == 64) {
//系统快3
    $actionNo = date('Ymd-', strtotime($para['actionTime'])) . substr($para['actionNo'] + 1000, 1);

} else if ($para['type'] == 20) {
//北京PK10
    $actionNo = 179 * (strtotime(date('Y-m-d', strtotime($para['actionTime']))) - strtotime('2007-11-11')) / 3600 / 24 + $para['actionNo'] - 3773;
} else if ($para['type'] == 66) {
//台湾PK10
    $actionNo = 288 * (strtotime(date('Y-m-d', strtotime($para['actionTime']))) - strtotime('2007-11-11')) / 3600 / 24 + $para['actionNo'] - 4321;
} else if ($para['type'] == 65) {
//澳门PK10
    $actionNo = 288 * (strtotime(date('Y-m-d', strtotime($para['actionTime']))) - strtotime('2007-11-11')) / 3600 / 24 + $para['actionNo'] - 6789;

} else if ($para['type'] == 78) {
//北京快乐8
    $actionNo = 179 * (strtotime(date('Y-m-d', strtotime($para['actionTime']))) - strtotime('2004-09-19')) / 3600 / 24 + $para['actionNo'] - 3837;
} else if ($para['type'] == 73) {
//澳门快乐8
    $actionNo = 288 * (strtotime(date('Y-m-d', strtotime($para['actionTime']))) - strtotime('2004-09-19')) / 3600 / 24 + $para['actionNo'] - 1234;
} else if ($para['type'] == 74) {
//韩国快乐8
    $actionNo = 288 * (strtotime(date('Y-m-d', strtotime($para['actionTime']))) - strtotime('2004-09-19')) / 3600 / 24 + $para['actionNo'] - 4567;

} else if ($para['type'] == 71 || $para['type'] == 72) {
//系统快乐十分
    $actionNo = date('17md', strtotime($para['actionTime'])) . substr($para['actionNo'] + 1000, 1);

} else if ($para['type'] == 77) {
//系统六合彩
    $actionNo = date('Ymd-', strtotime($para['actionTime'])) . substr($para['actionNo'] + 1000, 1);

}

?>
<div>
    <input type="hidden" value="<?= $this->user['username'] ?>"/>
    <form action="/index.php/data/added" target="ajax" method="post" call="dataSubmitCode" onajax="dataBeforeSubmitCode"
          dataType="html">
        <input type="hidden" name="type" value="<?= $para['type'] ?>"/>
        <table class="popupModal">
            <tr>
                <td class="title" width="300">期号：</td>
                <td><input type="text" name="number" value="<?= $actionNo ?>"/></td>
            </tr>
            <tr>
                <td class="title">开奖时间：</td>
                <td><input type="text" name="time" value="<?= $para['actionTime'] ?>"/></td>
            </tr>

            <tr>
                <td class="title"> 中奖类型：</td>
                <td>
                    <select name="win_type" class="id_win_type" onchange="win_change(this);">
                        <option value="0" selected="selected">请选择</option>
                        <option value="1">数字</option>
                        <option value="2">大小单双</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td class="title"> 值：</td>
                <td>
                    <div class="win_type_val">
                        <!-- <div style="cursor: pointer;margin-left:10px;float:left;text-align:center;width: 20px;height: 20px;border:0.5px solid red; border-radius: 10px;line-height: 20px;">1</div>
                        <div style="cursor: pointer;margin-left:10px;float:left;text-align:center;width: 20px;height: 20px;border:0.5px solid red; border-radius: 10px;line-height: 20px;">2</div>
                        <div style="cursor: pointer;margin-left:10px;float:left;text-align:center;width: 20px;height: 20px;border:0.5px solid red; border-radius: 10px;line-height: 20px;">3</div> -->
                    </div>

                </td>
            </tr>

            <tr>
                <td class="title">开奖号码：</td>
                <td><input type="text" name="data" class="input_data_v"/></td>
            </tr>


            <tr>
                <td align="right"><span class="spn4">提示：</span></td>
                <td><span class="spn4">请确认【期号】和【开奖号码】正确<br/>号码格式如: 1,2,3,4,5</span></td>
            </tr>
        </table>


    </form>
</div>


