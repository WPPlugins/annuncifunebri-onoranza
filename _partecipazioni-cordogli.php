<div class="annfu_partecipazioni">
  <?php if(count($annuncio['partecipazioni']) > 0): ?>
    <div class="annfu_partecipazioni_wrapper">
      <?php $partecipazioni = array(); ?>
      <?php foreach($annuncio['partecipazioni'] as $partecipazione): ?>
        <?php $partecipazioni[] = $partecipazione['utente']; ?>
      <?php endforeach; ?>
      <?php echo implode(', ', $partecipazioni); ?>
      <?php echo ' partecipa'.(count($annuncio['partecipazioni']) == 1 ? '' : 'no').' al lutto'; ?>
    </div>
  <?php endif; ?>
</div>

<?php $count = count($annuncio['cordogli']); ?>
<div class="annfu_cordogli">
  <?php if($count > 0): ?>
    <?php foreach($annuncio['cordogli'] as $cordoglio): ?>
      <div class="annfu_cordoglio clearfix">
        <div class="annfu_cordoglio_intestazione clearfix">
          <div class="col-xs-12 col-sm-8 col-md-8">
            <strong><?php echo $cordoglio['utente'] ?></strong>
          </div>
          <div class="annfu_data_cordoglio text-right col-xs-12 col-sm-4 col-md-4">
            <?php echo strftime('%e %B %Y', strtotime($cordoglio['data'])) ?>
          </div>
        </div>
        <div class="annfu_cordoglio_testo col-xs-12 col-sm-12 col-md-12"><?php echo $cordoglio['testo'] ?></div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
