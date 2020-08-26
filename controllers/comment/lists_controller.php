<?php
class Controller_Lists extends Zy_Core_Controller{

    public function ajaxGetCommentList () {
        $type       = empty($this->_request['type']) ? '' : trim($this->_request['type']);
        $starttime  = empty($this->_request['starttime']) ? '' : trim($this->_request['starttime']);
        $endtime    = empty($this->_request['endtime']) ? '' : trim($this->_request['endtime']);
        
        if (!empty($starttime)) {
            $starttime = strtotime($starttime);
        }

        if (!empty($endtime)) {
            $endtime = strtotime($endtime);
        }

        $pn = empty($this->_request['pn']) ? 0 : intval($this->_request['pn']);
        $rn = empty($this->_request['rn']) ? 20 : intval($this->_request['rn']);
        $pn = $pn * 20;

        $serivce = new Service_Comment_Lists ();
        list($total, $lists) = $serivce->getCommentList($type, $starttime, $endtime, $pn, $rn);

        return ['lists' => $lists, 'total' => $total];
    }


}
