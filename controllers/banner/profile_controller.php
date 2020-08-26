<?php
class Controller_Profile extends Zy_Core_Controller{

    public function ajaxGetBannerProfile () {
        $bannerid = empty($this->_request['bannerid']) ? 0 : intval($this->_request['bannerid']);
        if (empty($bannerid)) {
            $this->error(405, 'banner 请求参数不可以为空');
        }

        $serivce = new  Service_Banner_Lists ();
        $details = $serivce->getBannerProfile($bannerid);
        
        if (empty($details)) {
            $this->error(405, '无法检索到相关banner');
        }

        return $details;
    }

    public function ajaxModifyBanner () {
        $bannerid = empty($this->_request['bannerid']) ? 0 : intval($this->_request['bannerid']);
        $bannertitle = empty($this->_request['bannertitle']) ? '' : trim($this->_request['bannertitle']);
        $bannerurl = empty($this->_request['bannerurl']) ? '' : trim($this->_request['bannerurl']);
        $bannerimg = empty($this->_request['bannerimg']) ? '' : trim($this->_request['bannerimg']);
        $weight = empty($this->_request['weight']) ? 0 : intval($this->_request['weight']);

        if (empty($bannertitle)){
            $this->error(405, 'banner 标题不可以为空');
        } 
        if (empty($bannerurl) ) {
            $this->error(405, 'banner 跳转地址不可以为空');
        } 
        if (empty($bannerimg)) {
            $this->error(405, 'banner 头图地址不可以为空');
        }
        if ($weight < 0 || $weight > 100) {
            $this->error(405, 'banner 权重只能在 0-100 之间');
        }

        $profile = [
            "bannertitle"   => $bannertitle,
            "bannerurl"     => $bannerurl,
            "bannerimg"     => $bannerimg,
            "weight"        => $weight,
            "status"        =>2,
            "updatetime"    => time(),
        ];
        if (empty($bannerid)) {
            $profile['createtime'] = time();
        }

        $serivce = new Service_Banner_Lists ();
        $ret = $serivce->modifyBannerProfile($bannerid, $profile);
        if ($ret == false) {
            $this->error(500, '编辑失败, 请重试');
        }

        return $this->_data;
    }

    public function ajaxChangeBannerStatus () {
        $bannerid = empty($this->_request['bannerid']) ? 0 : intval($this->_request['bannerid']);
        $status = empty($this->_request['status']) ? 2 : intval($this->_request['status']);

        if (empty($bannerid)) {
            $this->error(405, 'bannerid 不可以为空');
        }

        if (!in_array($status, [1, 2])) {
            $this->error(405, '状态参数不正确');
        }

        $serivce = new  Service_Banner_Lists ();
        $ret = $serivce->changeBannerStatus($bannerid, $status);
        
        if ($ret == false) {
            $this->error( 500, '删除失败, 请重试');
        }

        return $this->_data;
    }

    public function ajaxDeleteBanner () {
        $bannerid = empty($this->_request['bannerid']) ? 0 : intval($this->_request['bannerid']);
        if (empty($bannerid)) {
            $this->error(405, 'bannerid 不可以为空');
        }

        $serivce = new  Service_Banner_Lists ();
        $ret = $serivce->deleteBannerProfile($bannerid);
        
        if ($ret == false) {
            $this->error( 500, '删除失败, 请重试');
        }

        return $this->_data;
    }
}
