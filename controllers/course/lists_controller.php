<?php
class Controller_Lists extends Zy_Core_Controller{

    public function ajaxGetCourseList () {
        $courseno = empty($this->_request['courseno']) ? '' : trim($this->_request['courseno']);
        $recommend = empty($this->_request['recommend']) ? -1 : intval($this->_request['recommend']);
        $coursename = empty($this->_request['coursename']) ? '' : trim($this->_request['coursename']);
        $status = empty($this->_request['status']) ? 0 : intval($this->_request['status']);
        $coursetype = empty($this->_request['coursetype']) ? '' : trim($this->_request['coursetype']);
        $starttime = empty($this->_request['starttime']) ? '' : trim($this->_request['starttime']);
        $endtime = empty($this->_request['endtime']) ? '' : trim($this->_request['endtime']);

        if (!empty($starttime)) {
            $starttime = strtotime($starttime);
        }

        if (!empty($endtime)) {
            $endtime = strtotime($endtime);
        }

        $pn = empty($this->_request['pn']) ? 0 : $this->_request['pn'];
        $rn = empty($this->_request['rn']) ? 20 : $this->_request['rn'];
        $pn = $pn * 20;

        $serivce = new Service_Course_Lists ();
        list($total, $lists) = $serivce->getCourseList($courseno, $recommend, $coursename, $coursetype, $status, $starttime, $endtime, $pn, $rn);
        $coursetype = Service_Course_Lists::COURSE_TYPE_LISTS;

        return ['coursetype' => $coursetype, 'lists' => $lists, 'total' => $total];
    }


}
