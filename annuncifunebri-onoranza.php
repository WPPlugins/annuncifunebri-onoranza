<?php
/*
Plugin Name: AnnunciFunebri 
Version: 2.0.14
Description: Con questo plugin è possibile visualizzare sul proprio sito gli annunci dell'impresa funebre pubblicati sul sito annuncifunebri.it
Author: Paolo Cantoni per AnnunciFunebri
Author URI: http://www.annuncifunebri.it
*/

if(!defined('ABSPATH')) exit;

setlocale(LC_ALL, "it_IT.utf8");

define('ANNFU_VERSION', '2.0.14');
define('ANNFU_SITE', 'http://www.annuncifunebri.it');
define('ANNFU_SITE_STATIC', 'https://static.annuncifunebri.it');
define('ANNFU_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ANNFU_IMG', ANNFU_PLUGIN_URL.'img/');
define('ANNFU_MAX_PER_PAGE', 24);
define('ANNFU_FOTO_W_ANNUNCIO', 180);
define('ANNFU_FOTO_H_ANNUNCIO', 216);
define('ANNFU_PAGE_ANNUNCIO', !get_option('annfu_page_annuncio') ? 'annuncio' : get_option('annfu_page_annuncio'));
define('ANNFU_PAGE_ANNUNCI', !get_option('annfu_page_annunci') ? 'ricerca' : get_option('annfu_page_annunci'));

require_once(plugin_dir_path(__FILE__).'/functions.inc.php');

add_action('init', 'annfu_start_session', 1);
add_action('init', 'annfu_rewrite_rules', 10, 0);
add_action('init', 'annfu_rewrite_tag', 10, 0);
add_action('init', 'annfu_add_endpoints');

add_action('init', 'annfu_create_sitemap', 10, 0); //creazione sitemap
add_filter('cron_schedules','annfu_cron_schedules'); //creazione cron
if(!wp_get_schedule('annfu_create_sitemap'))
{
  add_action('init', 'annfu_cron', 10); //inizializzazione del cron
}

add_filter('pre_get_document_title', 'annfu_annuncio_title', 20);
add_filter('wp_title', 'annfu_annuncio_title', 20, 2); //cambio del titolo della pagina
remove_action('wp_head', 'rel_canonical');
add_action('wp_head', 'annfu_head', 1); //caricamento CSS, JS e meta per Facebook
add_filter('query_vars', 'annfu_add_query_vars'); // aggiungo le variabili utili alla rewrite per gli annunci

add_action('template_redirect','annfu_remove_seo');

add_action('init', 'annfu_annunci_register_shortcode');
add_action('init', 'annfu_annuncio_register_shortcode');

//admin
add_action('admin_menu', 'annfu_menu'); // inserisco il menu
add_action('admin_init', 'annfu_register_settings'); // salvataggio dei dati della form lato admin
add_action('admin_enqueue_scripts', 'annfu_admin_scripts'); // caricamento CSS e JS lato admin
add_action('admin_action_annfu_reset_options', 'annfu_reset_options'); // reset delle opzioni

