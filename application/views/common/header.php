<!DOCTYPE html>
<html>
<head>
    <title>在线笔试网</title>
    <link rel="stylesheet" type="text/css" href="/live-test/scripts/css/common.css">
    <?php
        foreach ($css as $v) {
            echo '<link rel="stylesheet" type="text/css" href="/live-test/scripts/css/' . $v . '.css">';
        }
    ?>
</head>
<body>
<div class="header">
    <div class="header-main">
        <a class="logo" title="在线笔试网" href="/live-test/subjectview/company"></a>
        <a class="header-nav" href="/live-test/subjectview/company">首页</a>
        <div class="user-log" style="display: <?php echo $display_log; ?>">
            <a id="login" href="/live-test/userview/log">登录</a>/<a id="register" href="/live-test/userview/log/2">注册</a>
        </div>
        <div class="user-center" style="display: <?php echo $display_center; ?>">
            <img src="/live-test/scripts/images/user-center.png">
            <ul class="user-center-sub">
                <li><a href="/live-test/userview/userinfo">个人信息</a></li>
                <li><a href="/live-test/userview/userskill">技能分析</a></li>
                <li style="display: <?php echo $display; ?>"><a href="/live-test/subjectview/published">已发布试题</a></li>
                <li style="display: <?php echo $display; ?>"><a href="/live-test/subjectview/pub">发布新试题</a></li>
                <li><a href="/live-test/user/logout">退&nbsp;&nbsp;出</a></li>
            </ul>
        </div>
    </div>
</div>