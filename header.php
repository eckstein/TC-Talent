<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php tctalent_schema_type(); ?>>
<head>
<meta charset="<?php bloginfo("charset"); ?>" />
<meta name="viewport" content="width=device-width" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="wrapper" class="hfeed">

<header id="header" role="banner" class="shadow">
  <div id="branding">
    <div id="site-title" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
      <a href="<?php echo esc_url(home_url("/")); ?>"><img src="https://toposwopetalent.com/wp-content/uploads/2023/06/TST-gold-horizontal-1.png" /></a>
    </div>
    <nav id="menu" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
      <?php wp_nav_menu([
          "theme_location" => "main-menu",
          "link_before" => '<span itemprop="name">',
          "link_after" => "</span>",
      ]); ?>
    </nav>
    <div id="search"><?php get_search_form(); ?></div>
  </div>
  blah blah
</header>



<div id="container">
<main id="content" role="main">