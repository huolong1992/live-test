<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户控制器
 * 执行用户相关操作
 * @author 王永强
 * @date 2016/05/09 17:13
 */
class User  extends MY_Controller {

    /**
     * 注册
     */
    public function register()
    {
        //登录判断
    	 if($this->is_login){
    	     $this->ret['status'] = 503;
    	     $this->flush();
    	 }
    	 $this->get_input();

    	 //个人, 公司
    	 $is_com = isset($this->posts['is_com']) ? true : false;

    	 //数据过滤
    	 $data_keys = array('mail', 'pass', 'code', 'phone');
    	 if($is_com){
    	     $data_keys[] = 'company';
    	     $data_keys[] = 'icp';
    	 }
    	 $this->validate($data_keys);
    	 //验证码
    	 if(strcasecmp($this->posts['code'], $_SESSION['code'])){
    	     $this->ret['error_info'] = '验证码错误或已过期';
    	     $this->flush();
    	 }
    	 //邮箱
    	 if(!$this->check_mail($this->posts['mail'])){
    	     $this->ret['error_info'] = '请填写正确的邮箱';
    	     $this->flush();
    	 }
    	 //联系方式
    	 if(!$this->check_phone($this->posts['phone'])){
   	         $this->ret['error_info'] = '请填写正确的联系方式';
   	         $this->flush();
   	     }
    	 if($is_com){
    	     //公司名称
    	 	 if(strlen($this->posts['company']) > 60){
    	 	     $this->ret['error_info'] = '公司名称最多60字符';
    	 	     $this->flush();
    	 	 }
    	 	 //备案
    	 	 if(strlen($this->posts['icp']) > 30){
    	 	     $this->ret['error_info'] = '备案最多30字符';
    	 	     $this->flush();
    	 	 }
    	 }

    	 //插入数据库
    	 unset($this->posts['code']);
    	 $this->load->model('User_Model', 'model');
        if(!$this->model->add_user($this->posts, $this->ret)){
            $this->flush();
        }

        //开启会话
        $_SESSION['uid'] = $this->ret['uid'];
        $_SESSION['mail'] = $this->posts['mail'];
        $_SESSION['is_com'] = $is_com;
        unset($_SESSION['code']);

        //返回数据
        unset($this->ret['uid']);
        $this->flush();
    }


    /**
     * 登录
     */
    public function login()
    {
        //登录判断
    	 if($this->is_login){
    	     $this->ret['status'] = 503;
    	     $this->flush();
    	 }
    	 $this->get_input();

    	 //数据过滤
    	 $this->validate(array('mail', 'pass', 'code'));
    	 //验证码
    	 if(strcasecmp($this->posts['code'], $_SESSION['code'])){
    	     $this->ret['error_info'] = '验证码错误或已过期';
    	     $this->flush();
    	 }
    	 //邮箱
    	 if(!$this->check_mail($this->posts['mail'])){
    	     $this->ret['error_info'] = '请填写正确的邮箱';
    	     $this->flush();
    	 }

    	 //查询数据库
    	 unset($this->posts['code']);
    	 $this->load->model('User_Model', 'model');
        if(!$this->model->check_user($this->posts, $this->ret)){
            $this->flush();
        }

        //开启会话
        $_SESSION['uid'] = $this->ret['uid'];
        $_SESSION['mail'] = $this->posts['mail'];
        $_SESSION['is_com'] = $this->ret['is_com'];
        unset($_SESSION['code']);

        //返回数据
        unset($this->ret['uid']);
        unset($this->ret['is_com']);
        $this->flush();
    }


    /**
     * 判断是否邮箱
     * @param $mail string 邮箱
     */
    protected function check_mail($mail)
    {
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        return true;
    }


    /**
     * 判断是否手机号码
     * @param $phone string 手机号码
     */
    protected function check_phone($phone)
    {
        if (!preg_match("#^1[34578]\d{9}$#", $phone) && !preg_match('#^[0-9\-]{12,13}$#', $phone)) {
            return false;
        }
        return true;
    }


    /**
     * 产生验证码
     */
    public function get_code()
    {
        //生成验证码
        $this->load->helper('captcha');
        $vals = array(
            'word_length' => 4,
            'img_width' => 130,
            'img_height' => 42
        );
        create_captcha($vals);
    }


    /**
     * 退出登录
     */
    public function logout()
    {
        if(isset($_SESSION['uid'])){
        	 //销毁会话, 重新生成session id
            session_destroy();
            session_regenerate_id();
            //设置session
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time()-42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
        }

        header('Location: ' . $this->login_page);
        die;
    }
}
