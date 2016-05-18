<div class="log common" id="log" target="<?php echo $target; ?>">
    <ul class="log-header clear">
        <li class="log-login log-header-active" id="log_login"><a class="log-login-a log-login-a-active" href="###">登录</a></li>
        <li class="log-register log-header-active" id="log_register"><a class="log-register-a" href="###">注册</a></li>
    </ul>
    <div class="log-content" id="log_content_login">
        <div class="log-form">
            <form>
                <div class="log-item">
                    <label for="mail_login">电子邮箱</label>
                    <div class="log-input">
                        <input type="text" id="mail_login">
                        <span class="input-tip">请输入正确的邮箱</span>
                    </div>
                </div>
                <div class="log-item">
                    <label for="pass_login">密码</label>
                    <div class="log-input">
                        <input type="password" id="pass_login">
                        <span class="input-tip">请输入密码</span>
                    </div>
                </div>
                <div class="log-item">
                    <label for="code_login">验证码</label>
                    <div class="log-input">
                        <input type="text" id="code_login">
                        <img id="code_img_login" src="/live-test/user/get_code">
                        <span class="input-tip">请输入验证码</span>
                    </div>
                </div>
                <div class="log-item">
                    <div class="log-input">
                        <input type="button" id="submit_login" value="立即登录" class="submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="log-content" id="log_content_register">
        <div class="log-user-type">
            <input type="radio" name="user_type" value="person">个人
            <input type="radio" name="user_type" value="company">公司
        </div>
        <div class="log-form">
            <form>
                <div class="log-item company">
                    <label for="company">公司名称</label>
                    <div class="log-input">
                        <input type="text" id="company">
                        <span class="input-tip">请输入公司名称</span>
                    </div>
                </div>
                <div class="log-item company">
                    <label for="icp">备案</label>
                    <div class="log-input">
                        <input type="text" id="icp">
                        <span class="input-tip">请输入公司备案</span>
                    </div>
                </div>
                <div class="log-item">
                    <label for="mail_register">电子邮箱</label>
                    <div class="log-input">
                        <input type="text" id="mail_register">
                        <span class="input-tip">请输入正确的邮箱</span>
                    </div>
                </div>
                <div class="log-item">
                    <label for="phone">手机或电话号码</label>
                    <div class="log-input">
                        <input type="text" id="phone">
                        <span class="input-tip">请输入正确的联系方式</span>
                    </div>
                </div>
                <div class="log-item">
                    <label for="pass_register">密码</label>
                    <div class="log-input">
                        <input type="password" id="pass_register">
                        <span class="input-tip">请输入密码</span>
                    </div>
                </div>
                <div class="log-item">
                    <label for="pass_confirm">确认密码</label>
                    <div class="log-input">
                        <input type="password" id="pass_confirm">
                        <span class="input-tip">两次密码不正确</span>
                    </div>
                </div>
                <div class="log-item">
                    <label for="code_register">验证码</label>
                    <div class="log-input">
                        <input type="text" id="code_register">
                        <img id="code_img_register" src="/live-test/user/get_code">
                        <span class="input-tip">请输入验证码</span>
                    </div>
                </div>
                <div class="log-item">
  	            <div class="log-input">
                    <input type="button" id="submit_register" value="立即注册" class="submit">
  	            </div>
                </div>
            </form>
        </div>
    </div>
</div>