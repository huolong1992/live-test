    <div class="published">
        <ul>
            <?php
                if($success){
                    foreach ($subject as $v) {
                        if($v['tag'] == 1){
                            echo '<li class="published-new"><h1>', $v['title'] , '</h1><a href="', $v['href'] , '">笔试未结束</a></li>';
                        }else{
                            echo '<li class="published-result"><h1>', $v['title'] , '</h1><a href="', $v['href'] , '">查看结果</a></li>';
                        }
                    }
                }else{
                    echo '<div>', $error_info , '</div>';
                }
            ?>
        </ul>
    </div>
</div>