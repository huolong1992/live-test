<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户模型
 * @author 王永强
 * @date 2016/05/14 09:23
 */
class User_Model  extends MY_Model {

    //加密算法
    private $algo = 'md5';
    //加密盐
    private $salt = 'sjfiwjeiewj34532878dnsi3987582nfj49832';
    
    /**
     * 添加用户
     * @param $data array
     * @param &$ret array 返回前端的数据
     */
    public function add_user($data, &$ret)
    {
        $mail = $data['mail'];
        $phone = $data['phone'];
        $pass = hash_hmac($this->algo, $data['pass'], $this->salt);//密码加密

        //邮箱是否已注册
        if($this->mail_exists($mail)){
            $ret['error_info'] = '该邮箱已被注册';
            return false;
        }

        if(isset($data['is_com'])){
            //公司
        	 $company = $data['company'];
        	 $icp = $data['icp'];
        	 $sql = "insert into user(mail,phone,pass,company,icp,is_com) values('$mail', '$phone', '$pass', '$company', '$icp', 1)";
        }else{
            //个人
        	 $sql = "insert into user(mail,phone,pass) values('$mail', '$phone', '$pass')";
        }

        //插入数据库, 开启事务
        $this->db->trans_begin();

        //插入user表
        if($this->db->query($sql)){
            $user_id = $this->db->insert_id();

            //插入user_skill表
            $sql = "insert into user_skill(user_id, skill_id) values($user_id, 1), ($user_id, 2), ($user_id, 3), ($user_id, 4), ($user_id, 5)";
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
        $ret['uid'] = $user_id;
        return true;
    }


    /**
     * 登录判断
     * @param $data array
     * @param &$ret array 返回前端的数据
     */
    public function check_user($data, &$ret)
    {
        $mail = $data['mail'];
        $pass = hash_hmac($this->algo, $data['pass'], $this->salt);//密码加密

        //查询user表
        $sql = "select user_id, is_com from user where mail='$mail' and pass='$pass'";
        $user = $this->db->query($sql);
        if($user->num_rows() <= 0){
            $ret['error_info'] = '邮箱不存在或密码错误';
            return false;
        }

        //构造返回数据
        $user = $user->row_array();
        $ret['success'] = true;
        $ret['uid'] = $user['user_id'];
        $ret['is_com'] = $user['is_com'] ? true : false;
        return true;
    }


    /**
     * 邮箱是否已被注册
     * @param $mail string 邮箱
     * @return true: 是, false: 否
     */
    protected function mail_exists($mail)
    {
        $sql = "select user_id from user where mail='$mail'";
        $user = $this->db->query($sql);
        if($user->num_rows() > 0){
            return true;
        }

        return false;
    }


    /**
     * 获取用户信息
     * @param $data array
     * @param &$ret array 返回的信息数组
     */
    public function get_userinfo($data, &$ret)
    {
        if($data['is_com']){
            //公司
        	 $sql = 'select mail, phone, company, icp from user where user_id=' . $data['uid'];
        }else{
            //个人
        	 $sql = 'select mail, phone from user where user_id=' . $data['uid'];
        }

        //查找user表
        $user = $this->db->query($sql);
        if($user->num_rows() <= 0){
            $ret['error_info'] = '服务器忙, 请稍后再试!';
            return false;
        }

        $user = $user->row_array();
        $ret['success'] = true;
        if($data['is_com']){
            $ret['userinfo'][] = array('公司名称', $user['company']);
            $ret['userinfo'][] = array('备案', $user['icp']);
        }
        $ret['userinfo'][] = array('邮箱', $user['mail']);
        $ret['userinfo'][] = array('联系方式', $user['phone']);
        return true;
    }


    /**
     * 获取用户技能
     * @param $data array
     * @param &$ret array 返回前端的数据
     */
    public function get_userskill($data, &$ret)
    {
        $sql = 'select skill_id, score from user_skill where user_id=' . $data['user_id'];
        $user_skill = $this->db->query($sql);
        if($user_skill->num_rows() <= 0){
            $ret['error_info'] = '您还没有参加过笔试, 暂时没有技能图谱哦 !';
            return false;
        }

        $ret['success'] = true;
        $ret['user_skill'] = $user_skill->result_array();
        return true;
    }
}
