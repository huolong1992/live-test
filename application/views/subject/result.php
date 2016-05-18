    <div class="result">
        <?php
            if(!$success){
                echo '<div class="result-item clear">', $error_info , '</div>';
            }else{
        ?>
        <div class="result-item clear">
            <label>90分以上:</label>
            <div class="result-right">
                <?php
                    foreach ($ninety_up as $v) {
                        echo '<p>', $v,  '</p>';
                    }
                ?>
            </div>
        </div>
        <div class="result-item clear">
            <label>70~89分:</label>
            <div class="result-right">
                <?php
                    foreach ($seventy_up as $v) {
                        echo '<p>', $v,  '</p>';
                    }
                ?>
            </div>
        </div>
        <div class="result-item clear">
            <label>70分以下:</label>
            <div class="result-right">
                <?php
                    foreach ($seventy_down as $v) {
                        echo '<p>', $v,  '</p>';
                    }
                ?>
            </div>
        </div>
        <?php
            }
        ?>
    </div>
</div>