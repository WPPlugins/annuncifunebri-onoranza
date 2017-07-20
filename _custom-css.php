<?php if(!defined('ABSPATH')) exit; ?>

<style type="text/css">
  <?php $afOptions = annfu_get_options(); ?>
  <?php $afOptionsValues = annfu_get_options_values(); ?>
  <?php foreach($afOptions as $k => $v): ?>
    <?php echo $v['css'].'{'.$v['property'].':'.$afOptionsValues[$k].' !important;}'; ?>
  <?php endforeach; ?>
  <?php echo get_option('annfu_css') ?>
</style>
