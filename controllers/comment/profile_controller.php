<?php
class Controller_Profile extends Zy_Core_Controller{
    
    public function ajaxGetCommentProfile () {
        $id = empty($this->_request['id']) ? 0 : intval($this->_request['id']);
        if (empty($id)) {
            $this->error(405, '请求参数错误');
        }

        $serivce = new  Service_Comment_Lists ();
        $details = $serivce->getCommentProfile($id);
        
        if (empty($details)) {
            $this->error(405, '无法检索到相关评论');
        }

        return $details;
    }

    public function ajaxModifyComment () {

        $id = empty($this->_request['id']) ? 0 : intval($this->_request['id']);
        $type = empty($this->_request['type']) ? "" : trim($this->_request['type']);
        $name = empty($this->_request['name']) ? "" : trim($this->_request['name']);
        $avatar = empty($this->_request['avatar']) ? "" : trim($this->_request['avatar']);
        $content = empty($this->_request['content']) ? "" : trim($this->_request['content']);
        $score = empty($this->_request['score']) ? 0 : intval($this->_request['score']);
        $time = empty($this->_request['time']) ? '' : intval($this->_request['time']);

        if (!isset(Service_Comment_Lists::COMMENT_TYPE[$type])) {
            $this->error(405, '类型错误');
        }

        if (empty($name) || empty($avatar) || empty($content) || empty($time)) {
            $this->error(405, '名字, 头像, 内容, 时间不可以为空');
        }

        if ($score <= 0 ) {
            $this->error(405, '分数不可以为0');
        }

        $time = strtotime($time);

        $profile =[
            'type'  => $type,
            'name'  => $name,
            'avatar'=> $avatar,
            'content' => $content,
            'score' => $score,
            'time' => $time,
            'updatetime' => time(),
        ];

        if (empty($id)) {
            $profile['createtime'] = time();
        }

        $serivce = new  Service_Comment_Lists ();
        $ret = $serivce->modifyCommentProfile($id, $profile);
        
        if ($ret == false) {
            $this->error(405, '编辑保存失败, 请重试');
        }

        return $this->_data;
    }

    public function ajaxDeleteComment () {
        $id = empty($this->_request['id']) ? 0 : intval($this->_request['id']);

        if (empty($id) ){
            $this->error(405, 'id为空');
        }

        $serivce = new Service_Comment_Lists ();
        $ret = $serivce->deleteCommentProfile($id);
        if ($ret == false) {
            $this->error(500, '删除失败, 请重试');
        }

        return $this->_data;
    }
}
