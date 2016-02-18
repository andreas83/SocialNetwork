Hello <?php echo $name; ?><br/>, 

someone is trying to reset your Password at <?php echo Config::get("address"); ?><br/>

Please follow this link if you really like to reset your Password:<br/>

<?php
echo $confirm_url; 
?>

Best Regards

<br/>
