<?php
class Controller_Lists extends Zy_Core_Controller{

    public function ajaxGetUserList () {
        $phone      = empty($this->_request['phone']) ? '' : trim($this->_request['phone']);
        $uname      = empty($this->_request['uname']) ? '' : trim($this->_request['uname']);
        $type       = empty($this->_request['type']) ? 0 : intval($this->_request['type']);
        $sex        = empty($this->_request['sex']) ? '' : trim($this->_request['sex']);
        $starttime  = empty($this->_request['starttime']) ? '' : trim($this->_request['starttime']);
        $endtime    = empty($this->_request['endtime']) ? '' : trim($this->_request['endtime']);
        $pn          = empty($this->_request['pn']) ? 0 : $this->_request['pn'];
        $rn          = empty($this->_request['rn']) ? 20 : $this->_request['rn'];
        $pn = $pn * 20;

        if (!empty($starttime)) {
            $starttime = strtotime($starttime);
        }

        if (!empty($endtime)) {
            $endtime = strtotime($endtime);
        }

        $serivce = new Service_Account_User ();
        list($total, $lists) = $serivce->getUserList($uname, $phone, $type, $sex, $starttime, $endtime, $pn, $rn);

        return ['lists' => $lists, 'total' => $total];
    }

    public function ajaxGetUserCourseList () {
        $userid      = empty($this->_request['userid']) ? 0 : intval($this->_request['userid']);
        $pn          = empty($this->_request['pn']) ? 0 : $this->_request['pn'];
        $rn          = empty($this->_request['rn']) ? 20 : $this->_request['rn'];
        $pn = $pn * 20;

        $serivce = new Service_Account_Course ();
        list($total, $lists) = $serivce->getCourseList($userid, $pn, $rn);

        return ['lists' => $lists, 'total' => $total];
    }
}
