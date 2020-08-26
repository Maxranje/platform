<?php
class Controller_Profile extends Zy_Core_Controller{

    public function ajaxGetArticleDetail () {
        $articleid = empty($this->_request['articleid']) ? 0 : intval($this->_request['articleid']);
        if (empty($articleid)) {
            $this->error(405, '无法检索到相关文章');
        }

        $serivce = new Service_Article_Lists ();
        $details = $serivce->getArticleProfile($articleid);
        
        if (empty($details)) {
            $this->error(405, '无法检索到相关文章');
        }

        return $details;
    }

    public function ajaxModifyArticle () {
        $articletitle = empty($this->_request['articletitle']) ? '' : trim($this->_request['articletitle']);
        $recommend = empty($this->_request['recommend']) ? '' : trim($this->_request['recommend']);
        $articleid = empty($this->_request['articleid']) ? 0 : intval($this->_request['articleid']);
        $articledesc = empty($this->_request['articledesc']) ? '' : trim($this->_request['articledesc']);
        $articleimg = empty($this->_request['articleimg']) ? '' : trim($this->_request['articleimg']);
        $articledetails = empty($this->_request['articledetails']) ? '' : trim($this->_request['articledetails']);
        $articleauthor = empty($this->_request['articleauthor']) ? '' : trim($this->_request['articleauthor']);
        $articletype = empty($this->_request['articletype']) ? '' : intval($this->_request['articletype']);
        $country = empty($this->_request['country']) ? '' : trim($this->_request['country']);
        $campusid = empty($this->_request['campusid']) ? '' : intval($this->_request['campusid']);

        if (empty($articletitle)) {
            $this->error(405, '文章标题不可以为空');
        } 
        if (!in_array($recommend, [0,1])) {
            $this->error(405, '文章推荐参数不正确');
        } 
        if(empty($articledesc) ) {
            $this->error(405, '文章描述不可以为空');
        }
        if (empty($articleimg)) {
            $this->error(405, '文章头图不可以为空');
        }
        if (empty($articledetails)) {
            $this->error(405, '文章详情不可以为空');
        }
        if (empty($articleauthor)) {
            $this->error(405, '文章作者不可以为空');
        }
        if (empty($articletype)) {
            $this->error(405, '文章类型不可以为空');
        }

        if ($articletype == Service_Article_Lists::ARTICLE_TYPE_CAMPUS && empty($campusid) ) {
            $this->error(405, '校区类型文章必须指定校区');
        }

        if ($articletype == Service_Article_Lists::ARTICLE_TYPE_ABROAD && (empty($country) || !in_array($country, ['england', 'america', 'canada']))) {
            $this->error(405, '申请规划描述文章必须指定国家, 且国家必须为 美国, 英国, 加拿大');
        }
        
        $profile = [
            'articletitle'      => $articletitle,
            'articledesc'       => $articledesc,
            'articleimg'        => $articleimg,
            'articleauthor'     => $articleauthor,
            'articledetails'    => $articledetails,
            'status'            => 2,
            'recommend'         => $recommend,
            'articletype'       => $articletype,
            'campusid'          => $campusid,
            'country'           => $country,
            'updatetime'        => time(),
        ];

        if (empty($articleid)) {
            $profile['createtime'] = time();
        }

        $serivce = new Service_Article_Lists ();
        $ret = $serivce->modifyArticleProfile($articleid, $profile);
        
        if ($ret == false) {
            $this->error(500, '服务错误, 请重试');
        }

        return $this->_data;
    }

    public function ajaxChangeArticleStatus () {
        $articleid  = empty($this->_request['articleid']) ? 0 : intval($this->_request['articleid']);
        $status     = empty($this->_request['status']) ? 2 : intval($this->_request['coursename']);

        if (empty($articleid) ) {
            $this->error(405, '文章id参数为空, 请重新尝试');
        }

        if (!in_array($status, [1, 2])) {
            $this->error(405, '状态参数不正确');
        }

        $serivce = new Service_Article_Lists ();
        $ret = $serivce->changeArticleStatus($articleid, $status);
        if ($ret == false) {
            $this->error(500, '编辑失败, 请重试');
        }

        return $this->_data;
    }
}
