<!-- css -->
<?php echo $this->Html->css('jquery.fancybox.css?v=2.1.5', array('inline' => false)); ?>

<!-- js -->
<?php echo $this->Html->script('jquery.fancybox.pack.js?v=2.1.5', array('inline' => false)); ?>

<!-- js -->
<script type="text/javascript">
<?php echo $this->Html->scriptStart(array('inline' => false)); ?>
$(function() {
	$(".fancybox").fancybox({
	    type: 'iframe',
		iframe: {
			preload: false
		}
	});
});
<?php echo $this->Html->scriptEnd(); ?>
</script>

<!-- breadcrumb -->
<?php $this->Html->addCrumb('ホーム', '/'); ?>
<?php $this->Html->addCrumb('データベース', '/db/'); ?>
<?php $this->Html->addCrumb('PMDA', '/db/pmda/'); ?>
<?php $this->Html->addCrumb('医療用医薬品', '/db/pmda/rx/'); ?>
<?php $this->Html->addCrumb('オンライン検索'); ?>

<!-- content -->
<h2>検索条件</h2>
<?php echo $this->Js->writeBuffer(array('inline' => 'true')); ?>
<?php
echo $this->Form->create('Pmda', array('type'=>'post'));
echo $this->Form->input('effect', array('type' => 'select', 'label' => '薬効分類', 'options' => $this->requestAction('/pmda/effect')));
echo $this->Form->input('mode', array('type' => 'select', 'label' => '検索対象', 'options' => array(0 => '製品名', 1 => '添付文書内')));
echo $this->Form->text('data');
echo $this->Js->submit('検索', array(
    'before'  => $this->Js->get('#before')->effect('fadeIn'),
    'error' => $this->Js->get('#error')->effect('fadeIn'),
	'success' => $this->Js->get('#success')->effect('fadeIn'),
	'complete' => $this->Js->get('#before')->effect('fadeOut'),
    'update' => '#update',
    'url' => '/pmda/search'
));
echo $this->Form->end();
?>
<span id="before" style='display:none;'>
	searching..<img src='/img/loading.gif' style='height:20px;width:20px;'>
	<div id="error" style='display:none;'>errored!!</div>
	<div id="success" style='display:none;'>succeeded!!</div>
</span>
<div id='update'></div>
