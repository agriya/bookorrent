<?php
$css_files = array(
	CSS . 'dev1bootstrap.less',
	CSS . 'responsive.less',
	CSS . 'bootstrap-datetimepicker.min.css',
	CSS . 'flag.css',
	CSS . 'jquery.uploader.css',
	CSS . 'jquery-ui-1.10.3.custom.css',
	CSS . 'slickmap.css',
	CSS . 'chart.css',
	CSS . 'bootstro.css',
	CSS . 'bootstrap-wysihtml5-0.0.2.css'
);
$css_files = Set::merge($css_files, Configure::read('site.admin.css_files'));
?>