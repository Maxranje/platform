<?php

class Service_Campus_Lists {

    private $daoCampus ;

    private $daoArticle;

    public function __construct() {
        $this->daoArticle = new Dao_Article_Mysql_Article ();
        $this->daoCampus = new Dao_Campus_Mysql_Campus () ;
    }

    public function getCampusList ($campusname, $status, $pn, $rn) {
        $arrConds = array();
        if (!empty($campusname)) {
            $arrConds[] = "campusname like '%{$campusname}%'";
        }
        if (!empty($status)) {
            $arrConds[] = 'status='.$status;
        }

        $arrFields = $this->daoCampus->simpleFields;

        $arrAppends = array(
            'order by campusid asc',
        );

        $total = $this->daoCampus->getCntByConds($arrConds);
        $lists = $this->daoCampus->getListByConds($arrConds, $arrFields, null , $arrAppends);
        if (empty($lists)) {
            return [0, []];
        }

        foreach ($lists as $index => $details) {
            $details['createtime'] = date('Y-m-d H:i:s', $details['createtime']);
            $details['updatetime'] = date('Y-m-d H:i:s',$details['updatetime']);
            $lists[$index] = $details;
        }

        return [$total, $lists];
    }


    public function getCampusProfile ($campusid) {
        $details = $this->daoCampus->getRecordByConds(['campusid' => $campusid], $this->daoCampus->arrFieldsMap);
        if (empty($details)) {
            return [];
        }

        $details['createtime'] = date('Y-m-d H:i:s', $details['createtime']);
        $details['updatetime'] = date('Y-m-d H:i:s',$details['updatetime']);
        return $details;
    }

    public function modifyCampusProfile ($campusid, $profile) {
        if (empty($campusid)) {    
            $ret = $this->daoCampus->insertRecords($profile);
        } else {
            $ret = $this->daoCampus->updateByConds(['campusid' => $campusid], $profile);
        }
        return $ret == false ? false : true;
    }

    public function changeCampusStatus ($campusid, $status) {
        if ($status==1) {
            $campus = $this->daoCampus->getRecordByConds(['campusid' => $campusid], $this->daoCampus->simpleFields);
            if (empty($campus['articleid'])) {
                throw new Zy_Core_Exception(405, '校区暂未指定具体文章, 先建立校区, 在文章列表找到文章配置校区, 而后校区上线');
            }
            $this->daoArticle->updateByConds(['articleid'=>$campus['articleid']], ['status'=>1]);
        }
        return $this->daoCampus->updateByConds(['campusid'=> $campusid], ['status'=> $status]) == false ? false:true;
    }
}