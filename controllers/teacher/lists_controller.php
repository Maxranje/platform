<?php
class Controller_Lists extends Zy_Core_Controller{

    public function ajaxGetTeacherList () {
        $teachername = empty($this->_request['teachername']) ? '' : trim($this->_request['teachername']);
        $courseid = empty($this->_request['courseid']) ? 0 : intval($this->_request['courseid']);
        $status = empty($this->_request['status']) ? 0 : intval($this->_request['status']);

        $pn = empty($this->_request['pn']) ? 0 : $this->_request['pn'];
        $rn = empty($this->_request['rn']) ? 20 : $this->_request['rn'];
        $pn = $pn * 20;

        $serivce = new Service_Teacher_Lists ();
        list($total, $lists) = $serivce->getTeacherList($teachername, $courseid, $status, $pn, $rn);

        return ['lists' => $lists, 'total' => $total];
    }


}
