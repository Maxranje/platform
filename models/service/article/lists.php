<?php

class Service_Article_Lists {

    private $articleDao ;
    private $campusDao;

    const ARTICLE_TYPE_NORMAL   = 1;
    const ARTICLE_TYPE_ABROAD   = 2;
    const ARTICLE_TYPE_CAMPUS   = 3;

    public function __construct() {
        $this->articleDao = new Dao_Article_Mysql_Article () ;
        $this->campusDao = new Dao_Campus_Mysql_Campus();
    }

    public function getArticleList ($articletitle, $articletype, $recommend, $status, $starttime, $endtime, $pn, $rn){
        $arrConds = [];
        if (!empty($articletitle)) {
            $arrConds[] = 'articletitle like "%' .$articletitle. '%"';
        }
        if (!empty($articletype)) {
            $arrConds['articletype'] = $articletype;
        }
        if ($recommend != -1) {
            $arrConds['recommend'] = $recommend;
        }
        if (!empty($status)) {
            $arrConds['status'] = $status;
        }
        if (!empty($starttime)) {
            $arrConds[] = 'createtime >=' . $starttime;
        }
        if (!empty($endtime)) {
            $arrConds[] = 'createtime <=' . $endtime;
        }

        $arrFields = $this->articleDao->simpleFields ;

        $arrAppends = [
            'order by id desc',
            "limit {$pn} , {$rn}",
        ];

        $total = $this->articleDao->getCntByConds($arrConds);
        $articlelist = $this->articleDao->getListByConds($arrConds, $arrFields, NULL, $arrAppends);
        if (empty($articlelist)) {
            return [0, []];
        }

        foreach ($articlelist as $index => $article) {
            $article['createtime']  = date('Y-m-d H:i:s', $article['createtime']);
            $article['updatetime']  = date('Y-m-d H:i:s', $article['updatetime']);
            $articlelist[$index] = $article;
        }

        return [$total, array_values($articlelist)];
    }

    public function getArticleProfile ($articleId) {
        $article = $this->articleDao->getRecordByConds(['articleid' => $articleId], $this->articleDao->arrFieldsMap);
        if (empty($article)) {
            return false;
        }

        $campus = [];
        if ($article['type'] == self::ARTICLE_TYPE_CAMPUS) {
            $campus = $this->campusDao->getRecordByConds(['articleid' => $articleId], $this->campusDao->simpleFields);
            if (!empty($campus)) {
                $campus = ['campusid' => $campus['campusid'], 'campusname' => $campus['campusname']] ;
            }
        }
    
        $article['createtime'] = date('Y-m-d H:i:s', $article['createtime']);
        $article['updatetime'] = date('Y-m-d H:i:s', $article['updatetime']);
        $article['campus']     = $campus;
        return $article;
    }

    public function modifyArticleProfile ($articleid, $profile) {
        
        if (empty($articleid)) {
            $ret = $this->articleDao->insertRecords($profile);
            if ($ret == false ) {
                return false;
            }
            $articleid = $this->articleDao->getInsertId();
        } else {
            $ret = $this->articleDao->updateByConds(['articleid' => $articleid], $profile);
            if ($ret == false) {
                return false;
            }
        }

        if ($profile['articletype'] == self::ARTICLE_TYPE_CAMPUS) {
            $campus = $this->daoCampus->getRecordByConds(['campusid' => $profile['campusid']], $this->daoCampus->simpleFields);
            if (empty($campus)) {
                $this->articleDao->updateByConds(['articleid' => $articleid], ['articletype' => self::ARTICLE_TYPE_NORMAL]);    
            } else {
                $this->articleDao->updateByConds(['articleid' => $campus['articleid']], ['articletype' => self::ARTICLE_TYPE_NORMAL]);
                $this->campusDao->updateByConds(['campusid' => $profile['campusid']], ['articleid' => $articleid]);
            }
        }

        return $ret == false ? false : true;
    }

    public function changeArticleStatus ($articleid, $status) {
        $article = $this->articleDao->getRecordByConds(['articleid'=>$articleid], $this->articleDao->simpleFields);
        if (empty($article)) {
            return false;
        }

        if ($article['articletype'] == self::ARTICLE_TYPE_CAMPUS && $status == 2) {
            $campus = $this->campusDao->getRecordByConds(['articleid' => $articleid], $this->campusDao->simpleFields);
            if ($campus['status'] == 1) {
                throw new Zy_Core_Exception(405, '校区依然处于上线状态, 文章不可以点击下线, 需要先下线对应的校区');
            }
        }
        
        $this->articleDao->updateByConds(['articleid' => $articleid], ['status' => $status]) == false ? false : true ;
    }

}