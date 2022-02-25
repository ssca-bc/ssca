<?php
class HymnsController extends BaseController {
    function actionIndex()
    {
        $hymns_model = new hymns_model();
        $praise_model = new praise_model();
        $sentences_model = new hymn_sentences_model();
        $sections_model = new hymn_sections_model();
        $hymn_match_model = new hymn_match_model();
        $chorus_match_model = new hymn_chorus_match_model();
        
        if(request('step','') == 'search')
        {
            $sql = "SELECT a.language,a.ps_id,a.song_id,b.title FROM {$praise_model->table_name} AS a LEFT JOIN {$hymns_model->table_name} AS b ON a.song_id=b.song_id WHERE a.language=2 AND b.language=2";
            $praise_list = $praise_model->query($sql);
            foreach($praise_list as &$praise)
            {
                $song = $hymns_model->find(array('language' => 1,'song_id' => $praise['song_id']));
                
                $praise['title'] .= "<br />".$song['title'];
            }
            
            $result = array(
                'status' => 'success',
                'list' => $praise_list,
                'paging' => ''
            );
            
            echo json_encode($result);
        }
        else
        {
            $this->display($GLOBALS['enabled_theme'].'/hymns/index.html');
        }
    }
    
    function actionGetLycisApi()
    {
        $hymns_model = new hymns_model();
        $praise_model = new praise_model();
        $sentences_model = new hymn_sentences_model();
        $sections_model = new hymn_sections_model();
        $hymn_match_model = new hymn_match_model();
        $chorus_match_model = new hymn_chorus_match_model();
        
        $song_id = (int)request('song_id',0);
        $language = (int)request('language',2);
        
        //中英文
        $hymn = $hymns_model->findAll(array('song_id' => $song_id));
        if(empty($hymn))
        {
            echo json_encode(array('status' => 'error'));
            exit;
        }
        
        $lycis = "";
        $lycis_c = "";
        $lycis_e = "";
        
        //中文
        $sections_c = $sections_model->findAll(array('language' => 2,'song_id' => $song_id),'section ASC');
        foreach($sections_c as $sc)
        {
            $lycis_c .= '<span style="color:red;">'.$sc['section'].'. </span>';
            $condition = array(
                'language' => 2,
                'song_id' => $song_id,
                'section' => $sc['section']
            );
            $sentences = $hymn_match_model->findAll($condition,'sentence_seq ASC');
            foreach($sentences as $st)
            {
                $sentence = $sentences_model->find(array('sentence_id' => $st['sentence_id']));
                $lycis_c .= $sentence['sentence']."<br />";
            }
            $lycis_c .= '<p style="margin-top:10px;"></p>';
            
            if($sc['chorus_id'] > 0)
            {
                $choruses = $chorus_match_model->findAll(array('language' => 2,'chorus_id' => $sc['chorus_id']));
                if(empty($choruses))
                    continue;
                
                $lycis_c .= '(副歌) ';
                foreach($choruses as $ch)
                {
                    $ch_sentence = $sentences_model->find(array('sentence_id' => $ch['sentence_id']));
                    $lycis_c .= $ch_sentence['sentence']."<br />";
                }
                $lycis_c .= '<p style="margin-top:10px;border-bottom:1px solid #eee;"></p>';
            }
            
        }
        
        //英文
        $sections_e = $sections_model->findAll(array('language' => 1,'song_id' => $song_id),'section ASC');
        foreach($sections_e as $se)
        {
            $lycis_e .= '<span style="color:red;">'.$se['section'].'. </span>';
            $condition = array(
                'language' => 1,
                'song_id' => $song_id,
                'section' => $se['section']
            );
            $sentences = $hymn_match_model->findAll($condition,'sentence_seq ASC');
            foreach($sentences as $ste)
            {
                $sentence = $sentences_model->find(array('sentence_id' => $ste['sentence_id']));
                $lycis_e .= $sentence['sentence']."<br />";
            }
            $lycis_e .= '<p style="margin-top:10px;"></p>';
            
            if($se['chorus_id'] > 0)
            {
                $choruses = $chorus_match_model->findAll(array('language' => 1,'chorus_id' => $se['chorus_id']));
                if(empty($choruses))
                    continue;
                
                $lycis_e .= '(Refrain) ';
                foreach($choruses as $che)
                {
                    $ch_sentence = $sentences_model->find(array('sentence_id' => $che['sentence_id']));
                    $lycis_e .= $ch_sentence['sentence']."<br />";
                }
                $lycis_e .= '<p style="margin-top:10px;border-bottom:1px solid #eee;"></p>';
            }
            
        }
        
        if($language == 1)
        {
            $lycis .= '<div style="width:100%;padding-top:5px;text-align:center;">'.$lycis_e.'</div>';
        }
        else
        {
            $lycis .= '<div style="width:100%;padding-top:5px;text-align:center;">'.$lycis_c.'</div>';
        }
        /*
        $lycis = '<div style="float:left;width:50%;padding-top:5px;text-align:center;">'.$lycis_c.'</div>';
        $lycis .= '<div style="float:left;width:50%;padding-top:5px;text-align:center;">'.$lycis_e.'</div>';
        */
        
        
        $result = array(
            'status' => 'success',
            'lycis' => $lycis,
        );
        echo json_encode($result);
    }
}