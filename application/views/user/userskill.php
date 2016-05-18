	<div class="userskill">
	    <?php
	        if(!$success){
	            echo '<div class="userskill-chart">', $error_info, '</div>';
	        }else{
	            echo '<div class="userskill-chart" id="skill"></div>';
	            foreach ($user_skill as $v) {
	                echo '<div style="display:none;" data-id="', $v['skill_id'], '" data-score="', $v['score'], '" class="userskill-data"></div>';
	            }
	        }
	    ?>
	</div>
</div>