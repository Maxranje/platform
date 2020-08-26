<?php
class Controller_Profile extends Zy_Core_Controller{

    public function ajaxGetCourseProfile () {
        $courseid = empty($this->_request['courseid']) ? 0 : intval($this->_request['courseid']);
        if (empty($courseid)) {
            $this->error(405, '无法检索到相关课程');
        }

        $serivce = new  Service_Course_Lists ();
        $details = $serivce->getCourseProfile($courseid);
        
        if (empty($details)) {
            $this->error(405, '无法检索到相关课程');
        }

        return $details;
    }

    public function ajaxModifyCourseProfile () {
        $courseno = empty($this->_request['courseno']) ? '' : trim($this->_request['courseno']);
        $courseid = empty($this->_request['courseid']) ? 0 : intval($this->_request['courseid']);
        $coursename = empty($this->_request['coursename']) ? '' : trim($this->_request['coursename']);
        $coursetype = empty($this->_request['coursetype']) ? '' : trim($this->_request['coursetype']);
        $courseimg = empty($this->_request['courseimg']) ? '' : trim($this->_request['courseimg']);
        $location = empty($this->_request['location']) ? '' : trim($this->_request['location']);
        $maxstunum = empty($this->_request['maxstunum']) ? '' : trim($this->_request['maxstunum']);
        $coursetime = empty($this->_request['coursetime']) ? '' : trim($this->_request['coursetime']);
        $coursemodel = empty($this->_request['coursemodel']) ? '' : trim($this->_request['coursemodel']);
        $coursedesc = empty($this->_request['coursedesc']) ? '' : trim($this->_request['coursedesc']);
        $coursedetails = empty($this->_request['coursedetails']) ? '' : trim($this->_request['coursedetails']);
        $price = empty($this->_request['price']) ? 0 : intval($this->_request['price']);
        $teacherids = empty($this->_request['teacherids']) ? '' : trim($this->_request['teacherids']);
        $isvip = empty($this->_request['isvip']) ? 1 : intval($this->_request['isvip']);
        $recommend = empty($this->_request['recommend']) ? 0 : intval($this->_request['recommend']);

        if (empty($courseo) || empty($coursename) || empty($courseimg) || empty($coursetype) || empty($location) || empty($maxstunum) 
            || empty($coursemodel) || empty($coursedesc) || empty($coursedetails)|| empty($price) || empty($coursetime)) {
            $this->error(405, '部分参数为空, 请重新尝试');
        }

        if (!in_array($recommend, [0,1])) {
            $this->error(405, '推荐状态参数不正确');
        }

        if (!in_array($isvip, [0, 1])) {
            $this->error(405, '折扣状态参数不正确');
        }

        $profile = [
            "coursetype" => $coursetype,
            "coursename" => $coursename,
            "courseno" => $courseno,
            "courseimg" => $courseimg,
            "location" => $location,
            "coursetime" => $coursetime,
            "maxstunum" => $maxstunum,
            "coursemodel" => $coursemodel,
            "coursedesc" => $coursedesc,
            "coursedetails" => $coursedetails,
            "price" => $price,
            "status" => 2,
            "teacherids" => empty($teacherids) ? [] : explode(',', $teacherids),
            "recommend" => $recommend,
            "isvip" => $isvip,
            "updatetime" => time(),
        ];
        if (empty($courseid)) {
            $profile['createtime'] = time();
        }

        $serivce = new Service_Course_Lists ();
        $ret = $serivce->modifyCourseProfile($courseid, $profile);
        if ($ret == false) {
            $this->error(500, '编辑失败, 请重试');
        }

        return $this->_data;
    }

    public function ajaxChangeCourseStatus () {
        $courseid = empty($this->_request['courseid']) ? 0 : intval($this->_request['courseid']);
        $status = empty($this->_request['status']) ? 2 : trim($this->_request['status']);

        if (empty($courseid) ) {
            $this->error(405, '参数为空, 请重新尝试');
        }

        if (!in_array($status, [1, 2])) {
            $this->error(405, '状态参数不正确');
        }

        $serivce = new Service_Course_Lists ();
        $ret = $serivce->changeCourseStatus($courseid, $status);
        if ($ret == false) {
            $this->error(500, '编辑失败, 请重试');
        }

        return $this->_data;
    }
}
