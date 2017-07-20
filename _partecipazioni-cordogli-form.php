<div id="annfu_form_cordoglio">
  <div id="annfu_errori" class="annfu_error"><?php echo $_COOKIE['cordoglio_error'] ?></div>
  <form action="." method="post">
    <input type="hidden" name="token" value="<?php echo $metaData['token'] ?>" id="annfu_token" />
    <input type="hidden" name="hash" value="<?php echo $annuncio['hash'] ?>" id="annfu_hash" />
    <div class="form-group">
      <input type="text" name="nome" id="annfu_nome" class="form-control controllato" placeholder="Nome e Cognome / Ragione Sociale" value="<?php echo $_COOKIE['cordoglio_nome'] ?>">
    </div>
    <div class="form-group">
      <input type="text" name="mail" id="annfu_mail" class="form-control controllato" placeholder="Email (facoltativo)" value="<?php echo $_COOKIE['cordoglio_mail'] ?>">
    </div>
    <div class="form-group">
      <textarea name="testo" id="annfu_testo" class="form-control controllato" placeholder="Testo"><?php echo $_COOKIE['cordoglio_testo'] ?></textarea>
    </div>
    <div class="form-group">
      <input type="checkbox" name="visibile" value="1" id="annfu_visibile" <?php echo isset($_COOKIE['cordoglio_visibile']) && $_COOKIE['cordoglio_visibile'] == 0 ? 'checked' : '' ?>/> Desidero che il cordoglio sia visibile solo alla famiglia
    </div>
    <br/>
    <div class="form-group">
      Facoltativo: Un recapito per permettere alla famiglia di inviare un eventuale ringraziamento. (Questo dato non sar&agrave; reso pubblico, ma verr&agrave; comunicato solo alla famiglia)<br/>
      <input type="text" name="recapito" id="annfu_recapito" class="form-control controllato" placeholder="Es.: via Verdi 90, Milano (facoltativo)" value="<?php echo $_COOKIE['cordoglio_recapito'] ?>">
    </div>
    <div class="form-group">
        <input type="checkbox" id="annfu_dati_personali"/>
        <a class="annfu_pointer" data-toggle="modal" data-target="#annfu_modal_privacy">Acconsento al trattamento dei miei dati personali.</a>
    </div>
    <div class="form-group">
      <input type="submit" name="invio" value="invia" id="annfu_invio" class="btn btn-default" disabled/>
    </div>
    <div class="clearfix"></div>
    <div id="annfu_successo" class="annfu_success"></div>
  </form>
</div>

<div id="annfu_modal_privacy" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Privacy</h4>
      </div>
      <div class="modal-body">
          AnnunciFunebri.it informa che i dati inseriti dal mittente nel presente form, verranno trattati, come da art. 13 del Codice in materia di protezione dei dati personali, da Cas-Per srl - titolare del trattamento, in forma elettronica con la sola finalità di inoltro al suo destinatario.
      </div>
    </div>
  </div>
</div>
