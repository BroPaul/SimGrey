<?php $blogOption = get_option('SimGrey_options');?>
<!DOCTYPE html>
<html dir="ltr" lang="zh-CN">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php if(get_bloginfo('description') && is_home() && $paged<2):?>
<meta name="description" content="<?php bloginfo('description')?>" />
<?php endif;?>
<title><?php wp_title('|', true, 'right');?><?php bloginfo('name');?><?php if($paged>1)echo " - 第 " . $paged . " 页";?></title>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/style<?php simgrey_css()?>.css" type="text/css" media="screen" />
<!--[if lt IE 8]><link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/ie.css" type="text/css" media="screen" /><![endif]-->
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url')?>" />
<link rel="alternate" type="application/rss+xml" title="订阅 <?php bloginfo('name');?>" href="<?php echo $blogOption['feed']?>" />
<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/javascript.js"></script>
<?php wp_head()?>
</head>
<body <?php body_class()?> itemscope itemtype="http://schema.org/Blog">
<!--[if IE 6]><div id="ie6">您还在使用 Internet Explorer 6 吗？！建议您选用更快、更安全的浏览器，如 Chrome、Firefox，或升级到最新版本的 Internet Explorer ！</div><![endif]-->
<div id="header" role="banner" class="site-header">
	<div id="blog">
		<a href="<?php echo $blogOption['feed']?>" id="feed" title="订阅 <?php bloginfo('name')?>">RSS</a>
		<h1 class="site-title" itemprop="name"><a href="<?php bloginfo('url')?>/" title="<?php bloginfo('name')?>" itemprop="url"><?php bloginfo('name')?></a></h1>
		<p class="site-description" itemprop="description"><?php bloginfo('description')?></p>
		<form method="get" id="search" action="<?php bloginfo('url')?>/">
			<input type="text" name="s" title="搜索..."/>
		</form>
	</div>
	<div id="nav" class="site-navigation">
		<ul role="navigation" class="nav-menu">
			<li class="page_item <?php if(is_home()){echo 'current_page_item';}?>"><a href="<?php bloginfo('url')?>">首页</a></li>
			<?php wp_list_pages('depth=0&title_li=')?>
		</ul>
	</div>
	<div class="clearfix"></div>
</div>
<!--/header-->
<div id="container">
	<div id="main" role="main" class="site-content">