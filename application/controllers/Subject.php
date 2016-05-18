<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 试题控制器
 * 执行试题相关操作
 * @author 王永强
 * @date 2016/05/14 19:49
 */
class Subject  extends MY_Controller {

    /**
     * 发布试题
     */
    public function pub()
    {
        //登录判断
       if(!$this->is_login){
           $this->ret['status'] = 503;
           $this->flush();
       }
       $this->get_input();

       //个人用户
       if(!$this->is_com){
           $this->ret['error_info'] = '非法操作';
           $this->flush();
       }

       //数据过滤
       if(!isset($this->posts['subject']) || !is_array($this->posts['subject']) ||!isset($this->posts['subinfo']) || !is_array($this->posts['subinfo'])){
           $this->ret['error_info'] = '参数错误';
           $this->flush();
       }
       $data_keys = array('name', 'start_time', 'end_time', 'tips', 'mail_list');
       foreach ($data_keys as $k) {
           if(!isset($this->posts['subject'][$k]) || empty($this->posts['subject'][$k])){
               $this->ret['error_info'] = '参数错误';
               $this->flush();
           }
           $this->posts['subject'][$k] = htmlspecialchars($this->posts['subject'][$k]);
       }
       $data_keys = array('type', 'skill', 'title', 'score', 'option', 'right_answer');
       foreach ($data_keys as $key) {
           foreach ($this->posts['subinfo'] as $index => $sub_array) {
              if(!is_array($sub_array) || !isset($sub_array[$key]) || empty($sub_array[$key])){
                  $this->ret['error_info'] = '参数错误';
                  $this->flush();
              }
              $this->posts['subinfo'][$index][$key] = htmlspecialchars($this->posts['subinfo'][$index][$key]);
           }
       }

       //插入数据库
       $this->posts['uid'] = $this->uid;
       $this->load->model('Subject_Model', 'model');
       $this->model->add_subject($this->posts, $this->ret);
       $this->flush();
    }


    /**
     * 开始考试
     */
    public function begin()
    {
       //登录判断
       if(!$this->is_login){
           $this->ret['status'] = 503;
           $this->flush();
       }
       $this->get_input();

       //数据过滤
       $this->validate(array('subject_id'));

       //获取试题题目
       $this->posts['mail'] = $this->mail;
       $this->load->model('Subject_Model', 'model');
       if($this->model->check_user($this->posts, $this->ret)){
           //用缓存方式记录该用户开始笔试
           $cache_id = 'Subject_begin_' . $this->uid;
           $cache_data = array('subject_id' => (int)$this->posts['subject_id'], 'start_time' => (int)$this->ret['start_time'], 'end_time' => (int)$this->ret['end_time']);
           $this->load->driver('cache');
           if(!$this->cache->file->save($cache_id, $cache_data, 3600*24)){
               $this->ret['error_info'] = '服务器忙, 请稍后再试';
           }else{
               $this->ret['success'] = true;
           }
           unset($this->ret['start_time']);
           unset($this->ret['end_time']);
       }

       $this->flush();
    }


    /**
     * 交卷
     */
    public function finish()
    {
       //登录判断
       if(!$this->is_login){
           $this->ret['status'] = 503;
           $this->flush();
       }
       $this->get_input();

       //数据过滤
       if(!isset($this->posts['subinfo']) || !is_array($this->posts['subinfo'])){
           $this->ret['error_info'] = '参数错误';
           $this->flush();
       }
       foreach ($this->posts['subinfo'] as $k => $v) {
           if(!isset($v['subinfo_id']) || !isset($v['answer'])){
               $this->ret['error_info'] = '参数错误';
               $this->flush();
           }
           $this->posts['subinfo'][$k]['subinfo_id'] = (int)$v['subinfo_id'];
           $this->posts['subinfo'][$k]['answer'] = (int)$v['answer'];
       }

       //获取缓存的题目信息, 跟用户答案比对
       $cache_id = 'Subject_begin_' . $this->uid;
       $this->load->driver('cache');
       $cache_data = $this->cache->file->get($cache_id);
       if(empty($cache_data)){
           $this->ret['error_info'] = '非法操作';
           $this->flush();
       }

       //笔试时间未到或已经结束
        $now_time = time();
        if($now_time < $cache_data['start_time'] || $now_time > $cache_data['end_time']){
            $this->ret['error_info'] = '非法操作';
            $this->flush();
        }

       //分数, 技能统计
       $user_skill[1] = $user_skill[2] = $user_skill[3] = $user_skill[4] = $user_skill[5] = 0;
       foreach ($this->posts['subinfo'] as $k => $v) {
           if($v['subinfo_id'] == $cache_data['subinfo'][$k]['subinfo_id'] && $v['answer'] == $cache_data['subinfo'][$k]['right_answer']){
               $skill = $cache_data['subinfo'][$k]['skill'];
               $user_skill[$skill] += $cache_data['subinfo'][$k]['score'];
           }
       }

       //将结果插入数据库
       $data['user_id'] = $this->uid;
       $data['subject_id'] = $cache_data['subject_id'];
       $data['mail'] = $this->mail;
       $data['total_score'] = array_sum($user_skill);
       $data['user_skill'] = $user_skill;
       unset($this->posts);
       unset($cache_data);
       $this->load->model('Subject_Model', 'model');
       if($this->model->finish($data, $this->ret)){
           //成功, 则删除缓存数据
           $this->cache->file->delete($cache_id);
           //记录已经完成笔试
           $_SESSION['finish'] = true;
           $this->ret['success'] = true;
       }

       $this->flush();
    }
}
