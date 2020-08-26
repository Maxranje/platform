<?php

class Service_Account_Course {

    private $daoCourse ;

    private $daoUserCourse ;

    public function __construct() {
        $this->daoCourse = new Dao_Course_Mysql_Course () ;
        $this->daoUserCourse = new Dao_User_Mysql_Course ();
    }

    public function getCourseList ($userid, $pn = 0, $rn= 20){

        $arrConds = ['userid' => $userid];

        $arrFields = $this->daoCourse->arrFieldsMap;

        $arrAppends = array(
            'order by id desc',
            "limit {$pn} , {$rn}",
        );

        $total = $this->daoUserCourse->getCntByConds($arrConds);
        
        $lists = $this->daoUserCourse->getListByConds($arrConds, $arrFields, null , $arrAppends);
        if (empty($lists)) {
            return [0, []];
        }

        $courseid = array_column($lists, "courseid");
        $lists = array_column($lists, null, 'courseid');
        $arrConds = [
            'courseid in (' . implode(',', $courseid) . ')',
        ];
        $courselist = $this->daoCourse->getListByConds($arrConds, $this->daoCourse->simpleFields);

        foreach ($courselist as $index => $course) {
            $course['paystatus'] = empty($lists[$course['courseid']]['status']) ? 1 : $lists[$course['courseid']]['status'];
            $course['createtime'] = date('Y-m-d H:i:s', $course['createtime']);
            $course['updatetime'] = date('Y-m-d H:i:s', $course['updatetime']);
            $lists[$index] = $course;
        }

        return [$total, $courselist];
    }

}