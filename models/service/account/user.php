<?php

class Service_Account_User {

    private $daoUser ;

    const USER_TYPE_INNER = 2;
    const USER_TYPE_NORMAL = 1;
    
    const USER_STATUS_NORMAL = 1;
    const USER_STATUS_BLOCK  = 2;

    public function __construct() {
        $this->daoUser = new Dao_User_Mysql_User () ;
    }

    public function getUserList ($uname, $phone, $type, $sex, $starttime, $endtime, $pn, $rn) {
        $arrConds = [];
        if (!empty($uname)) {
            $arrConds[] = 'uname like "%' .$uname. '%"';
        }
        if (!empty($phone)) {
            $arrConds['phone'] = $phone;
        }
        if (!empty($type)) {
            $arrConds['type'] = $type;
        }
        if (!empty($sex)) {
            $arrConds['sex'] = $sex;
        }
        if (!empty($starttime)) {
            $arrConds[] = 'regtime >=' . $starttime;
        }
        if (!empty($endtime)) {
            $arrConds[] = 'regtime <=' . $endtime;
        }

        $arrFields = $this->daoUser->arrFieldsMap ;

        $arrAppends = [
            'order by userid desc',
            "limit {$pn} , {$rn}",
        ];

        $total = $this->daoUser->getCntByConds($arrConds);
        $lists = $this->daoUser->getListByConds($arrConds, $arrFields, NULL, $arrAppends);
        if (empty($lists)) {
            return [0, []];
        }

        foreach ($lists as $index => $user) {
            $user['regtime']    = date('Y-m-d H:i:s', $user['regtime']);
            $user['birthday']    = date('Y-m-d H:i:s', $user['birthday']);
            $lists[$index]      = $user;
        }

        return [$total, array_values($lists)];
    }

    public function getUserInfo ($userid){
        $userinfo = $this->daoUser->getRecordByConds(['userid' => $userid], $this->daoUser->arrFieldsMap);
        if (empty($userinfo)) {
            return [];
        }

        $userinfo['regtime']    = date('Y-m-d H:i:s', $userinfo['regtime']);
        $userinfo['birthday']   = date('Y-m-d H:i:s', $userinfo['birthday']);
        $userinfo['discount']   = $userinfo['discount'] . '(折)';
        return $userinfo;
    }


    public function changeUserVip ($userid, $isvip, $discount) {
        return $this->daoUser->updateByConds(['userid' => $userid], ['isvip'  => $isvip,'discount' => $discount]) == false ? false : true;
    }

    public function changeUserType ($userid, $type) {
        return $this->daoUser->updateByConds(['userid' => $userid], ['type'  => $type]) == false ? false : true;
    }

    public function changeUserStatus ($userid, $status) {
        return $this->daoUser->updateByConds(['userid' => $userid], ['status'  => $status]) == false ? false : true;
    }
}