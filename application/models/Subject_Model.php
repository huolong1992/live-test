<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 试题模型
 * @author 王永强
 * @date 2016/05/14 18:02
 */
class Subject_Model  extends MY_Model {

    /**
     * 获取公司
     * @param &$ret array 返回前端的的数据
     */
    public function get_company(&$ret)
    {
        $sql = 'select user_id, company from user where is_com=1';
        $company = $this->db->query($sql);
        if($company->num_rows() <= 0){
            $ret['error_info'] = '暂无公司发布试题';
            return false;
        }

        $ret['success'] = true;
        $ret['company'] = $company->result_array();
    }


    /**
     * 获取试题
     * @param $data array
     * @param &$ret array 返回前端的数据
     */
    public function get_subject($data, &$ret)
    {
        $sql = 'select subject_id, title, end_time from subject where user_id=' . $data['user_id'];
        $subject = $this->db->query($sql);
        if($subject->num_rows() <= 0){
            $ret['error_info'] = '该公司暂时无笔试试题';
            return false;
        }

        //过滤掉已经结束的笔试
        $subject = $subject->result_array();
        $ret['subject'] = array();
        foreach ($subject as $v) {
            if(time() < $v['end_time']){
                $v['href'] = '/live-test/subjectview/detail/' . $v['subject_id'];
                unset($v['subject_id']);
                unset($v['end_time']);
                $ret['subject'][] = $v;
            }
        }

        unset($subject);
        $ret['success'] = true;
        return true;
    }


    /**
     * 获取试题详细信息
     * @param $data array
     * @param &$ret array 返回前端的数据
     */
    public function get_detail($data, &$ret)
    {
        $sql = 'select subject_id, title, start_time, end_time, single, multiple, tips from subject where subject_id=' . $data['subject_id'];
        $subject = $this->db->query($sql);
        if($subject->num_rows() <= 0){
            $ret['error_info'] = '试题不存在';
            return false;
        }

        $ret['success'] = true;
        $ret['detail'] = $subject->row_array();
        return true;
    }


    /**
     * 获取试题题目
     * @param $data array
     * @param &$ret array 返回前端的数据
     */
    public function get_subinfo($data, &$ret)
    {
        $sql = 'select * from subinfo where subject_id=' . $data['subject_id'];
        $subinfo = $this->db->query($sql);
        if($subinfo->num_rows() <= 0){
            $ret['error_info'] = '该试题暂时无题目信息';
            return false;
        }

        foreach ($subinfo->result_array() as $v) {
            //需缓存的题目
            $cache_value['subinfo_id'] = (int)$v['subinfo_id'];
            $cache_value['score'] = (int)$v['score'];
            $cache_value['skill'] = (int)$v['skill'];
            $cache_value['right_answer'] = (int)$v['right_option'];

            //返回前端的题目
            $ret_value['subinfo_id'] = $v['subinfo_id'];
            $ret_value['name'] = $v['name'];
            $ret_value['score'] = $v['score'];
            $ret_value['type'] = $v['type'];
            $ret_value['options'] = explode('#@#', $v['options']);

            $ret['cache_subinfo'][] = $cache_value;
            $ret['subinfo'][] = $ret_value;
        }

        return true;
    }


    /**
     * 交卷动作
     * @param $data array
     * @param &$ret array 返回前端的数据
     */
    public function finish($data, &$ret)
    {
        $mail = $data['mail'];
        $subject_id = $data['subject_id'];
        $user_id = $data['user_id'];
        $total_score = $data['total_score'];

        //开启事务
        $this->db->trans_begin();

        //该用户已完成笔试
        $sql = "update subject set done_mail_list=concat(done_mail_list, ',', '$mail') where subject_id=$subject_id";
        $this->db->query($sql);

        //该用户所属分数段
        if($total_score >= 90){
            $table = 'ninety_up';
        }elseif($total_score >= 70){
            $table = 'seventy_up';
        }else{
            $table = 'seventy_down';
        }
        $sql = "update subject set %s=concat(%s, ',', '$mail') where subject_id=$subject_id";
        $sql = sprintf($sql, $table, $table);
        $this->db->query($sql);

        //更新用户技能
        foreach ($data['user_skill'] as $skill_id => $score) {
            $sql = "update user_skill set score=score+$score where user_id=$user_id and skill_id=$skill_id";
            $this->db->query($sql);
        }

        //事务状态判断
        if($this->db->trans_status() === false){
            $this->db->trans_rollback();
            $ret['error_info'] = '服务器忙, 请稍后再试!';
            return false;
        }

        $this->db->trans_commit();
        return true;
    }


    /**
     * 添加试题
     * @param $data array
     * @param &$ret array 返回前端的数据
     */
    public function add_subject($data, &$ret)
    {
    	$user_id = $data['uid'];
    	$title = $data['subject']['name'];
    	$start_time = strtotime($data['subject']['start_time']);
    	$end_time = strtotime($data['subject']['end_time']);
    	$tips = explode("\n", $data['subject']['tips']);
    	foreach ($tips as $k => $v) {
    	    $tips[$k] = '&lt;p&gt;' . ($k+1) . ', ' . $v . '&lt;/p&gt;';
    	}
    	$tips = implode('', $tips);
    	$mail_list = $data['subject']['mail_list'];

    	//单选/多选题目个数
    	$single = $multiple = 0;
    	foreach ($data['subinfo'] as $v) {
    	    if($v['type'] == 1){
    	        $single++;
    	    }else{
    	        $multiple++;
    	    }
    	}

        //插入数据库, 开启事务
        $this->db->trans_begin();

        //插入subject表
		 $sql = "insert into subject(user_id, title, start_time, end_time, single, multiple, tips, mail_list) values($user_id, '$title', $start_time, $end_time, $single, $multiple, '$tips', '$mail_list')";
		 if($this->db->query($sql)){
		     $subject_id = $this->db->insert_id();

		     //插入subinfo表
			 $sql = "insert into subinfo(subject_id, name, score, type, skill, options, right_option) values";
			 foreach ($data['subinfo'] as $v) {
			 	 $name = $v['title'];
			 	 $score = (int)$v['score'];
			 	 $type = (int)$v['type'];
			 	 $skill = (int)$v['skill'];
			 	 $options = $v['option'];
			 	 $right_option = $v['right_answer'];
			     $sql .= "($subject_id, '$name', $score, $type, $skill, '$options', '$right_option'),";
			 }
			 $sql = substr($sql, 0, -1);
			 $this->db->query($sql);
		 }

		 //事务状态判断
        if($this->db->trans_status() === false){
            $this->db->trans_rollback();
            $ret['error_info'] = '服务器忙, 请稍后再试!';
            return false;
        }

        $this->db->trans_commit();
        $ret['success'] = true;
        return true;
	 }

    
    /**
     * 获取已发布的试题
     * @param $data array
     * @param &$ret array 返回前端的数据
     */
    public function get_published($data, &$ret)
    {
        $sql = 'select subject_id, title, end_time from subject where user_id=' . $data['user_id'];
        $subject = $this->db->query($sql);
        if($subject->num_rows() <= 0){
            $ret['error_info'] = '还没有发布试题哦, 赶快去发布吧, <a href="/live-test/subjectview/pub">点击发布试题</a>';
            return false;
        }

        //区分考试已结束和未结束试题
        $subject = $subject->result_array();
        foreach ($subject as $v) {
            if(time() > $v['end_time']){
                //已结束
                $v['tag'] = 2;
                $v['href'] = '/live-test/subjectview/result/' . $v['subject_id'];
            }else{
                //未结束
                $v['tag'] = 1;
                $v['href'] = '/live-test/subjectview/modify/' . $v['subject_id'];
            }
            unset($v['end_time']);
            unset($v['subject_id']);
            $ret['subject'][] = $v;
        }

        unset($subject);
        $ret['success'] = true;
        return true;
    }


    /**
     * 获取考试结果
     * @param $data array
     * @param &$ret array 返回前端的数据
     */
    public function get_result($data, &$ret)
    {
        $sql = 'select ninety_up, seventy_up, seventy_down from subject where subject_id=' . $data['subject_id'] . ' and user_id=' . $data['user_id'];
        $subject = $this->db->query($sql);
        if($subject->num_rows() <= 0){
            $ret['error_info'] = '不存在该数据';
            return false;
        }

        $subject = $subject->row_array();
        $ret['ninety_up'] = explode(',', substr($subject['ninety_up'], 1));
        $ret['seventy_up'] = explode(',', substr($subject['seventy_up'], 1));
        $ret['seventy_down'] = explode(',', substr($subject['seventy_down'], 1));
        $ret['success'] = true;
        return true;
    }


    /**
     * 验证用户合法性
     * @param $data array
     * @param &$ret array 返回前端的数据
     */
    public function check_user($data, &$ret)
    {
        //考试时间, 合法性判断
        $sql = 'select start_time, end_time, mail_list, done_mail_list from subject where subject_id=' . (int)$data['subject_id'];
        $subject = $this->db->query($sql);
        if($subject->num_rows() <= 0){
            $ret['error_info'] = '非法请求';
            return false;
        }
        
        $subject = $subject->row_array();
        $now_time = time();
        if($now_time < $subject['start_time']){
            //笔试未开始
            $ret['error_info'] = '笔试未开始, 请耐心等待';
            return false;

        }elseif($now_time > $subject['end_time']){
            //笔试已结束
            $ret['error_info'] = '笔试已结束';
            return false;

        }elseif(strpos($subject['mail_list'], $data['mail']) === false){
            //该用户无笔试资格
            $ret['error_info'] = '您无笔试资格';
            return false;

        }elseif(strpos($subject['done_mail_list'], $data['mail']) !== false){
            //该用户已经完成了笔试
            $ret['error_info'] = '您已参加过笔试';
            return false;
        }

        $ret['start_time'] = $subject['start_time'];
        $ret['end_time'] = $subject['end_time'];
        return true;
    }
}
