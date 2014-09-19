<?php
defined('_JEXEC') or die;
?>
<?php foreach ($list as $item) : ?>
<div class="row-fluid">
<div class="span6">
<strong class="row-title">
<?php echo $item->name; ?>
</strong>
</div>
<div class="span3">
<?php echo $item->username; ?>
</div>
<div class="span3">
<?php echo $item->email; ?>
</div>
</div>
<?php endforeach; ?>


