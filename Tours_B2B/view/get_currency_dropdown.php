<?php
include '../../model/model.php';
$currency_id = $_POST['currency_id'];
$_SESSION['session_currency_id'] = $currency_id;
?>           

<div class="c-select2DD st-clear">
<select title='Select Currency' id='currency' name='currency' onchange='get_selected_currency()'>
    <?php $sq_curr = mysql_fetch_assoc(mysql_query("select id,currency_code from currency_name_master where id='$currency_id'")); ?>
    <option value='<?= $sq_curr['id'] ?>'><?= $sq_curr['currency_code'] ?></option>
    <?php $sq_currency = mysql_query("select * from currency_name_master where id!='$currency_id' order by currency_code");
    while($row_currency = mysql_fetch_assoc($sq_currency)){
    ?>
    <option value='<?= $row_currency['id'] ?>'><?= $row_currency['currency_code'] ?></option>
    <?php } ?>
</select>
</div>

<script>
$(function(){
    if ($('.c-select2DD.st-clear').length){
        $('.c-select2DD.st-clear select').select2();
    }
})
</script>