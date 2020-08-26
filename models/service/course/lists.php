<?php

class Service_Course_Lists {

    private $daoCourse ;

    private $daoTeacherCourse ;

    private $daoTeacher;

    const COURSE_TYPE_LISTS = [
        ['id'    => 'toefl',    'name'  => 'TOEFL',],
        ['id'    => 'ielts',    'name'  => 'IELTS',],
        ['id'    => 'sat',      'name'  => 'SAT',],
        ['id'    => 'sat2',     'name'  => 'SATⅡ/AP',],
        ['id'    => 'gre',      'name'  => 'GRE/GMAT',],
        ['id'    => 'other',    'name'  => '其他课程',],
    ];

    const COURSE_STATUS_NORMAL = 1;
    const COURSE_STATUS_OFLINE = 0;

    public function __construct() {
        $this->daoCourse = new Dao_Course_Mysql_Course () ;
        $this->daoTeacher = new Dao_Teacher_Mysql_Teacher();
        $this->daoTeacherCourse = new Dao_Teacher_Mysql_Course();
    }

    public function getCourseList ($courseno, $recommend, $coursename, $coursetype, $status, $starttime, $endtime, $pn = 0, $rn= 20){
        $arrConds = array();
        if (!empty($courseno)) {
            $arrConds['courseno'] = $courseno;
        }
        if ($recommend != -1) {
            $arrConds['recommend'] = $recommend;
        }
        if (!empty($coursename)) {
            $arrConds[] = 'coursename like "%' . $coursename . '%"';
        }
        if (!empty($coursetype)) {
            $arrConds['coursetype'] = $coursetype;
        }
        if (!empty($status)) {
            $arrConds['status'] = $status;
        }

        if (!empty($starttime)) {
            $arrConds[] = "createtime >= " . $starttime;
        }

        if (!empty($endtime)) {
            $arrConds[] = "createtime >= " . $endtime;
        }

        $arrFields = $this->daoCourse->simpleFields;

        $arrAppends = array(
            'order by id desc',
            "limit {$pn} , {$rn}",
        );

        $total = $this->daoCourse->getCntByConds($arrConds);
        $lists = $this->daoCourse->getListByConds($arrConds, $arrFields, null , $arrAppends);
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

    public function getCourseProfile ($courseid) {
        $details = $this->daoCourse->getRecordByConds(['courseid' => $courseid], $this->daoCourse->arrFieldsMap);
        if (empty($details)) {
            return [];
        }

        $teacher = [];
        $teacherids = $this->daoTeacherCourse->getListByConds(['courseid' => $courseid], $this->daoTeacherCourse->arrFieldsMap);
        if (!empty($teacherids)) {
            $teacherids = array_column($teacherids, 'teacherid');
            $arrConds = [
                'teacherid in (' . implode(',', $teacherids) . ')',
            ];
            $arrFields = $this->daoTeacher->simpleFields;
            $arrAppends = [
                'order by id desc',
            ];
            $teacher = $this->daoTeacher->getListByConds($arrConds, $arrFields, null, $arrAppends);
            $teacher = empty($teacher) ? [] : $teacher;
        }

        $details['createtime'] = date('Y-m-d H:i:s', $details['createtime']);
        $details['updatetime'] = date('Y-m-d H:i:s', $details['updatetime']);
        $details['teacher']    = $teacher;
        return $details;
    }

    public function modifyCourseProfile ($courseid , $profile) {
        if (empty($courseid)) {    
            $ret = $this->daoCourse->insertRecords($profile);
            if ($ret == false) {
                return false;
            }
            $courseid = $this->daoCourse->getInsertId();

        } else {
            $ret = $this->daoCourse->updateByConds(['courseid' => $courseid], $profile);
            if ($ret == false) {
                return false;
            }

            $ret = $this->daoTeacherCourse->deleteByConds(['courseid' => $courseid]);
            if ($ret == false) {
                return false;
            }
        }

        if (empty($profile['teacherids'])) {
            $data = [
                "courseid"      => $courseid,
                "coursetype"    => $profile['coursetype'],
                "createtime"    => time(),
                "updatetime"    => time(),
            ];
            foreach ($profile['teacherids'] as $teacherid) {
                $data['teacherid'] = $teacherid;
                $this->daoTeacherCourse->insertRecords($data);
            }
        }

        return true;
    }

    public function changeCourseStatus ($courseid, $status) {
        $ret = $this->daoCourse->updateByConds(['courseid' => $courseid], ['status' => $status]);
        if ($ret == false) {
            return false;
        }
        $this->daoTeacherCourse->updateByConds(['courseid' => $courseid], ['status' => $status]);
        return true;
    }

}