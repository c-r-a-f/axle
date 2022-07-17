<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php $page_type = is_singular() ? 'article' : 'website'; ?>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# <?= $page_type ?>: http://ogp.me/ns/<?= $page_type ?>#">
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php axle_ogp_meta(); ?>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<main>
