<?php
class Controller_Profile extends Zy_Core_Controller{

    public function ajaxGetTeacherProfile () {
        $teacherid = empty($this->_request['teacherid']) ? 0 : intval($this->_request['teacherid']);
        if (empty($teacherid)) {
            $this->error(405, '无法检索到相关教师');
        }

        $serivce = new  Service_Teacher_Lists ();
        $details = $serivce->getTeacherProfile($teacherid);
        
        if (empty($details)) {
            $this->error(405, '无法检索到相关课程');
        }

        return $details;
    }

    public function ajaxModifyTeacherProfile () {
        $teacherid = empty($this->_request['teacherid']) ? 0 : intval($this->_request['teacherid']);
        $teachertype = empty($this->_request['teachertype']) ? 1 : intval($this->_request['teachertype']);
        $teachername = empty($this->_request['teachername']) ? '' : trim($this->_request['teachername']);
        $teacheravatar = empty($this->_request['teacheravatar']) ? '' : trim($this->_request['teacheravatar']);
        $teacherpic = empty($this->_request['teacherpic']) ? '' : trim($this->_request['teacherpic']);
        $teacherdesc = empty($this->_request['teacherdesc']) ? '' : trim($this->_request['teacherdesc']);
        $teacherdetails = empty($this->_request['teacherdetails']) ? '' : trim($this->_request['teacherdetails']);

        if (empty($teachername) ){
            $this->error(405, '教师姓名不可以为空');
        }

        if (empty($teacheravatar) ){
            $this->error(405, '教师头像不可以为空');
        }

        if (empty($teacherpic) ){
            $this->error(405, '教师全身照不可以为空');
        }

        if (empty($teacherdesc) ){
            $this->error(405, '教师描述不可以为空');
        }

        if (empty($teacherdetails) ){
            $this->error(405, '教师详情不可以为空');
        }

        $profile = [
            "teachertype" => $teachertype,
            "teachername" => $teachername,
            "teacheravatar" => $teacheravatar,
            "teacherpic" => $teacherpic,
            "teacherdesc" => $teacherdesc,
            "teacherdetails" => $teacherdetails,
            "updatetime" => time(),
        ];
        if (empty($courseid)) {
            $profile['createtime'] = time();
        }

        $serivce = new Service_Teacher_Lists ();
        $ret = $serivce->modifyTeacherProfile($teacherid, $profile);
        if ($ret == false) {
            $this->error(500, '编辑失败, 请重试');
        }

        return $this->_data;
    }

    public function ajaxChangeTeacherStatus () {
        $teacherid = empty($this->_request['teacherid']) ? 0 : intval($this->_request['teacherid']);
        $status = empty($this->_request['status']) ? 2 : trim($this->_request['status']);

        if (empty($teacherid) ) {
            $this->error(405, '参数为空, 请重新尝试');
        }

        if (!in_array($status, [1, 2])) {
            $this->error(405, '状态参数不正确');
        }

        $serivce = new Service_Teacher_Lists ();
        $ret = $serivce->changeTeacherStatus($teacherid, $status);
        if ($ret == false) {
            $this->error(500, '编辑失败, 请重试');
        }

        return $this->_data;
    }
}
