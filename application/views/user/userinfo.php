    <div class="userinfo">
        <?php
            if($success){
                foreach ($userinfo as $v) {
                    echo '<div class="userinfo-item"><label>', $v[0] , ':</label><span>', $v[1] , '</span></div>';
                }
            }else{
                echo '<div class="userinfo-item"><span>', $error_info , '</span></div>';
            }
        ?>
    </div>
</div>