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

        <nav id="menu" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
          <?php wp_nav_menu([
            "theme_location" => "main-menu",
            "link_before" => '<span itemprop="name">',
            "link_after" => "</span>",
          ]); ?>
        </nav>
        <div id="site-title" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
          <a href="<?php echo esc_url(home_url("/")); ?>">
            <?php if (get_theme_mod('site_header_logo')) { ?><img
                src="<?php echo get_theme_mod('site_header_logo'); ?>" />
            <?php } else { ?>
              <h1>Tim Crist Talent</h1>
            <?php } ?>
          </a>
        </div>
        <div id="search">
          <?php get_search_form(); ?>
        </div>
      </div>
    </header>



    <div id="container">
      <main id="content" role="main">