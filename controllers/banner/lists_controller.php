<?php
class Controller_Lists extends Zy_Core_Controller{

    public function ajaxGetBannerList () {
        $bannertitle = empty($this->_request['bannertitle']) ? '' : trim($this->_request['bannertitle']);
        $status      = empty($this->_request['status']) ? 0 : intval($this->_request['status']);
        $starttime   = empty($this->_request['starttime']) ? '' : trim($this->_request['starttime']);
        $endtime     = empty($this->_request['endtime']) ? '' : trim($this->_request['endtime']);
        $pn          = empty($this->_request['pn']) ? 0 : $this->_request['pn'];
        $rn          = empty($this->_request['rn']) ? 20 : $this->_request['rn'];
        $pn = $pn * 20;

        if (!empty($starttime)) {
            $starttime = strtotime($starttime);
        }

        if (!empty($endtime)) {
            $endtime = strtotime($endtime);
        }

        $serivce = new Service_Banner_Lists ();
        list($total, $lists) = $serivce->getBannerList($bannertitle,$status, $starttime, $endtime, $pn, $rn);

        return ['lists' => $lists, 'total' => $total];
    }


}
