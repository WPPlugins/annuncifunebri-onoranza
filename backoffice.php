<?php if(!defined('ABSPATH')) exit; ?>

<h1>Annunci Funebri / Pannello di amministrazione</h1>

<?php $onoranzaFunebreId = !get_option('annfu_onoranza_funebre_id') ? '' : get_option('annfu_onoranza_funebre_id'); ?>
<?php $paginaAnnunci = !get_option('annfu_page_annunci') ? ANNFU_PAGE_ANNUNCI : get_option('annfu_page_annunci'); ?>
<?php $paginaAnnuncio = !get_option('annfu_page_annuncio') ? ANNFU_PAGE_ANNUNCIO : get_option('annfu_page_annuncio'); ?>
<?php $maxPerPage = !get_option('annfu_max_per_page') ? 12 : get_option('annfu_max_per_page'); ?>
<?php $pagine = !get_option('annfu_pages') ? 5 : get_option('annfu_pages'); ?>
<?php $showBreadcrumbs = !get_option('annfu_breadcrumbs') ? 0 : get_option('annfu_breadcrumbs'); ?>
<?php $showPoweredBy = get_option('annfu_poweredby') == '' ? '' : 1; ?>
<?php $css = !get_option('annfu_css') ? '' : get_option('annfu_css'); ?>

<?php $afOptions = annfu_get_options(); ?>
<?php $afOptionsValues = annfu_get_options_values(); ?>

<div class="annfu_form_wrap">

  <form method="post" action="options.php">

    <div> <!-- tabs container -->

      <!-- nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#annfu_home" role="tab" data-toggle="tab">Utilizzo</a></li>
        <li role="presentation"><a href="#annfu_colors" role="tab" data-toggle="tab">Colori</a></li>
        <li role="presentation"><a href="#annfu_css_custom" role="tab" data-toggle="tab">CSS personalizzato</a></li>
      </ul>

      <div class="tab-content"> <!-- tab panes -->

        <div role="tabpanel" class="tab-pane active" id="annfu_home">
          <h2>Utilizzo</h2>
          <div class="clearfix">
            codici da inserire in una pagina o articolo per visualizzare rispettivamente l'elenco degli annunci e il singolo annuncio<br/><br/>
            <pre class="annfu_code">[ANNFU_ANNUNCI]</pre>
            <pre class="annfu_code">[ANNFU_ANNUNCIO]</pre><br/><br/>
            in caso di problemi, inviare un'email a <a href="mailto:info@annuncifunebri.it">info@annuncifunebri.it</a>
          </div>

          <h2>Configurazione</h2>
          <?php settings_fields('af-settings'); ?>
          <?php do_settings_sections('af-settings'); ?>

          <div class="annfu_form_row">
            <input type="text" size="12" name="annfu_onoranza_funebre_id" value="<?php echo $onoranzaFunebreId ?>" class="annfu_input" placeholder="<?php echo __('#ID onoranza/e','af') ?>" />
            <span class="annfu_helper">#ID onoranza funebre; in caso di più onoranze, separare i valori con la virgola</span>
          </div>
          <div class="annfu_form_row">
            <input type="text" size="12" name="annfu_page_annunci" value="<?php echo $paginaAnnunci ?>" class="annfu_input" placeholder="<?php echo __('nome (slug) della pagina','af') ?>" />
            <span class="annfu_helper">Nome (slug) della pagina degli annunci</span>
          </div>
          <div class="annfu_form_row">
            <input type="text" size="12" name="annfu_page_annuncio" value="<?php echo $paginaAnnuncio ?>" class="annfu_input" placeholder="<?php echo __('nome (slug) della pagina','af') ?>" />
            <span class="annfu_helper">Nome (slug) della pagina del singolo annuncio</span>
          </div>
          <div class="annfu_form_row">
            <input type="text" size="3" name="annfu_max_per_page" value="<?php echo $maxPerPage ?>" class="annfu_input" placeholder="<?php echo __('n.','af') ?>" />
            <span class="annfu_helper">Numero di annunci per pagina</span>
          </div>
          <div class="annfu_form_row">
            <input type="text" size="3" name="annfu_pages" value="<?php echo $pagine ?>" class="annfu_input" placeholder="<?php echo __('n.','af') ?>" />
            <span class="annfu_helper">Numero pagine da visualizzare nella paginazione prima e dopo la pagina corrente</span>
          </div>
          <div class="annfu_form_row">
            <input type="checkbox" name="annfu_breadcrumbs" value="1" <?php echo $showBreadcrumbs == 1 ? 'checked="checked"' : '' ?>/>
            <span class="annfu_helper">Visualizza breadcrumbs nella pagina dell'annuncio</span>
          </div>
          <div class="annfu_form_row">
            <input type="checkbox" name="annfu_poweredby" value="1" <?php echo $showPoweredBy == 1 ? 'checked="checked"' : '' ?>/>
            <span class="annfu_helper">Visualizza il testo "powered by annuncifunebri.it" in fondo alle schermate annunci e annuncio</span>
          </div>

          <br/>
          <input type="submit" value="<?php echo __('Salva','af')?>" class="button button-primary" id="submit" name="submit">

        </div>

        <div role="tabpanel" class="tab-pane" id="annfu_colors">
          <?php include_once('_backoffice-colors.php'); ?>
        </div>

        <div role="tabpanel" class="tab-pane" id="annfu_css_custom">
          <div class="annfu_form_row">
            <p>CSS personalizzato</p>
            <textarea rows="10" cols="80" name="annfu_css" id="annfu_textarea" class="annfu_textarea" placeholder="<?php echo __('CSS personalizzato','af')?>" /><?php echo $css ?></textarea>
            <div id="annfu_editor"></div>
          </div>
          <input type="submit" value="<?php echo __('Salva','af')?>" class="button button-primary" id="submit" name="submit">
					<br/><br/>
          <div class="annfu_form_row">
            <?php include_once('_struttura.php') ?>
          </div>

        </div>

      </div> <!-- end tab panes -->

    </div> <!-- end tabs container -->

  </form>
</div>
