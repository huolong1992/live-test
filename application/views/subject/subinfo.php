<div class="subinfo common">
    <div class="subinfo-time"><span id="subinfo_time_h"><?php echo $hour; ?></span>:<span id="subinfo_time_m"><?php echo $minute; ?></span>:<span id="subinfo_time_s"><?php echo $second; ?></span></div>
    <?php
        if(!$success){
            echo '<div class="subinfo-content">', $error_info, '</div>';
        }else{
            foreach ($subinfo as $k => $v) {
                //第一题显示, 其他隐藏
                if($k == 0){
                    echo '<div class="subinfo-content subinfo-active" id="', $k+1, '" data-id="', $v['subinfo_id'], '">';
                }else{
                    echo '<div class="subinfo-content" style="display:none;" id="', $k+1, '" data-id="', $v['subinfo_id'], '">';
                }
                //题目
                $type = $v['type']==1 ? '单选题' : '多选题';
                echo '<div class="subinfo-question">', $k+1, ', ', $v['name'], ' [', $type, '] [', $v['score'], '分]</div>';
                //选项
                $input_type = $v['type']==1 ? 'radio' : 'checkbox';
                foreach ($v['options'] as $index => $option) {
                    echo '<div class="subinfo-options"><input type="', $input_type, '" name="subinfo_option', $k+1, '" data-id="', $index+1, '"><span>&nbsp;', $option, '</span></div>';
                }
                echo '</div>';
            }
        }
    ?>
    <div class="subinfo-submit">
        <input type="button" value="下一题" class="btn subinfo-next" id="subinfo_next">
        <input type="button" value="交卷" class="btn subinfo-post" id="subinfo_post">
    </div>
</div>