<?php

class Service_Comment_Lists {

    private $daoComment ;

    const COMMENT_TYPE = [
        'toefl' => '托福牛人',
        'ielts' => '雅思牛人',
        'sat'   => 'SAT牛人',
        'other' => '其他牛人',
    ];

    public function __construct() {
        $this->daoComment = new Dao_Comment_Mysql_Comment () ;
    }

    public function getCommentList ($type, $starttime, $endtime, $pn = 0, $rn = 20){

        $arrConds = array();
        if (!empty($type)) {
            $arrConds['type'] = $type;
        }
        if (!empty($starttime)) {
            $arrConds[] = 'createtime >=' . $starttime;
        }
        if (!empty($endtime)) {
            $arrConds[] = 'createtime <=' . $endtime;
        }

        $arrFields = $this->daoComment->arrFieldsMap;

        $arrAppends = [
            'order by id desc',
            "limit {$pn}, {$rn}",
        ];

        $total = $this->daoComment->getCntByConds($arrConds);
        $lists = $this->daoComment->getListByConds($arrConds, $arrFields, null , $arrAppends);

        if (empty($lists)) {
            return [0, []];
        }

        foreach ($lists as $index => $record) {
            $record['createtime'] = date('Y-m-d H:i:s', $record['createtime']);
            $record['updatetime'] = date('Y-m-d H:i:s', $record['updatetime']);
            $lists[$index] = $record;
        }

        return [$total, $lists];
    }

    public function getCommentProfile ($id) {
        $details = $this->daoComment->getRecordByConds(['id' => $id], $this->daoComment->arrFieldsMap);
        if (empty($details)) {
            return [];
        }

        $details['createtime'] = date('Y-m-d H:i:s', $details['createtime']);
        $details['updatetime'] = date('Y-m-d H:i:s',$details['updatetime']);
        return $details;
    }

    public function modifyCommentProfile ($id, $profile) {
        if (empty($id)) {    
            $ret = $this->daoComment->insertRecords($profile);
        } else {
            $ret = $this->daoComment->updateByConds(['id' => $id], $profile);
        }
        return $ret == false ? true : false;
    }

    public function deleteCommentProfile ($id) {
        return $this->daoComment->deleteByConds(['id'=>$id]) == false ? false : true;
    }
}