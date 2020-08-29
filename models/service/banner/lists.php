<?php

class Service_Banner_Lists {

    private $daoBanner ;

    public function __construct() {
        $this->daoBanner = new Dao_Banner_Mysql_Banner () ;
    }

    public function getBannerList ($bannertitle, $status, $starttime, $endtime, $pn = 0, $rn = 20){
        $arrConds = array();
        if (!empty($bannertitle)) {
            $arrConds[] = 'bannertitle like "%' . $bannertitle . '%"';
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

        $arrFields = $this->daoBanner->arrFieldsMap;

        $arrAppends = array(
            'order by bannerid desc',
            "limit {$pn} , {$rn}",
        );

        $total = $this->daoBanner->getCntByConds($arrConds);
        $lists = $this->daoBanner->getListByConds($arrConds, $arrFields, null , $arrAppends);
        if (empty($lists)) {
            return [0, []];
        }

        foreach ($lists as $index => $item) {
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            $item['updatetime'] = date('Y-m-d H:i:s', $item['updatetime']);
            $lists[$index] = $item;
        }

        return [$total, array_values($lists)];
    }


    public function getBannerProfile ($bannerid) {
        $details = $this->daoBanner->getRecordByConds(['bannerid' => $bannerid], $this->daoBanner->arrFieldsMap);
        if (empty($details)) {
            return [];
        }

        $details['createtime'] = date('Y-m-d H:i:s', $details['createtime']);
        $details['updatetime'] = date('Y-m-d H:i:s',$details['updatetime']);
        return $details;
    }

    public function modifyBannerProfile ($bannerid, $profile) {
        if (empty($bannerid)) {    
            $ret = $this->daoBanner->insertRecords($profile);
        } else {
            $ret = $this->daoBanner->updateByConds(['bannerid' => $bannerid], $profile);
        }
        return $ret == false ? false : true;
    }

    public function changeBannerStatus ($bannerid, $status) {
        return $this->daoBanner->updateByConds(['bannerid' => $bannerid], ['status'=>$status]) == false ? false : true;
    }

    public function deleteBannerProfile ($bannerid) {
        return $this->daoBanner->deleteByConds(['bannerid' => $bannerid]) == false ? false : true;
    }
}