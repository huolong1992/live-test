<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户控制器
 * 执行用户相关视图显示操作
 * @author 王永强
 * @date 2016/05/09 17:13
 */
class Userview  extends MY_Controller {

    /**
     * 注册/登录
     * @param $target int 1:登录, 2:注册
     */
    public function log($target=1)
    {
        //登录判断
        if($this->is_login){
            header('Location: ' . $this->main_page);
            die;
        }

        $data_header['css'] = array('log');
        $data_header['display_log'] = 'block';
        $data_header['display_center'] = 'none';
        $data_header['display'] = 'none';
        $data_content['target'] = ($target==1 || $target==2) ? $target : 1;
        $data_bottom['js'] = array('module/log');

        $this->load->view('common/header', $data_header);
        $this->load->view('user/log', $data_content);
        $this->load->view('common/bottom', $data_bottom);
    }


    /**
     * 个人信息
     */
    public function userinfo()
    {
        //登录判断
        if(!$this->is_login){
            header('Location: ' . $this->login_page);
            die;
        }

        $data_header['css'] = array('userinfo');
        $data_header['display_log'] = 'none';
        $data_header['display_center'] = 'block';
        $data_header['display'] = 'none';
        $data_left['display'] = 'none';
        $data_left['target'] = 'userinfo';
        $data_content['success'] = false;
        $data_bottom['js'] = array('module/left');

        //公司
        if($this->is_com){
            $data_header['display'] = 'block';
            $data_left['display'] = 'block';
        }

        //获取个人信息
        $this->load->model('User_Model', 'model');
        $data = array(
            'uid' => $this->uid,
            'is_com' => $this->is_com
            );
        $this->model->get_userinfo($data, $data_content);

        $this->load->view('common/header', $data_header);
        $this->load->view('common/left', $data_left);
        $this->load->view('user/userinfo', $data_content);
        $this->load->view('common/bottom', $data_bottom);
    }


    /**
     * 技能分析
     */
    public function userskill()
    {
        //登录判断
        if(!$this->is_login){
            header('Location: ' . $this->login_page);
            die;
        }

        $data_header['css'] = array('userskill');
        $data_header['display_log'] = 'none';
        $data_header['display_center'] = 'block';
        $data_header['display'] = 'none';
        $data_left['display'] = 'none';
        $data_left['target'] = 'userskill';
        $data_content['success'] = false;
        $data_bottom['js'] = array('flot/jquery.flot', 'flot/jquery.flot.pie', 'module/left', 'module/userskill');

        if($this->is_com){
            $data_header['display'] = 'block';
            $data_left['display'] = 'block';
        }

        //获取用户技能
        $data['user_id'] = $this->uid;
        $this->load->model('User_Model', 'model');
        $this->model->get_userskill($data, $data_content);

        $this->load->view('common/header', $data_header);
        $this->load->view('common/left', $data_left);
        $this->load->view('user/userskill', $data_content);
        $this->load->view('common/bottom', $data_bottom);
    }
}
