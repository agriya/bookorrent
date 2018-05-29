<div class="accordion-group space">
    <form name="input" action="http://www.retailmenot.com/submit.php" method="post" class ="normal add-item js-promote-reltailmenot">
        <input type ="text" name="domain" value="<?php echo Router::url('/', true);?>">
        <input type ="text" name="f_description" value="<?php echo $this->Html->cText($reward['ItemReward']['reward'], false);?>">
    </form>
</div>