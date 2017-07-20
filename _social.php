<?php if(!defined('ABSPATH')) exit; ?>

<?php $url = get_site_url().'/'.ANNFU_PAGE_ANNUNCIO.'/'.$annuncio['comune']['slug'].'/'.$annuncio['slug'] ?>

<div class="annfu_social annfu_text_center">

  <a href="whatsapp://send?text=<?php echo str_replace("+", "%20",urlencode("Ecco il link per lasciare un messaggio di cordoglio ai congiunti di ".$annuncio['nominativo']."; tutti i pensieri vengono anche stampati e consegnati alla famiglia. (servizio gratuito)\n".$url)) ?>" class="annfu_whatsapp"title="Condividi su WhatsApp"><i class="fa fontawesome-icon fa-whatsapp circle-yes"></i></a>

  <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url.'&t='.$annuncio['nominativo'] ?>" target="_blank" class="annfu_facebook" title="condividi su Facebook"><i class="fa fontawesome-icon fa-facebook circle-yes"></i></a>

  <a href="http://twitter.com/intent/tweet?text=<?php echo urlencode("Annuncio funebre ".$annuncio['nominativo'].": ").$url.urlencode(" ".$annuncio['hashTag']) ?>" target="_blank" class="annfu_twitter" title="Condividi su Twitter"><i class="fa fontawesome-icon fa-twitter circle-yes"></i></a>

  <a href="https://plus.google.com/share?url=<?php echo $url ?>" target="_blank" class="annfu_googleplus"title="Condividi su Google+"><i class="fa fontawesome-icon fa-google-plus circle-yes"></i></a>

  <a href="mailto:?subject=Annuncio funebre <?php echo $annuncio['nominativo'] ?>&body=<?php echo $url ?>" class="annfu_mail" title="Invia email"><i class="fa fontawesome-icon fa-envelope-o circle-yes"></i></a>

<?php if($annuncio['filePubblicazione'] != ''): ?>
  <a href="<?php echo ANNFU_SITE_STATIC.$annuncio['filePubblicazione'] ?>" target="_blank" class="annfu_pubblicazione" title="Stampa annuncio"><i class="fa fontawesome-icon fa-print circle-yes"></i></a>
<?php endif; ?>

<?php if($annuncio['fileEpigrafe'] != ''): ?>
  <a href="<?php echo ANNFU_SITE_STATIC.$annuncio['fileEpigrafe'] ?>" target="_blank" class="annfu_epigrafe" title="Stampa epigrafe" ><i class="fa fontawesome-icon fa-newspaper-o circle-yes"></i></a>
<?php endif; ?>

  <div class="clearfix"></div>
</div>
