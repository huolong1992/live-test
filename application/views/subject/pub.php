    <div class="pub">
        <div class="pub-1 clear" id="pub_1">
            <div class="pub-1-item">
                <label for="name">笔试名称</label>
                <div class="pub-1-input">
                    <input type="text" id="name">
                    <span class="input-tip"></span>
                </div>
            </div>
            <div class="pub-1-item">
                <label for="start_date">考试时间</label>
                <div class="pub-1-input pub-1-input-time">
                    <input type="text" id="start_date"><input type="text" id="start_time">
                    <input type="text" id="end_date"><input type="text" id="end_time">
                    <span class="input-tip"></span>
                </div>
            </div>
            <div class="pub-1-item pub-1-item-area">
                <label for="tips">注意事项</label>
                <div class="pub-1-input pub-1-input-area">
                    <textarea id="tips" placeholder="一行一个注意事项"></textarea>
                    <span class="input-tip"></span>
                </div>
            </div>
            <div class="pub-1-item pub-1-item-area">
                <label for="mail_list">考生邮箱</label>
                <div class="pub-1-input pub-1-input-area">
                    <textarea id="mail_list" placeholder="一行一个邮箱"></textarea>
                    <span class="input-tip"></span>
                </div>
            </div>
        </div>
        <div class="pub-1 clear" id="pub_2" style="display: none;">
            <div class="pub-1-item">
                <label for="type">题目类型</label>
                <div class="pub-1-input">
                    <select id="type">
                        <option value="1">单选</option>
                        <option value="2">多选</option>
                    </select>
                    <span class="input-tip"></span>
                </div>
            </div>
            <div class="pub-1-item">
                <label for="skill">考查技能</label>
                <div class="pub-1-input">
                    <select id="skill">
                        <option value="1">算法</option>
                        <option value="2">网络</option>
                        <option value="3">操作系统</option>
                        <option value="4">编程语言</option>
                        <option value="5">Linux</option>
                    </select>
                    <span class="input-tip"></span>
                </div>
            </div>
            <div class="pub-1-item">
                <label for="title">题目</label>
                <div class="pub-1-input">
                    <input type="text" id="title">
                    <span class="input-tip"></span>
                </div>
            </div>
            <div class="pub-1-item">
                <label for="score">分数</label>
                <div class="pub-1-input">
                    <input type="text" id="score">
                    <span class="input-tip"></span>
                </div>
            </div>
            <div class="pub-1-item">
                <label for="option_1">选项1</label>
                <div class="pub-1-input">
                    <input type="text" id="option_1" class="option">
                    <span class="input-tip"></span>
                </div>
            </div>
            <div class="pub-1-item">
                <label for="option_2">选项2</label>
                <div class="pub-1-input">
                    <input type="text" id="option_2" class="option">
                    <span class="input-tip"></span>
                </div>
            </div>
            <div class="pub-1-item pub-1-item-add">
                <label for="">&nbsp;</label>
                <div class="pub-1-input pub-1-input-add">
                    <a href="##" id="pub_add">+添加选项</a>
                </div>
            </div>
            <div class="pub-1-item">
                <label for="right_answer">正确选项</label>
                <div class="pub-1-input">
                    <input type="text" id="right_answer">
                    <span class="input-tip"></span>
                </div>
            </div>
        </div>
        <div class="pub-submit">
            <input type="button" value="下一步" class="btn pub-next" id="pub_next">
            <input type="button" value="下一题" class="btn pub-next" id="pub_next_subject" style="display: none;">
            <input type="button" value="提交" class="btn pub-post" id="pub_post" style="display: none;">
        </div>
    </div>
</div>