<script type="text/javascript" src="/live-test/scripts/js/jquery.js"></script>
<script type="text/javascript" src="/live-test/scripts/js/module/common.js"></script>
<?php
    foreach ($js as $v) {
    	echo '<script type="text/javascript" src="/live-test/scripts/js/' . $v . '.js"></script>';
    }
?>
</body>
</html>