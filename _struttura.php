<?php if(!defined('ABSPATH')) exit; ?>

<pre id="annfu_struttura">
  <strong>Elenco annunci</strong>
  <?php echo htmlentities('
  // ANNUNCI ////////////////////////////////////////////////////////////////////////////
  <div id="annfu_annunci" class="row">
    // ANNUNCIO /////////////////////////////////////////////////////////////////////////
    <div class="annfu_annunci_container col-xs-6 col-sm-4 col-md-3">
      <div class="annfu_annunci_wrapper">
        <div>
          <a class="annfu_annunci_foto" href="#">
            <img src="#" alt="NOMINATIVO">
          </a>
        </div>
        <div>
          <h2 class="annfu_annunci_nominativo"><a href="#">NOMINATIVO</a></h2>
          <div class="annfu_annunci_anni">di XX anni</div>
          <div class="annfu_annunci_paese">PAESE</div>
        </div>
        <div class="annfu_add_cordoglio text-center"><a href="#">lascia un messaggio di cordoglio</a></div>
        </div>
      </div>
    </div>
  </div>
  // PAGINAZIONE ////////////////////////////////////////////////////////////////////////
  <div class="annfu_pagination_container">
    <div class="annfu_pagination">
      <a href="#" class="pagination-prev">Prima</a>
      <span class="current">X</span>
      <a href="#" class="inactive">Y</a>
      <a href="#" class="pagination-next">Ultima</a>
    </div>
    <div class="annfu_nb_results">XX risultati</div>
  </div>
  '); ?>

  <strong>Pagina annuncio</strong>
  <?php echo htmlentities('
  <div class="annfu_wrapper">
    <div id="annfu_regione_provincia_comune">
      <a href="#">Italia</a> / 
      <a href="#">REGIONE</a> / 
      <a href="#">PROVINCIA</a> / 
      COMUNE
    </div>

    <div class="row">
      // ANNUNCIO ///////////////////////////////////////////////////////////////////////
      <div class="col-xs-12 col-sm-12 col-md-7">
        <div class="annfu_annuncio_wrapper">
          <div class="annfu_annuncio text-center ">
            <div class="annfu_annuncio_citazione text-right"><em>CITAZIONE</em></div>
            <div class="annfu_annuncio_apertura">APERTURA</div>
            <div class="annfu_annuncio_foto">
              <a href="#">
                <img src="#" alt="NOMINATIVO">
              </a>
            </div>
            <h2>NOMINATIVO</h2>
            <div class="annfu_annuncio_anni">di XX anni</div>
            <div class="annfu_annuncio_testo">TESTO</div>
            <div class="annfu_annuncio_paese text-left">PAESE, DATA</div>
            <div class="annfu_annuncio_onoranza_funebre text-left">CHIUSURA ONORANZA</div>
          </div>
        </div>
      </div>

      // FORM INSERIMENTO CORDOGLIO /////////////////////////////////////////////////////
      <div class="col-xs-12 col-sm-12 col-md-5">
        <div class="annfu_form_cordoglio_wrapper">

          <div id="annfu_form_cordoglio">
            <div id="annfu_errori" class="annfu_error"></div>
            <form action="." method="post">
              <input type="hidden" name="token" value="" id="annfu_token" />
              <input type="hidden" name="hash" value="" id="annfu_hash" />
              <div class="form-group">
                <input type="text" name="nome" id="annfu_nome" class="form-control" placeholder="Nome">
              </div>
              <div class="form-group">
                <input type="text" name="cognome" id="annfu_cognome" class="form-control" placeholder="Cognome">
              </div>
              <div class="form-group">
                <input type="text" name="mail" id="annfu_mail" class="form-control" placeholder="Email">
              </div>
              <div class="form-group">
                <textarea name="testo" id="annfu_testo" class="form-control" placeholder="Testo"></textarea>
              </div>
              <div class="form-group">
                <input type="checkbox" name="visibile" value="1" id="annfu_visibile" /> ...
              </div>
              <br/>
              <div class="form-group">
                ...
                <br/>
                <input type="text" name="recapito" id="annfu_recapito" class="form-control" placeholder="...">
              </div>
              <div class="form-group">
                <input type="submit" name="invio" value="invia" id="annfu_invio" class="btn btn-default"/>
              </div>
              <div class="clearfix"></div>
              <div id="annfu_successo" class="annfu_success"></div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="clearfix"></div>

    // PARTECIPAZIONI ///////////////////////////////////////////////////////////////////
    <div class="annfu_partecipazioni">
      <div class="annfu_partecipazioni_wrapper">NOMINATIVI partecipano al lutto</div>
    </div>

    // CORDOGLI /////////////////////////////////////////////////////////////////////////
    <div class="annfu_cordogli">
      <div class="annfu_cordoglio clearfix">
        <div class="annfu_cordoglio_intestazione clearfix">
          <div class="col-xs-12 col-sm-8 col-md-8">
            <strong>NOMINATIVO</strong>
          </div>
          <div class="annfu_data_cordoglio text-right col-xs-12 col-sm-4 col-md-4">DATA</div>
        </div>
        <div class="annfu_cordoglio_testo col-xs-12 col-sm-12 col-md-12">TESTO</div>
      </div>
    </div>
  </div>

  <div class="clearfix"></div>

  // TESTI PRECARICATI //////////////////////////////////////////////////////////////////
  <div id="annfu_modal_testi" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Testi</h4>
        </div>
        <div class="modal-body">
          <div class="annfu_testo_default">
            <span>TESTO</span>
            <a class="annfu_pointer annfu_copia_testo">Copia il testo</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div><a href="#">Ritorna alla pagina degli annunci</a></div>
  '); ?>

</pre>
