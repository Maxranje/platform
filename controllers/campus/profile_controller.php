<?php
class Controller_Profile extends Zy_Core_Controller{
    
    public function ajaxGetCampusProfile () {
        $campusid = empty($this->_request['campusid']) ? 0 : intval($this->_request['campusid']);
        if (empty($campusid)) {
            $this->error(405, '请求参数错误');
        }

        $serivce = new  Service_Campus_Lists ();
        $details = $serivce->getCampusProfile($campusid);
        
        if (empty($details)) {
            $this->error(405, '无法检索到相关评论');
        }

        return $details;
    }

    public function ajaxModifyCampusProfile () {
        $campusid = empty($this->_request['campusid']) ? 0 : intval($this->_request['campusid']);
        $city = empty($this->_request['city']) ? "" : trim($this->_request['city']);
        $area = empty($this->_request['area']) ? "" : trim($this->_request['area']);
        // $articleid = empty($this->_request['articleid']) ? 0 : intval($this->_request['articleid']);
        $campusname = empty($this->_request['campusname']) ? "" : trim($this->_request['campusname']);

        if (empty($city) || empty($area) || empty($campusname)) {
            $this->error(405, '城市,地区,校区名不可以为空');
        }

        $profile =[
            'city'  => $city,
            'area'  => $area,
            'status' => 2,
            'campusname'=> $campusname,
            'updatetime' => time(),
        ];

        if (empty($campusid)) {
            $profile['articleid'] = 0;
            $profile['createtime'] = time();
        }

        $serivce = new  Service_Campus_Lists ();
        $ret = $serivce->modifyCampusProfile($campusid, $profile);
        
        if ($ret == false) {
            $this->error(405, '编辑保存失败, 请重试');
        }

        return $this->_data;
    }

    public function ajaxChangeCampusStatus () {
        $campusid = empty($this->_request['campusid']) ? 0 : intval($this->_request['campusid']);
        $status = empty($this->_request['status']) ? 2 : intval($this->_request['status']);

        if (empty($campusid) || !in_array($status, [1,2]) ){
            $this->error(405, '请求参数错误');
        }

        $serivce = new Service_Campus_Lists ();
        $serivce->changeCampusStatus($campusid, $status);
        return $this->_data;
    }
}
