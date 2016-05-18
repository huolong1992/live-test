<div class="detail common">
    <?php
        if(!$success){
            echo '<div class="detail-body">', $error_info, '</div>';
        }else{
    ?>
    <h1><?php echo $detail['title']; ?></h1>
    <div class="detail-body">
        <div class="detail-view">
            <div class="detail-img"></div>
            <div class="detail-options">
                <p>单选题: <?php echo $detail['single']; ?>道</p>
                <p>多选题: <?php echo $detail['multiple']; ?>道</p>
                <p>考试时间: <?php echo date('m月d日 H:i', $detail['start_time']), ' ~ ', date('m月d日 H:i', $detail['end_time']); ?></p>
            </div>
        </div>
        <div class="detail-tips">
            <?php echo htmlspecialchars_decode($detail['tips']); ?>
        </div>
        <div class="detail-submit">
            <input type="button" class="btn" value="开始笔试" data-id="<?php echo $detail['subject_id']; ?>" data-start="<?php echo $detail['start_time']; ?>" id="detail_begin">
        </div>
    </div>
    <?php
        }
    ?>
</div>