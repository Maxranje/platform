<?php
class Controller_Lists extends Zy_Core_Controller{

    public function ajaxGetArticleList () {
        $articletitle   = empty($this->_request['articletitle']) ? '' : trim($this->_request['articletitle']);
        $articletype    = empty($this->_request['articletype']) ? 0 : intval($this->_request['articletype']);
        $recommend      = empty($this->_request['recommend']) ? -1 : intval($this->_request['recommend']);
        $status         = empty($this->_request['status']) ? 0 : intval($this->_request['status']);
        $starttime      = empty($this->_request['starttime']) ? '' : trim($this->_request['starttime']);
        $endtime        = empty($this->_request['endtime']) ? '' : trim($this->_request['endtime']);
        $pn             = empty($this->_request['pn']) ? 0 : $this->_request['pn'];
        $rn             = empty($this->_request['rn']) ? 20 : $this->_request['rn'];
        $pn = $pn * 20;

        if (!empty($starttime)) {
            $starttime = strtotime($starttime);
        }

        if (!empty($endtime)) {
            $endtime = strtotime($endtime);
        }


        $serivce = new Service_Article_Lists ();
        list($total, $lists) = $serivce->getArticleList($articletitle, $articletype, $recommend, $status, $starttime, $endtime, $pn, $rn);

        return ['lists' => $lists, 'total' => $total];
    }
}
