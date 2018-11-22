<?php
@session_start();

class Safe extends WebLoginBase
{
    public $title = '\x51\x51\x34\x31\x30\x37\x34\x39\x39\x38\x35';
    private $vcodeSessionName = 'blast_vcode_session_name';

    /**
     * 用户信息页面
     */
    public final function info()
    {
        $this->display('safe/info.php');
    }

    public final function question()
    {
        $this->display('safe/question.php');
    }

    public final function us()
    {
        $this->display('safe/us.php');
    }

    public final function more()
    {
        $this->display('safe/more.php');
    }

    //金额获取
    public final function userInfo()
    {
        $this->display('safe/userInfo.php');
    }

    //登入密码
    public final function loginpasswd()
    {
        $this->display('safe/loginpasswd.php');
    }

    //个人中心
    public final function Personal()
    {
        $this->display('safe/Personal.php');
    }

    /**
     * 密码管理
     */
    public final function passwd()
    {
        $sql = "select password, coinPassword from {$this->prename}members where uid=?";
        $pwd = $this->getRow($sql, $this->user['uid']);
        if (!$pwd['coinPassword']) {
            $coinPassword = false;
        } else {
            $coinPassword = true;
        }
        $this->display('safe/passwd.php', 0, $coinPassword);
    }

    /**
     * 设置密码
     */
    public final function setPasswd()
    {
        $opwd = $_POST['oldpassword'];
        if (!$opwd) return ('原密码不能为空');
        //if(strlen($opwd)<6) return ('原密码至少6位');
        if (!$npwd = $_POST['newpassword']) return ('密码不能为空');
        if (strlen($npwd) < 6) return ('密码至少6位');

        $sql = "select password from {$this->prename}members where uid=?";
        $pwd = $this->getValue($sql, $this->user['uid']);
        $opwd = md5($opwd);
        if ($opwd != $pwd) return ('原密码不正确');

        $sql = "update {$this->prename}members set password=? where uid={$this->user['uid']}";
        if ($this->update($sql, md5($npwd))) return 200;
        return '修改密码失败';
    }

    /**
     * 设置资金密码
     */
    public final function setCoinPwd()
    {
        $opwd = $_POST['password'];
        if (!$npwd = $_POST['newpassword']) return ('提款密码不能为空');
        if (strlen($npwd) < 6) return ('提款密码至少6位');

        $sql = "select password, coinPassword,username from {$this->prename}members where uid=?";
        $pwd = $this->getRow($sql, $this->user['uid']);
        if (!$pwd['coinPassword']) {
            $npwd = md5($npwd);
            if ($npwd == $pwd['password']) return ('提款密码不能和登陆密码一样!');
        } else {
            if (md5($opwd) != $pwd['coinPassword']) {
                return '旧提款密码不正确';
                die();
            }
            $npwd = md5($npwd);
            if ($npwd == $pwd['password']) return ('提款密码不能和登陆密码一样!');
        }
        $sql = "update {$this->prename}members set coinPassword=? where uid={$this->user['uid']}";
        if ($this->update($sql, $npwd)) return 200;
        return '修改提款密码失败';
    }

    //设置问题
    public final function updateQuestion()
    {
        $question = $_POST['question'];
        $answer = $_POST['answer'];
        $oldAnswer = $_POST['oldAnswer'];


        if (!$question) return ('问题不能为空');
        if (!$answer) return ('答案不能为空');

        $sql = "select * from {$this->prename}question where uid={$this->user['uid']} and question = '{$question}'";
        $data = $this->getRow($sql);

        if (!$data) {
            $update['question'] = $question;
            $update['answer'] = $answer;
            $update['uid'] = $this->user['uid'];
            if ($this->insertRow($this->prename . 'question', $update)) return 200;
        } else {
            if ($answer != $data['answer']) return ('答案错误!');
            $ans = $oldAnswer;
            $sql = "update {$this->prename}question set answer=? where uid={$this->user['uid']}";
            if ($this->update($sql, $ans)) return 200;
        }
        return '设置失败';
    }

    public final function setCoinPwd2()
    {
        $opwd = $_POST['password'];
        if (!$opwd) throw new Exception('旧提款密码不能为空');
        if (strlen($opwd) < 6) throw new Exception('旧提款密码至少6位');
        if (!$npwd = $_POST['newpassword']) throw new Exception('提款密码不能为空');
        if (strlen($npwd) < 6) throw new Exception('提款密码至少6位');

        $sql = "select password, coinPassword from {$this->prename}members where uid=?";
        $pwd = $this->getRow($sql, $this->user['uid']);
        if (!$pwd['coinPassword']) {
            $npwd = md5($npwd);
            if ($npwd == $pwd['password']) throw new Exception('提款密码不能和登陆密码一样!');
            $tishi = '提款密码设置成功';
        } else {
            if ($opwd && md5($opwd) != $pwd['coinPassword']) throw new Exception('旧提款密码不正确');
            $npwd = md5($npwd);
            if ($npwd == $pwd['password']) throw new Exception('提款密码不能和登陆密码一样!');
            $tishi = '修改提款密码成功';
        }
        $sql = "update {$this->prename}members set coinPassword=? where uid={$this->user['uid']}";
        if ($this->update($sql, $npwd)) return $tishi;
        return '修改提款密码失败';
    }

    /**
     * 设置银行帐户
     */
    public final function setCBAccount()
    {
        if (!$_POST) throw new Exception('参数出错');

        $update['account'] = wjStrFilter($_POST['account']);
        $update['countname'] = wjStrFilter($_POST['countname']);
        $update['username'] = wjStrFilter($_POST['username']);
        $update['bankId'] = intval($_POST['bankId']);
        $coinPassword = $_POST['coinPassword'];

        if (!isset($update['account'])) return ('请填写银行账号!');
        if (!isset($update['countname'])) return ('请填写开户行!');
        if (!isset($update['username'])) return ('请填写账户名!');
        if (!isset($update['bankId'])) return ('请选择银行类型!');

        $x = strlen($update['countname']);
        $a = strlen($update['username']);
        $y = mb_strlen($update['countname'], 'utf8');
        $b = mb_strlen($update['username'], 'utf8');
        if (($x != $y && $x % $y == 0) == FALSE) return ('开户行必须为汉字');
        if (($a != $b && $a % $b == 0) == FALSE) return ('开户人姓名必须为汉字');
        unset($x);
        unset($y);
        unset($a);
        unset($b);

        // 更新用户信息缓存
        $this->freshSession();
        // 修改用户提款密码
        if ($this->user['password'] == md5($coinPassword)) {
            return '提款密码不能和登陆密码一样!';
        }
        $sql = "update {$this->prename}members set coinPassword=? where uid={$this->user['uid']}";
        if (!$this->update($sql, md5($coinPassword))) {
            return '提款密码设置失败';
        }
        $update['uid'] = $this->user['uid'];
        $update['editEnable'] = 0;//设置过银行
        if ($bank = $this->getRow("select editEnable from {$this->prename}member_bank where uid=? LIMIT 1", $this->user['uid'])) {
            $update['xgtime'] = $this->time;
            if ($this->updateRows($this->prename . 'member_bank', $update, 'uid=' . $this->user['uid'])) {
                return '更改银行信息成功';
            } else {
                return ('更改银行信息出错');
            }
        } else {
            $update['bdtime'] = $this->time;
            if ($this->insertRow($this->prename . 'member_bank', $update)) {
                return 200;
            } else {
                return ('绑定银行卡出错');
            }
        }
        //检查银行账号唯一
        if ($account = $this->getValue("select account FROM {$this->prename}member_bank where account=? LIMIT 1", $update['account'])) throw new Exception('该' . $account . '银行账号已经使用');
        //检查账户名唯一
        if ($account = $this->getValue("select username FROM {$this->prename}member_bank where account=? LIMIT 1", $update['username'])) throw new Exception('该' . $username . '账户名已经使用');
        if ($bank['editEnable'] != 1) throw new Exception('银行信息绑定后不能随便更改，如需更改，请联系在线客服');
    }

    //设置登陆问候语
    public final function care()
    {
        if (!$_POST) throw new Exception('提交参数出错');

        //过滤未知字段
        $update['care'] = wjStrFilter($_POST['care']);

        //问候语长度限制
        $len = mb_strlen($update['care'], 'utf8');
        if ($len > 10) throw new Exception('登陆问候语过长，请重新输入');
        if ($len = 0) throw new Exception('登陆问候语不能为空，请重新输入');

        if ($this->updateRows($this->prename . 'members', $update, 'uid=' . $this->user['uid'])) {
            return '更改登陆问候语成功';
        } else {
            throw new Exception('更改登陆问候语出错');
        }
    }

    //设置昵称
    public final function nickname()
    {
        $urlshang = $_SERVER['HTTP_REFERER']; //上一页URL
        $urldan = $_SERVER['SERVER_NAME']; //本站域名
        $urlcheck = substr($urlshang, 7, strlen($urldan));
        if ($urlcheck <> $urldan) throw new Exception('数据包被非法篡改，请重新操作');

        if (!$_POST) throw new Exception('提交参数出错');

        //过滤未知字段
        $update['nickname'] = wjStrFilter($_POST['nickname']);

        $len = mb_strlen($update['nickname'], 'utf8');
        if ($len > 8) throw new Exception('昵称过长，请重新输入');
        if ($len = 0) throw new Exception('昵称不能为空，请重新输入');

        if ($this->updateRows($this->prename . 'members', $update, 'uid=' . $this->user['uid'])) {
            return '更改昵称成功';
        } else {
            throw new Exception('更改昵称出错');
        }
    }
}