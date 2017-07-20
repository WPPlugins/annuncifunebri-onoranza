<?php if(!defined('ABSPATH')) exit; ?>

<?php include_once('_custom-css.php') ?>
<?php global $wp_query; ?>
<?php $vars = $wp_query->query_vars; ?>
<?php $variables = array('text', 'regione', 'provincia', 'onoranza_funebre_id', 'nome', 'cognome', 'paese', 'dal', 'al'); ?>

<?php if(isset($_GET['reset'])): ?>
  <?php unset($_SESSION['annuncifunebri_page'], $_SESSION['annuncifunebri_dal_en'], $_SESSION['annuncifunebri_al_en']); ?>
  <?php foreach($variables as $var): ?>
    <?php unset($_SESSION['annuncifunebri_'.$var]); ?>
  <?php endforeach; ?>
<?php endif; ?>

<?php if($_GET['avanzata'] == 1): ?>
  <?php unset($_SESSION['annuncifunebri_text'], $_SESSION['annuncifunebri_regione'], $_SESSION['annuncifunebri_provincia']) ?>
<?php elseif($_GET['avanzata'] == 0 ): ?>
  <?php foreach($variables as $var): ?>
    <?php if($var != 'text') unset($_SESSION['annuncifunebri_'.$var]); ?>
  <?php endforeach; ?>
<?php else: ?>
  <?php foreach($variables as $var): ?>
    <?php if($var != 'regione' && $var != 'provincia') unset($_SESSION['annuncifunebri_'.$var]); ?>
  <?php endforeach; ?>
<?php endif; ?>

<?php $limit = get_option('annfu_max_per_page', ANNFU_MAX_PER_PAGE); ?>
<?php $page = isset($vars['pg']) && is_numeric($vars['pg']) ? $vars['pg'] : 1; ?>
<?php $_SESSION['annuncifunebri_page'] = $page; ?>

<?php $query = '&onoranza_funebre_id='.get_option('annfu_onoranza_funebre_id'); ?>
<?php foreach($variables as $var): ?>
  <?php if(isset($vars[$var])): ?>
    <?php if($var == "dal" || $var == "al"): ?>
      <?php list($gg,$mm,$aa) = explode("/", $vars[$var]); ?>
      <?php $_SESSION['annuncifunebri_'.$var.'_en'] = $aa."-".$mm."-".$gg; ?>
    <?php endif; ?>
    <?php $_SESSION['annuncifunebri_'.$var] = $vars[$var]; ?>
  <?php endif; ?>
  <?php if(isset($vars[$var]) || isset($_SESSION['annuncifunebri_'.$var])): ?>
    <?php if($var == "dal" || $var == "al"): ?>
      <?php $query .= '&'.$var.'='.filter_var($_SESSION['annuncifunebri_'.$var.'_en'], FILTER_SANITIZE_STRING); ?>
    <?php else: ?>
      <?php $query .= '&'.$var.'='.urlencode(filter_var($_SESSION['annuncifunebri_'.$var], FILTER_SANITIZE_STRING)); ?>
    <?php endif; ?>
  <?php endif; ?>
<?php endforeach; ?>

<?php include_once('_ricerca.php') ?>

<div id="annfu_annunci" class="row">
  <?php $response = wp_remote_get(ANNFU_SITE.'/api/v2/annunci?limit='.$limit.'&page='.$page.$query); ?>
  <?php $r = json_decode(wp_remote_retrieve_body($response), true); ?>

  <?php if(count($r['data']) == 0): ?>
    <div class="annfu_annunci_no_results">Nessun risultato trovato. Prova a modificare i parametri della ricerca.</div>
  <?php else: ?>
    <?php foreach($r['data'] as $annuncio): ?>
    <?php $link = get_site_url().'/'.ANNFU_PAGE_ANNUNCIO.'/'.$annuncio['comune']['slug'].'/'.$annuncio['slug']; ?>
      <div class="annfu_annunci_container col-xs-6 col-sm-4 col-md-3">
        <div class="annfu_annunci_wrapper">
          <?php if($annuncio['tipoAnnuncio'] == 'anniversario'): ?>
          <div class="annfu_ribbon_anniversario_wrapper">
            <div class="annfu_ribbon_anniversario">anniversario</div>
          </div>
          <?php endif; ?>
          <?php if($annuncio['tipoAnnuncio'] == 'ringraziamento'): ?>
          <div class="annfu_ribbon_ringraziamento_wrapper">
            <div class="annfu_ribbon_ringraziamento">ringraziamento</div>
          </div>
          <?php endif; ?>
          <div>
            <?php $info = pathinfo($annuncio['fotoGrande']) ?>
            <a class="annfu_annunci_foto" href="<?php echo $link ?>">
              <?php if(in_array(strtolower($info['extension']), array("png", "jpg", "jpeg", "gif"))): ?>
                <img src="<?php echo $annuncio['fotoGrande'] ?>" alt="<?php echo $annuncio['nominativo'] ?>">
              <?php else: ?>
                <img src="<?php echo ANNFU_PLUGIN_URL ?>/img/anonimo.jpg" alt="<?php echo $annuncio['nominativo'] ?>">
              <?php endif; ?>
            </a>
          </div>
          <div>
            <h2 class="annfu_annunci_nominativo">
							<a href="<?php echo $link ?>">
								<?php echo $annuncio['titolo'] ?>
								<?php echo $annuncio['nominativo'] ?>
								<?php echo $annuncio['secondaRiga'] != '' ? '<br/>'.$annuncio['secondaRiga'] : '' ?>
								<?php echo $annuncio['terzaRiga'] != '' ? '<br/>'.$annuncio['terzaRiga'] : '' ?>
							</a>
						</h2>
            <?php if($annuncio['eta'] > 0): ?>
              <div class="annfu_annunci_anni">di <?php echo $annuncio['eta'] ?> anni</div>
            <?php else: ?>
              <div class="annfu_annunci_anni">&nbsp;</div>
            <?php endif; ?>
            <div class="annfu_annunci_paese">
              <?php echo $annuncio['paese'] ?>
            </div>
          </div>
          <div class="annfu_add_cordoglio text-center"><a href="<?php echo $link ?>">lascia un messaggio di cordoglio</a></div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
  <div class="clearfix"></div>
  <?php if(get_option('annfu_poweredby') == 1): ?>
    <div class="text-right annfu_poweredby"><a href="<?php echo ANNFU_SITE ?>">powered by annuncifunebri.it</a></div>
  <?php endif; ?>
</div>

<?php if(count($r['data']) > 0): ?>
<div class="annfu_pagination_container">
  <?php $pagine = ceil($r['metaData']['results'] / $limit); ?>
  <?php $link = get_site_url().'/'.ANNFU_PAGE_ANNUNCI.'/'; ?>
  <?php $link .= $_SESSION['annuncifunebri_regione'] != '' ? $_SESSION['annuncifunebri_regione'].'/' : '' ?>
  <?php $link .= $_SESSION['annuncifunebri_provincia'] != '' ? $_SESSION['annuncifunebri_provincia'].'/' : '' ?>
  <div class="annfu_pagination">
    <?php for($p = 1;$p <= $pagine;$p++): ?>
      <?php if($p == 1): ?>
        <a href="<?php echo $link ?>1">Prima</a>
      <?php endif; ?>
      <?php if(abs($p - $page) < get_option('annfu_pages')): ?>
        <?php if($p == $page): ?>
          <span class="current"><?php echo $p ?></span>
        <?php else: ?>
          <a href="<?php echo $link.$p ?>" class="inactive"><?php echo $p ?></a>
        <?php endif; ?>
      <?php endif; ?>
      <?php if($p == $pagine): ?>
        <a href="<?php echo $link.$pagine ?>">Ultima</a>
      <?php endif; ?>
    <?php endfor; ?>
  </div>

  <div class="annfu_nb_results"><?php echo $r['metaData']['results'] ?> risultat<?php echo $r['metaData']['results'] == 1 ? 'o' : 'i' ?></div>
</div>
<?php endif; ?>