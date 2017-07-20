<?php
if(!defined('ABSPATH')) exit;

global $annuncio;

//inizializzo la sessione
function annfu_start_session() {
	if(!session_id()) session_start();
}

function annfu_remove_seo() {
  if(is_page(array(ANNFU_PAGE_ANNUNCIO, ANNFU_PAGE_ANNUNCI))) {
		if (defined('WPSEO_VERSION')) { // Yoast SEO
			global $wpseo_front;
			if(defined($wpseo_front)){
				remove_action('wp_head',array($wpseo_front,'head'),1);
			} else {
				$wp_thing = WPSEO_Frontend::get_instance();
				remove_action('wp_head',array($wp_thing,'head'),1);
			}
		}
		if (defined('AIOSEOP_VERSION')) { // All-In-One SEO
			global $aiosp;
			remove_action('wp_head',array($aiosp,'wp_head'));
		}
	}
}

function annfu_head() {
	global $wp_query, $annuncio;
	$vars = $wp_query->query_vars;

	if(is_page(array(ANNFU_PAGE_ANNUNCIO, ANNFU_PAGE_ANNUNCI))) {
		wp_enqueue_script('af-cookie', ANNFU_PLUGIN_URL.'js/js.cookie.js', array('jquery'), false, true);
		wp_enqueue_script('af-select2', ANNFU_PLUGIN_URL.'js/select2.min.js', array('jquery'), false, true);
		wp_enqueue_script('af-flip', ANNFU_PLUGIN_URL.'js/jquery.flip.min.js', array('jquery'), false, true);
		wp_enqueue_script('af-bootstrap', ANNFU_PLUGIN_URL.'js/bootstrap.min.js', array('jquery'), false, true);
		wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
		wp_enqueue_script('af', ANNFU_PLUGIN_URL.'js/annuncifunebri.js', false, false, true);
		wp_enqueue_style('af-font-awesome', ANNFU_PLUGIN_URL.'css/font-awesome.min.css',false,'1.0','all');
		wp_enqueue_style('af-jquery-ui', ANNFU_PLUGIN_URL.'css/jquery-ui.min.css',false,'1.0','all');
		wp_enqueue_style('af-bootstrap', ANNFU_PLUGIN_URL.'css/bootstrap.min.css',false,'1.0','all');
		wp_enqueue_style('af-select2', ANNFU_PLUGIN_URL.'/css/select2.min.css',false,'1.0','all');
		wp_enqueue_style('af', ANNFU_PLUGIN_URL.'css/style.css',false,'1.0','all');

		echo '<meta name="af-version" content="'.ANNFU_VERSION.'"/>';
	}

	if(isset($vars['comune']) && isset($vars['slug'])) {

		if(is_null($annuncio)) {
			$response = wp_remote_get(ANNFU_SITE."/api/v2/annunci/".$vars['slug'].'?of='.get_option('annfu_onoranza_funebre_id'));
			$annuncio = json_decode(wp_remote_retrieve_body($response), true);
			if(is_null($annuncio))
			{
				$wp_query->set_404();
				status_header(404);
			}
		}

		$metaData = $annuncio['metaData'];
		$annuncioData = $annuncio['data'];
		echo '<link rel="canonical" href="'.get_permalink().$vars['comune'].'/'.$vars['slug'].'/" />' . "\n";
		echo '<meta name="description" content="'.substr(strip_tags($annuncioData['testo']),0,120).'...\n'.'" />' . "\n";
		echo '<meta name="keywords" content="'.str_replace('"', '\'', $annuncio['nominativo']).', '.$annuncioData['onoranzaFunebre']['ragioneSociale'].', annuncio, annunci, funebri, onoranza, funebre, conforto, cordoglio, partecipazione, defunto, morto" />';
		echo '<meta property="og:site_name" content="'.get_site_url().'"/>';
		echo '<meta property="og:title" content="'.str_replace('"', '\'', $annuncioData['nominativo']).' - '.$annuncioData['onoranzaFunebre']['ragioneSociale'].'"/>';
		echo '<meta property="og:type" content="profile" />';
		echo '<meta property="og:description" content="'. substr(strip_tags($annuncioData['testo']),0,120).'... '.$annuncioData['onoranzaFunebre']['ragioneSociale'].'" />';
		echo '<meta property="og:url" content="'.get_permalink().$vars['comune'].'/'.$vars['slug'].'/"/>';
		if(!is_null($annuncioData['facebookFoto'])) {
			echo '<meta property="og:image" content="https:'.$annuncioData['facebookFoto'].'"/>';
		}
		else if(!is_null($annuncioData['foto'])) {
			echo '<meta property="og:image" content="https:'.$annuncioData['foto'].'"/>';
			echo '<meta property="og:image:width" content="160" />';
			echo '<meta property="og:image:height" content="192" />';
		}
		echo '<meta property="og:first_name" content="'.str_replace('"', '\'', $annuncioData['nome']).'"/>';
		echo '<meta property="og:last_name" content="'.str_replace('"', '\'', $annuncioData['cognome']).'"/>';
		echo '<meta property="fb:app_id" content="966242223397117"/>';
	}
}

function annfu_annuncio_title() {
	global $wp_query, $annuncio;
	$vars = $wp_query->query_vars;
	if(isset($vars['comune']) && isset($vars['slug'])) {

		if(is_null($annuncio)) {
			$response = wp_remote_get(ANNFU_SITE."/api/v2/annunci/".$vars['slug'].'?of='.get_option('annfu_onoranza_funebre_id'));
			$annuncio = json_decode(wp_remote_retrieve_body($response), true);
			if(is_null($annuncio))
			{
				$wp_query->set_404();
				status_header(404);
			}
		}

		$metaData = $annuncio['metaData'];
		$annuncioData = $annuncio['data'];

		$title = $annuncioData['nominativo'].' - '.get_bloginfo('name');
	}

	return $title;
}

function annfu_annunci_shortcode() {
	if(get_option('annfu_onoranza_funebre_id') != '') {
		ob_start();
		include_once('annunci.php');
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	} else {
		return 'Plugin Annunci Funebri non configurato';
	}
}

function annfu_annunci_register_shortcode() {
	add_shortcode('ANNFU_ANNUNCI', 'annfu_annunci_shortcode');
}

function annfu_annuncio_shortcode() {
	if(get_option('annfu_onoranza_funebre_id') != '') {
		ob_start();
		include_once('annuncio.php');
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	} else {
		return 'Plugin Annunci Funebri non configurato';
	}
}

function annfu_annuncio_register_shortcode() {
	add_shortcode('ANNFU_ANNUNCIO', 'annfu_annuncio_shortcode');
}

// aggiungo le variabili che mi servono nella riscrittura degli URL
function annfu_add_query_vars($aVars) {
	$aVars[] = "text";
	$aVars[] = "slug";
	$aVars[] = "regione";
	$aVars[] = "provincia";
	$aVars[] = "regione_id";
	$aVars[] = "provincia_id";
	$aVars[] = "comune";
	$aVars[] = "nome";
	$aVars[] = "cognome";
	$aVars[] = "paese";
	$aVars[] = "dal";
	$aVars[] = "al";
	$aVars[] = "pg";

	return $aVars;
}

// riscrivo gli URL come mi serve
function annfu_rewrite_rules() {
	add_rewrite_rule(ANNFU_PAGE_ANNUNCIO.'/([^/]+)/([^/]+)$', 'index.php?pagename='.ANNFU_PAGE_ANNUNCIO.'&comune=$matches[1]&slug=$matches[2]', 'top');
	add_rewrite_rule(ANNFU_PAGE_ANNUNCI.'/(\d+)/?$', 'index.php?pagename='.ANNFU_PAGE_ANNUNCI.'&pg=$matches[1]', 'top');
	add_rewrite_rule(ANNFU_PAGE_ANNUNCI.'/([^/]+)/?$', 'index.php?pagename='.ANNFU_PAGE_ANNUNCI.'&regione=$matches[1]', 'top');
	add_rewrite_rule(ANNFU_PAGE_ANNUNCI.'/([^/]+)/([^/]+)/?$', 'index.php?pagename='.ANNFU_PAGE_ANNUNCI.'&regione=$matches[1]&provincia=$matches[2]', 'top');
	add_rewrite_rule(ANNFU_PAGE_ANNUNCI.'/([^/]+)/([^/]+)/(\d+)/?$', 'index.php?pagename='.ANNFU_PAGE_ANNUNCI.'&regione=$matches[1]&provincia=$matches[2]&pg=$matches[3]', 'top');
	flush_rewrite_rules();
}

function annfu_add_endpoints() {
	add_rewrite_endpoint(ANNFU_PAGE_ANNUNCIO, EP_PAGES);
}

function annfu_rewrite_tag() {
	add_rewrite_tag('%pagename%', '([^&]+)');
	add_rewrite_tag('%regione%', '([^&]+)');
	add_rewrite_tag('%provincia%', '([^&]+)');
	add_rewrite_tag('%comune%', '([^&]+)');
	add_rewrite_tag('%slug%', '([^&]+)');
	add_rewrite_tag('%annuncio%', '([^&]+)');
}

// menu
function annfu_menu() {
	add_menu_page(__('Annunci Funebri', 'af'), __('Annunci Funebri', 'af'), 'manage_options', 'af-plugin', 'annfu_admin', ANNFU_IMG.'af.png');
}

// script e css
function annfu_admin_scripts($hook) {
	if('toplevel_page_af-plugin' != $hook ) {
		return;
	}
	wp_enqueue_script('af-ace', ANNFU_PLUGIN_URL.'js/ace/ace.js',false,false,true);
	wp_enqueue_script('af-bootstrap', ANNFU_PLUGIN_URL.'js/bootstrap.min.js', array('jquery'), false, true);
	wp_enqueue_script('af-cp', ANNFU_PLUGIN_URL.'js/bootstrap-colorpicker.min.js',array('jquery'),false,true);
	wp_enqueue_script('af', ANNFU_PLUGIN_URL.'js/annuncifunebriAdmin.js', false, false, true);
	wp_enqueue_style('af', ANNFU_PLUGIN_URL.'css/style.css',false,'1.0','all');
	wp_enqueue_style('af-bootstrap', ANNFU_PLUGIN_URL.'css/bootstrap.min.css',false,'1.0','all');
	wp_enqueue_style('af-cp', ANNFU_PLUGIN_URL.'css/bootstrap-colorpicker.min.css',false,'1.0','all');
}

// pagina di amministrazione
function annfu_admin() {
	require_once('backoffice.php');
}

//salvataggio del form
function annfu_register_settings() {
	register_setting('af-settings', 'annfu_onoranza_funebre_id');
	register_setting('af-settings', 'annfu_page_annunci');
	register_setting('af-settings', 'annfu_page_annuncio');
	register_setting('af-settings', 'annfu_max_per_page');
	register_setting('af-settings', 'annfu_pages');
	register_setting('af-settings', 'annfu_breadcrumbs');
	register_setting('af-settings', 'annfu_poweredby');
	register_setting('af-settings', 'annfu_css');
	$afOptionColors = annfu_get_options();
	foreach($afOptionColors as $k => $v) {
		register_setting('af-settings', $k);
	}
}

function annfu_get_options() {
  return array(
    //annunci - ricerca ///////////////////////////////////////////////////////////////////////////
    'id_bg_annfu_annunci_filter' => array(
      'css' => '#annfu_annunci_filter .front, #annfu_annunci_filter .back', 
      'property' => 'background-color', 
      'default' => '#a59ddb',
      'description' => 'Sfondo box'),
    'id_annfu_annunci_filter' => array(
      'css' => '#annfu_annunci_filter .front, #annfu_annunci_filter .back', 
      'property' => 'color', 
      'default' => '#000000',
      'description' => 'Colore box'),
    'class_bg_annfu_filter_submit' => array(
      'css' => '.annfu_filter_submit', 
      'property' => 'background-color', 
      'default' => '#ffffff',
      'description' => 'Sfondo pulsante'),
    'class_annfu_filter_submit' => array(
      'css' => '.annfu_filter_submit', 
      'property' => 'color', 
      'default' => '#a59ddb',
      'description' => 'Colore pulsante'),
    //annunci - elenco ////////////////////////////////////////////////////////////////////////////
    'id_border_annfu_annunci' => array(
      'css' => '.annfu_annunci', 
      'property' => 'border-color', 
      'default' => '#f2f2f2',
      'description' => 'Bordo contenitore annunci'),
    'id_bg_annfu_annunci' => array(
      'css' => '.annfu_annunci', 
      'property' => 'background-color', 
      'default' => '#fafafa',
      'description' => 'Sfondo contenitore annunci'),
    'class_border_annfu_annunci_wrapper' => array(
      'css' => '.annfu_annunci_wrapper', 
      'property' => 'border-color', 
      'default' => '#dcdcdc',
      'description' => 'Bordo annuncio'),
    'class_bg_annfu_annunci_wrapper' => array(
      'css' => '.annfu_annunci_wrapper', 
      'property' => 'background-color', 
      'default' => '#ffffff',
      'description' => 'Sfondo annuncio'),
    'class_annfu_annunci_wrapper' => array(
      'css' => '.annfu_annunci_wrapper', 
      'property' => 'color', 
      'default' => '#000000',
      'description' => 'Colore testo annuncio'),
    'class_annfu_annunci_nominativo' => array(
      'css' => 'h2.annfu_annunci_nominativo a', 
      'property' => 'color', 
      'default' => '#000000',
      'description' => 'Colore titolo annuncio'),
    'class_border_hover_annfu_annunci_wrapper' => array(
      'css' => '.annfu_annunci_wrapper:hover', 
      'property' => 'border-color', 
      'default' => '#4f5064',
      'description' => 'Bordo annuncio (hover)'),
    'class_bg_hover_annfu_annunci_wrapper' => array(
      'css' => '.annfu_annunci_wrapper:hover', 
      'property' => 'background-color', 
      'default' => '#4f5064',
      'description' => 'Sfondo annuncio (hover)'),
    'class_hover_annfu_annunci_wrapper' => array(
      'css' => '.annfu_annunci_wrapper:hover', 
      'property' => 'color', 
      'default' => '#ffffff',
      'description' => 'Colore testo annuncio (hover)'),
    'class_hover_annfu_annunci_nominativo' => array(
      'css' => '.annfu_annunci_wrapper:hover h2.annfu_annunci_nominativo a', 
      'property' => 'color',
      'default' => '#ffffff',
      'description' => 'Colore titolo annuncio (hover)'),
    'class_bg_hover_annfu_add_cordoglio' => array(
      'css' => '.annfu_annunci_wrapper:hover h2.annfu_add_cordoglio',
      'property' => 'background-color',
      'default' => '#ffffff',
      'description' => 'Sfondo aggiungi cordoglio'),
    'class_hover_annfu_add_cordoglio' => array(
      'css' => '.annfu_annunci_wrapper:hover h2.annfu_add_cordoglio',
      'property' => 'color',
      'default' => '#000000',
      'description' => 'Colore aggiungi cordoglio'),
      //annuncio //////////////////////////////////////////////////////////////////////////////////
    'class_border_annfu_annuncio_wrapper' => array(
      'css' => '.annfu_annuncio_wrapper',
      'property' => 'border-color',
      'default' => '#d2d2d2',
      'description' => 'Bordo annuncio'),
    'class_annfu_annuncio_wrapper' => array(
      'css' => '.annfu_annuncio_wrapper',
      'property' => 'color',
      'default' => '#000000',
      'description' => 'Colore testo annuncio'),
    'class_h2_annfu_annuncio_wrapper' => array(
      'css' => '.annfu_annuncio_wrapper h2',
      'property' => 'color',
      'default' => '#000000',
      'description' => 'Colore testo nominativo'),
      //annuncio - form cordoglio /////////////////////////////////////////////////////////////////
    'class_annfu_form_cordoglio_wrapper' => array(
      'css' => '.annfu_form_cordoglio_wrapper',
      'property' => 'color',
      'default' => '#000000',
      'description' => 'Colore form cordoglio'),
    'class_h2_annfu_form_cordoglio_wrapper' => array(
      'css' => '.annfu_form_cordoglio_wrapper h2',
      'property' => 'color',
      'default' => '#000000',
      'description' => 'Colore titolo form cordoglio'),
    'id_bg_annfu_invio' => array(
      'css' => '#annfu_invio',
      'property' => 'background-color',
      'default' => '#a59ddb',
      'description' => 'Sfondo pulsante'),
    'id_annfu_invio' => array(
      'css' => '#annfu_invio',
      'property' => 'color',
      'default' => '#ffffff',
      'description' => 'Colore pulsante'),
    'id_bg_hover_annfu_invio' => array(
      'css' => '#annfu_invio:hover',
      'property' => 'background-color',
      'default' => '#ffffff',
      'description' => 'Sfondo pulsante (hover)'),
    'id_hover_annfu_invio' => array(
      'css' => '#annfu_invio:hover',
      'property' => 'color',
      'default' => '#a59ddb',
      'description' => 'Colore pulsante (hover)'),
      //annuncio - cordogli ///////////////////////////////////////////////////////////////////////
    'class_annfu_cordoglio_intestazione' => array(
      'css' => '.annfu_cordoglio_intestazione strong',
      'property' => 'color',
      'default' => '#a59ddb',
      'description' => 'Colore nominativo cordoglio'),
    'class_annfu_data_cordoglio' => array(
      'css' => '.annfu_data_cordoglio',
      'property' => 'color',
      'default' => '#000000',
      'description' => 'Colore data cordoglio'),
    'class_border_annfu_cordoglio_intestazione' => array(
      'css' => '.annfu_cordoglio_intestazione',
      'property' => 'border-bottom-color',
      'default' => '#d2d2d2',
      'description' => 'Bordo cordoglio'),
    'class_annfu_cordoglio_testo' => array(
      'css' => '.annfu_cordoglio_testo',
      'property' => 'color',
      'default' => '#000000',
      'description' => 'Colore testo cordoglio'),
  );
}

function annfu_get_options_values() {
	$values = array();
	$afOptions = annfu_get_options();
	foreach($afOptions as $k => $v) {
		$values[$k] = !get_option($k) ? $v['default'] : get_option($k);
	}

	return $values;
}

function annfu_reset_options() {
	if(isset($_GET['_reset'])) {
		$afOptions = annfu_get_options();
		foreach($afOptions as $k => $v) {
			delete_option($k);
		}
	}
	wp_redirect(admin_url('admin.php?page=af-plugin'));
}

function annfu_create_sitemap() {
	$response = wp_remote_get(ANNFU_SITE.'/api/v2/annunci?limit=9999&onoranza_funebre_id='.get_option('annfu_onoranza_funebre_id'));
	$r = json_decode(wp_remote_retrieve_body($response), true);

	header('Content-Type: text/xml; charset=' . get_bloginfo('charset'), true);
	$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
	$sitemap .= "<url><loc>".get_site_url()."</loc><changefreq>Daily</changefreq><priority>1.0</priority></url>\n";
	$sitemap .= "<url><loc>".get_site_url().'/'.ANNFU_PAGE_ANNUNCI."</loc><changefreq>Daily</changefreq><priority>1.0</priority></url>\n";
	if(isset($r['data'])) {
		foreach($r['data'] as $annuncio) {
			$link = get_site_url().'/'.ANNFU_PAGE_ANNUNCIO.'/'.$annuncio['comune']['slug'].'/'.$annuncio['slug'];
			$sitemap .= "<url>".
				"<loc>".$link."</loc>".
				"<lastmod>".date("Y-m-d\TH:i:sP", strtotime($annuncio['updatedAt']))."</lastmod>".
				"<changefreq>Weekly</changefreq>".
				"<priority>0.5</priority>".
				"</url>\n";
		}
	}
	$sitemap .= "</urlset>";

	$fop = fopen(ABSPATH."sitemap_AF.xml", 'w');
	fwrite($fop, $sitemap);
	fclose($fop);
}

function annfu_cron_schedules($schedules) {
	if(!isset($schedules["sitemapAF"])) {
		$schedules["sitemapAF"] = array(
		'interval' => 24*60*60,
		'display' => __('Once every day'));
	}

	return $schedules;
}

function annfu_cron() {
	wp_schedule_event(time(), 'sitemapAF', 'annfu_create_sitemap');
}
