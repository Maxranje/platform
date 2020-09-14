<?php
class Controller_Profile extends Zy_Core_Controller{

    public function ajaxGetUserProfile () {
        $userid = empty($this->_request['userid']) ? 0 : intval($this->_request['userid']);
        if (empty($userid)) {
            $this->error(405, '用户id 为空');
        }

        $serivce = new Service_Account_User ();
        $userinfo = $serivce->getUserInfo($userid);        

        if (empty($userinfo)) {
            $this->error(500, '无相关用户信息, 请重新登陆');
        }

        return ['userinfo' => $userinfo];
    }

    public function ajaxChangeUserVip () {
        $userid = empty($this->_request['userid']) ? 0 : intval($this->_request['userid']);
        $isvip  = empty($this->_request['isvip']) ? 0 : intval($this->_request['isvip']);
        $discount  = empty($this->_request['discount']) ? 0 : intval($this->_request['discount']);

        if (empty($this->_userInfo) || !in_array($this->_userid, [10001, 10000]) ) {
            $this->error(405, '你没有权限修改');
        }

        if (empty($userid)) {
            $this->error(405, '用户id 为空');
        }

        if (!in_array($isvip, [0,1])) {
            $this->error(405, 'vip 状态错误');
        }

        if ($isvip == 1 && ($discount <=0 || $discount> 100)) {
            $this->error(405, '如果设置 vip,  折扣必须在 1-100 之间, 例如:88折 = 88');
        }

        $serivce = new Service_Account_User ();
        $ret = $serivce->changeUserVip($userid, $isvip, $discount);        

        if ($ret == false) {
            $this->error(500, '系统错误, 请重试');
        }

        return $this->_data;
    }

    public function ajaxChangeUserType () {
        $userid = empty($this->_request['userid']) ? 0 : intval($this->_request['userid']);
        $type  = empty($this->_request['type']) ? 0 : intval($this->_request['type']);

        if (empty($this->_userInfo) || !in_array($this->_userid, [10001, 10000]) ) {
            $this->error(405, '你没有权限修改');
        }

        if (empty($userid)) {
            $this->error(405, '用户id 为空');
        }

        if (!in_array($type, [3,2])) {
            $this->error(405, '用户类型参数不正确');
        }

        $serivce = new Service_Account_User ();
        $ret = $serivce->changeUserType ($userid, $type);        

        if ($ret == false) {
            $this->error(500, '系统错误, 请重试');
        }

        return $this->_data;
    }

    public function ajaxChangeUserStatus () {
        $userid = empty($this->_request['userid']) ? 0 : intval($this->_request['userid']);
        $status  = empty($this->_request['status']) ? 0 : intval($this->_request['status']);

        if (empty($this->_userInfo) || !in_array($this->_userid, [10001, 10000]) ) {
            $this->error(405, '你没有权限修改');
        }

        if (empty($userid)) {
            $this->error(405, '用户id 为空');
        }

        if (!in_array($status, [1,2])) {
            $this->error(405, '用户状态参数错误');
        }

        $serivce = new Service_Account_User ();
        $ret = $serivce->changeUserStatus ($userid, $status);        

        if ($ret == false) {
            $this->error(500, '系统错误, 请重试');
        }

        return $this->_data;
    }
}

