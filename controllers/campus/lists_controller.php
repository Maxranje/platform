<?php
class Controller_Lists extends Zy_Core_Controller{

    public function ajaxGetCampusList () {
        $campusname = empty($this->_request['campusname']) ? '' : trim($this->_request['campusname']);
        $status = empty($this->_request['status']) ? 0 : intval($this->_request['status']);
        $pn = empty($this->_request['pn']) ? 0 : intval($this->_request['pn']);
        $rn = empty($this->_request['rn']) ? 20 : intval($this->_request['rn']);
        $pn = $pn * 20;

        $serivce = new Service_Campus_Lists ();
        list($total, $lists) = $serivce->getCampusList($campusname,  $status, $pn, $rn);

        return ['lists' => $lists, 'total' => $total];
    }


}
