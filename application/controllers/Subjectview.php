<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 试题控制器
 * 显示试题相关视图操作
 * @author 王永强
 * @date 2016/05/11 10:08
 */
class Subjectview  extends MY_Controller {

    /**
     * 公司列表
     */
    public function company()
    {
        //登录判断
        if(!$this->is_login){
            header('Location: ' . $this->login_page);
            die;
        }

        $data_header['css'] = array('subject');
        $data_header['display_log'] = 'none';
        $data_header['display_center'] = 'block';
        $data_header['display'] = $this->is_com ? 'block' : 'none';
        $data_content['success'] = false;
        //获取公司列表
        $this->load->model('Subject_Model', 'model');
        $this->model->get_company($data_content);
        $data_bottom['js'] = array();

        $this->load->view('common/header', $data_header);
        $this->load->view('subject/company', $data_content);
        $this->load->view('common/bottom', $data_bottom);
    }


    /**
     * 试题列表
     * @param $user_id int 公司id
     */
    public function subject($user_id=null)
    {
        //登录判断
        if(!$this->is_login){
            header('Location: ' . $this->login_page);
            die;
        }

        //获取公司id
        if(!isset($user_id) || empty($user_id)){
            header('Location: ' . $this->main_page);
            die;
        }

        $data_header['css'] = array('subject');
        $data_header['display_log'] = 'none';
        $data_header['display_center'] = 'block';
        $data_header['display'] = $this->is_com ? 'block' : 'none';
        $data_content['success'] = false;
        //获取试题列表
        $data['user_id'] = $user_id;
        $this->load->model('Subject_Model', 'model');
        $this->model->get_subject($data, $data_content);
        $data_bottom['js'] = array();

        $this->load->view('common/header', $data_header);
        $this->load->view('subject/subject', $data_content);
        $this->load->view('common/bottom', $data_bottom);
    }


    /**
     * 试题详情
     * @param $subject_id int 试题id
     */
    public function detail($subject_id=null)
    {
        //登录判断
        if(!$this->is_login){
            header('Location: ' . $this->login_page);
            die;
        }

        //获取试题id
        if(!isset($subject_id) || empty($subject_id)){
            header('Location: ' . $this->main_page);
            die;
        }

        $data_header['css'] = array('subject');
        $data_header['display_log'] = 'none';
        $data_header['display_center'] = 'block';
        $data_header['display'] = $this->is_com ? 'block' : 'none';
        $data_content['success'] = false;
        //获取试题详细信息
        $data['subject_id'] = (int)$subject_id;
        $this->load->model('Subject_Model', 'model');
        $this->model->get_detail($data, $data_content);
        $data_bottom['js'] = array('module/detail');

        $this->load->view('common/header', $data_header);
        $this->load->view('subject/detail', $data_content);
        $this->load->view('common/bottom', $data_bottom);
    }


    /**
     * 试题题目列表
     * @param $subject_id int 试题id
     */
    public function subinfo($subject_id=null)
    {
        //登录判断
        if(!$this->is_login){
            header('Location: ' . $this->login_page);
            die;
        }

        //获取试题id
        if(!isset($subject_id) || empty($subject_id)){
            header('Location: ' . $this->main_page);
            die;
        }

        //判断用户是否已开始笔试
        $cache_id = 'Subject_begin_' . $this->uid;
        $this->load->driver('cache');
        $cache_data = $this->cache->file->get($cache_id);
        if(empty($cache_data) || $cache_data['subject_id'] != $subject_id){
            header('Location: ' . $this->main_page);
            die;
        }

        //笔试时间未到或已经结束
        $now_time = time();
        if($now_time < $cache_data['start_time'] || $now_time > $cache_data['end_time']){
            header('Location: ' . $this->main_page);
            die;
        }

        $data_header['css'] = array('subject');
        $data_header['display_log'] = 'none';
        $data_header['display_center'] = 'block';
        $data_header['display'] = $this->is_com ? 'block' : 'none';
        $data_content['success'] = false;
        //获取试题题目信息
        $data['subject_id'] = (int)$subject_id;
        $this->load->model('Subject_Model', 'model');
        if($this->model->get_subinfo($data, $data_content)){
            //缓存该题目信息
           $cache_data['subinfo'] = $data_content['cache_subinfo'];
           if(!$this->cache->file->save($cache_id, $cache_data, 3600*24)){
               unset($data_content['subinfo']);
               $data_content['error_info'] = '服务器忙, 请稍后再试';

           }else{
               $data_content['success'] = true;
               //计算剩余时间
               $this->load->helper('timestamp');
               $dhis = timestamp2dhis('h:i:s', $cache_data['end_time'] - $now_time);
               $dhis = explode(':', $dhis);
               $data_content['hour'] = $dhis[0];
               $data_content['minute'] = $dhis[1];
               $data_content['second'] = $dhis[2];
           }
           unset($data_content['cache_subinfo']);
        }
        $data_bottom['js'] = array('module/subinfo');

        $this->load->view('common/header', $data_header);
        $this->load->view('subject/subinfo', $data_content);
        $this->load->view('common/bottom', $data_bottom);
    }


    /**
     * 交卷完成提示
     */
    public function success()
    {
        //登录判断
        if(!$this->is_login){
            header('Location: ' . $this->login_page);
            die;
        }

        //是否已完成笔试
        if(!isset($_SESSION['finish'])){
            header('Location: ' . $this->main_page);
            die;
        }

        $data_header['css'] = array('success');
        $data_header['display_log'] = 'none';
        $data_header['display_center'] = 'block';
        $data_header['display'] = $this->is_com ? 'block' : 'none';
        $data_bottom['js'] = array();

        $this->load->view('common/header', $data_header);
        $this->load->view('subject/success');
        $this->load->view('common/bottom', $data_bottom);
    }


    /**
     * 已发布试题
     */
    public function published()
    {
        //登录判断
        if(!$this->is_login){
            header('Location: ' . $this->login_page);
            die;
        }

        //个人用户
        if(!$this->is_com){
            header('Location: ' . $this->main_page);
            die;
        }

        $data_header['css'] = array('published');
        $data_header['display_log'] = 'none';
        $data_header['display_center'] = 'block';
        $data_header['display'] = 'block';
        $data_left['display'] = 'block';
        $data_left['target'] = 'published';
        $data_content['success'] = false;
        //获取已发布试题
        $this->load->model('Subject_Model', 'model');
        $this->model->get_published(array('user_id'=>$this->uid), $data_content);
        $data_bottom['js'] = array('module/left', 'module/published');

        $this->load->view('common/header', $data_header);
        $this->load->view('common/left', $data_left);
        $this->load->view('subject/published', $data_content);
        $this->load->view('common/bottom', $data_bottom);
    }


    /**
     * 考试结果
     * @param $subject_id int 试题id
     */
    public function result($subject_id=null)
    {
        //登录判断
        if(!$this->is_login){
            header('Location: ' . $this->login_page);
            die;
        }

        //个人用户
        if(!$this->is_com){
            header('Location: ' . $this->main_page);
            die;
        }

        //获取试题id
        if(!isset($subject_id) || empty($subject_id)){
            header('Location: ' . $this->main_page);
            die;
        }

        $data_header['css'] = array('result');
        $data_header['display_log'] = 'none';
        $data_header['display_center'] = 'block';
        $data_header['display'] = 'block';
        $data_left['display'] = 'block';
        $data_left['target'] = 'published';
        $data_content['success'] = false;
        //获取考试结果
        $data['user_id'] = $this->uid;
        $data['subject_id'] = (int)$subject_id;
        $this->load->model('Subject_Model', 'model');
        $this->model->get_result($data, $data_content);
        $data_bottom['js'] = array('module/left');

        $this->load->view('common/header', $data_header);
        $this->load->view('common/left', $data_left);
        $this->load->view('subject/result', $data_content);
        $this->load->view('common/bottom', $data_bottom);
    }


    /**
     * 发布新试题
     */
    public function pub()
    {
        //登录判断
        if(!$this->is_login){
            header('Location: ' . $this->login_page);
            die;
        }

        //个人用户
        if(!$this->is_com){
            header('Location: ' . $this->main_page);
            die;
        }

        $data_header['css'] = array('default', 'default.date', 'default.time', 'pub');
        $data_header['display_log'] = 'none';
        $data_header['display_center'] = 'block';
        $data_header['display'] = 'block';
        $data_left['display'] = 'block';
        $data_left['target'] = 'pub';
        $data_bottom['js'] = array('picka/picker', 'picka/picker.date', 'picka/picker.time', 'picka/translations/zh_CN', 'module/left', 'module/pub');

        $this->load->view('common/header', $data_header);
        $this->load->view('common/left', $data_left);
        $this->load->view('subject/pub');
        $this->load->view('common/bottom', $data_bottom);
    }

}
