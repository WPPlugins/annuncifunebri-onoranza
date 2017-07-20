<?php if(!defined('ABSPATH')) exit; ?>

<?php include_once('_custom-css.php') ?>

<?php global $wp_query, $annuncio; ?>
<?php $vars = $wp_query->query_vars; ?>

<?php if(isset($vars['comune']) && isset($vars['slug'])): ?>

	<?php if(is_null($annuncio)): ?>
		<?php $response = wp_remote_get(ANNFU_SITE."/api/v2/annunci/".$vars['slug'].'?of='.get_option('annfu_onoranza_funebre_id')); ?>
		<?php $annuncio = json_decode(wp_remote_retrieve_body($response), true); ?>
	<?php endif; ?>

  <?php if(array_key_exists('error', $annuncio)): ?>
    <p>Annuncio non trovato</p>
  <?php else: ?>

  <?php $metaData = $annuncio['metaData'] ?>
  <?php $annuncio = $annuncio['data'] ?>

  <?php $response = wp_remote_get(ANNFU_SITE."/api/v2/testiDefault"); ?>
  <?php $testi = json_decode(wp_remote_retrieve_body($response), true); ?>

  <div class="annfu_wrapper">

    <?php if(get_option('annfu_breadcrumbs') == 1): ?>
    <div id="annfu_regione_provincia_comune">
      <a href="<?php echo get_site_url() ?>/<?php echo ANNFU_PAGE_ANNUNCI ?>">Italia</a> / 
      <a href="<?php echo get_site_url() ?>/<?php echo ANNFU_PAGE_ANNUNCI.'/'.$annuncio['regione']['slug'] ?>"><?php echo $annuncio['regione']['regione'] ?></a> / 
      <a href="<?php echo get_site_url() ?>/<?php echo ANNFU_PAGE_ANNUNCI.'/'.$annuncio['regione']['slug'].'/'.$annuncio['provincia']['slug'] ?>"><?php echo $annuncio['provincia']['provincia'] ?></a> / 
      <?php echo $annuncio['comune']['comune'] ?>
    </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-7">
        <div class="annfu_annuncio_wrapper">
          <div class="annfu_annuncio text-center ">
            <div class="annfu_annuncio_citazione text-right"><em><?php echo $annuncio['citazione'] ?></em></div>
            <div class="annfu_annuncio_apertura"><?php echo $annuncio['apertura'] ?></div>
            <div class="annfu_annuncio_foto">
              <a href="<?php echo $annuncio['fotoGrande'] ?>">
                <img style="width:<?php echo get_option('annfu_foto_w_annuncio', ANNFU_FOTO_W_ANNUNCIO) ?>px;height:<?php echo get_option('annfu_foto_h_annuncio', ANNFU_FOTO_H_ANNUNCIO) ?>px" src="<?php echo $annuncio['foto'] ?>" alt="<?php echo $annuncio['nominativo'] ?>">
              </a>
            </div>
            <h2>
							<?php echo $annuncio['titolo'] ?>
							<?php echo $annuncio['nominativo'] ?>
							<?php echo $annuncio['secondaRiga'] != '' ? '<br/>'.$annuncio['secondaRiga'] : '' ?>
							<?php echo $annuncio['terzaRiga'] != '' ? '<br/>'.$annuncio['terzaRiga'] : '' ?>
						</h2>
            <?php if($annuncio['eta'] > 0): ?>
              <div class="annfu_annuncio_anni">di <?php echo $annuncio['eta'] ?> anni</div>
            <?php endif; ?>
            <div class="annfu_annuncio_testo"><?php echo $annuncio['testo'] ?></div>
            <div class="annfu_annuncio_paese text-left"><?php echo $annuncio['paese'] ?>, <?php echo strftime('%e %B %Y', strtotime($annuncio['dataMorte'])) ?></div>
            <div class="annfu_annuncio_onoranza_funebre text-left"><?php echo $annuncio['chiusuraOnoranzaFunebre'] ?></div>
          </div>
        </div>
        <?php include('_social.php'); ?>
      </div>

      <div class="col-xs-12 col-sm-12 col-md-5">
        <div class="annfu_form_cordoglio_wrapper">
          <h2>Lascia il tuo messaggio di cordoglio alla famiglia</h2>

          <p>Lascia <strong>gratuitamente</strong> un messaggio di cordoglio, sar&agrave; nostra cura consegnarlo ai congiunti di <?php echo $annuncio['nominativo'] ?>.<br/>
          Tutti i pensieri verranno anche <strong>stampati e consegnati ai congiunti</strong> in ricordo.</p>

          <p>Se non sai cosa scrivere, o non trovi le parole adatte, clicca solo sul pulsante invia e verr&agrave; inviato gratuitamente un avviso alla famiglia. Altrimenti <a class="annfu_pointer" data-toggle="modal" data-target="#annfu_modal_testi">clicca qui</a> e troverai una lista di testi da cui prendere spunto.</p>

          <?php include_once('_partecipazioni-cordogli-form.php') ?>

        </div>
      </div>
    </div>

    <div class="clearfix"></div>

    <?php include_once('_partecipazioni-cordogli.php') ?>

  </div>

  <div class="clearfix"></div>

  <div id="annfu_modal_testi" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Testi</h4>
        </div>
        <div class="modal-body">
          <?php foreach($testi as $testo): ?>
            <div class="annfu_testo_default">
              <span><?php echo $testo ?></span>
              <a class="annfu_pointer annfu_copia_testo">Copia il testo</a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

<?php endif; ?>

  <?php $page = isset($_SESSION['annuncifunebri_page']) ? $_SESSION['annuncifunebri_page'] : 1; ?>
  <div><a href="<?php echo get_site_url().'/'.ANNFU_PAGE_ANNUNCI ?>/<?php echo $page ?>">Ritorna alla pagina degli annunci</a></div>
  <?php if(get_option('annfu_poweredby') == 1): ?>
    <div class="text-right annfu_poweredby"><a href="<?php echo ANNFU_SITE ?>">powered by annuncifunebri.it</a></div>
  <?php endif; ?>

<?php else : ?>
  <div id="annfu_annunci">Annuncio non trovato</div>
<?php endif; ?>
