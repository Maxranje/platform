<?php

class Service_Teacher_Lists {

    private $daoTeacher ;

    private $daoTeacherCourse;

    public function __construct() {
        $this->daoTeacher = new Dao_Teacher_Mysql_Teacher () ;
        $this->daoTeacherCourse = new Dao_Teacher_Mysql_Course();
    }

    public function getTeacherList ($teachername, $courseid, $status, $pn, $rn){
        $arrConds = array();
        if (!empty($teachername)) {
            $arrConds[] = 'teachername like "%' . $teachername . '%"';
        }
        if (!empty($courseid)) {
            $teacherid = $this->daoTeacherCourse->getListByConds(['courseid' => $courseid], $this->daoTeacherCourse->simpleFields);
            $teacherid = array_column($teacherid, 'teacherid');
            $arrConds[] = "teacherid in (" . implode(',', $teacherid) . ')';
        }
        if (!empty($status)) {
            $arrConds[] = 'status=' . $status;
        }

        $arrFields = $this->daoTeacher->simpleFields;

        $arrAppends = array(
            'order by teacherid desc',
            "limit {$pn} , {$rn}",
        );

        $total = $this->daoTeacher->getCntByConds($arrConds);
        $lists = $this->daoTeacher->getListByConds($arrConds, $arrFields, null , $arrAppends);
        if (empty($lists)) {
            return [0, []];
        }

        foreach ($lists as $index => $course) {
            $course['createtime'] = date('Y-m-d H:i:s', $course['createtime']);
            $course['updatetime'] = date('Y-m-d H:i:s', $course['updatetime']);
            $lists[$index] = $course;
        }

        return [$total, $lists];
    }

    public function getTeacherProfile ($teacherid){
        $arrConds = array(
            'teacherid' => $teacherid,
        );

        $arrFields = $this->daoTeacher->arrFieldsMap;

        $teacherinfo = $this->daoTeacher->getRecordByConds($arrConds, $arrFields);

        return empty($teacherinfo) ? false : $teacherinfo;
    }


    public function modifyTeacherProfile ($teacherid , $profile) {
        if (empty($teacherid)) {    
            $ret = $this->daoTeacher->insertRecords($profile);
        } else {
            $ret = $this->daoTeacher->updateByConds(['teacherid' => $teacherid], $profile);
        }
        return $ret == false ? false : true;
    }

    public function changeTeacherStatus ($teacherid, $status) {
        $ret = $this->daoTeacher->updateByConds(['teacherid' => $teacherid], ['status' => $status]);
        if ($ret == false) {
            return false;
        }
        $this->daoTeacherCourse->updateByConds(['teacherid' => $teacherid], ['status' => $status]);
        return true;
    }
}