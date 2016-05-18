<div class="subject common">
    <ul class="subject-item clear">
        <?php
            if(!$success){
                echo '<li class="subject-company">', $error_info, '</li>';
            }else{
                foreach ($company as $v) {
                    echo '<li class="subject-company"><img src="/live-test/scripts/images/company.jpeg"><a href="http://localhost/live-test/subjectview/subject/', $v['user_id'], '">', $v['company'], '</a></li>';
                }
            }
        ?>
    </ul>
    <div class="pagination">
        <ul>
            <li class="pagination-first" id="pagination_first"><a href="###">首页</a></li>
            <li class="pagination-prev" id="pagination_prev"><a href="###">上一页</a></li>
            <li class="pagination-active"><a href="###">1</a></li>
            <li><a href="###">2</a></li>
            <li><a href="###">3</a></li>
            <li class="pagination-next"><a href="###">下一页</a></li>
            <li class="pagination-last"><a href="###">尾页</a></li>
        </ul>
    </div>
</div>