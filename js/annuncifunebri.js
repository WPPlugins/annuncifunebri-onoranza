//invio del cordoglio
jQuery('#annfu_form_cordoglio form').on('submit',function() {
  var error = "";
  
  var token = jQuery('#annfu_token').val();
  var hash = jQuery('#annfu_hash').val();
  var nome = jQuery('#annfu_nome').val();
  var mail = jQuery('#annfu_mail').val();
  var testo = jQuery('#annfu_testo').val();
  var recapito = jQuery('#annfu_recapito').val();
  var visibile = jQuery('#annfu_visibile').attr('checked') ? 0 : 1;

  Cookies.set('cordoglio.nome', nome, { expires: 365 });
  Cookies.set('cordoglio.mail', mail, { expires: 365 });
  Cookies.set('cordoglio.testo', testo, { expires: 365 });
  Cookies.set('cordoglio.recapito', recapito, { expires: 365 });
  Cookies.set('cordoglio.visibile', visibile , { expires: 365 });

  if(nome == "") error += "Il nome &egrave; obbligatorio<br/>";
  // if(jQuery('#annfu_cognome').val() == "") error += "Il cognome &egrave; obbligatorio<br/>";
  // if(jQuery('#annfu_mail').val() == "") error += "L'email &egrave; obbligatoria<br/>";
  var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	if(mail != "" && !re.test(mail)) error += "L'email non &egrave; corretta<br/>";

  if(error != "") jQuery('#annfu_errori').html(error);
  else
  {
    jQuery('#annfu_loading').removeClass('hidden');

    jQuery.ajax({
      url: "http://www.annuncifunebri.it/api/v2/cordogli",
      data: {
        token: token,
        hash: hash,
        nome: nome,
        cognome: '',
        mail: mail,
        testo: testo,
        recapito: recapito,
        visibile: visibile,
      },
      type: "POST",
      crossDomain: true,
      dataType: 'jsonp',
      success: function(data) {
        var success = '';
        if('utente' in data) {
          if(data.testo === null) {
            jQuery('.annfu_partecipazioni').html('<div class="annfu_partecipazioni_wrapper">'+data.partecipazioni+'</div>');
            success = 'La partecipazione &egrave; stata inserita correttamente ed &egrave; in attesa di approvazione';
          } else {
            if('utente' in data)
            {
              var visibile = data.visibile == 0 ? '<em>; sar&agrave; visibile solo dalla famiglia</em>' : '';
              var cordoglio = '<div class="annfu_cordoglio clearfix">'+
                '<div class="annfu_cordoglio_intestazione clearfix">'+
                  '<div class="col-xs-12 col-sm-8 col-md-8">'+
                    '<strong>'+data.utente.nominativo+'</strong> '+
                    '<em>in attesa di approvazione</em>'+visibile+
                  '</div>'+
                  '<div class="annfu_data_cordoglio text-right col-xs-12 col-sm-4 col-md-4">'+
                    '<i class="fa fa-clock-o"></i> '+data.data+
                  '</div>'+
                '</div>'+
                '<div class="annfu_cordoglio_testo col-xs-12 col-sm-12 col-md-12">'+data.testo+'</div>'+
                '<div id="annfu_sms_'+data.utente.id+'">'+
                  '<br/><span class="annfu_help">Inserisci il tuo numero di cellulare per ricevere gratuitamente una conferma all\'avvenuta approvazione del cordoglio</span>'+
                  '<form id="annfu_sms_utente_'+data.utente.id+'" class="annfu_sms_utente form-inline">'+
                    '<input type="hidden" name="sf_method" value="PUT" />'+
                    '<div class="form-group">'+
                      '<input type="hidden" name="utente_id" value="'+data.utente.id+'"/>'+
                      '<input type="text" name="sms" class="annfu_sms_numero form-control" placeholder="numero di cellulare" />'+
                      '<input type="submit" value="ok" class="btn btn-default">'+
                      '<div class="annfu_sms_error annfu_error"></div>'+
                    '</div>'+
                  '</form>'+
                '</div>'+
              '</div>';

              jQuery('.annfu_cordogli').prepend(cordoglio);
              success = 'Il cordoglio &egrave; stato inserito correttamente ed &egrave; in attesa di approvazione';
              Cookies.remove('cordoglio.testo');
              Cookies.remove('cordoglio.visibile');
            }
          }
          jQuery('#annfu_successo').html(success);
        } else {
          jQuery('#annfu_errori').html('Non &egrave; stato possibile inserire il cordoglio. Riprovare tra qualche minuto.');
          Cookies.set('cordoglio.error', 'Non &egrave; stato possibile inserire il cordoglio. Riprovare tra qualche minuto.');
          location.reload(true);
        }
      },
      error: function(data) {
        jQuery('#annfu_errori').html('Non &egrave; stato possibile inserire il cordoglio. Riprovare tra qualche minuto');
        Cookies.set('cordoglio.error', 'Non &egrave; stato possibile inserire il cordoglio. Riprovare tra qualche minuto');
        location.reload(true);
      },
    });

    jQuery('#annfu_errori, #annfu_successo').html('');
    jQuery('#annfu_testo').val('');
    jQuery('#annfu_loading').addClass('hidden');
  }
  return false;
});

//salvataggio numero di telefono dell'utente dopo l'inserimento del cordoglio
jQuery(".annfu_cordogli").on("submit", "form", function(e){
  e.preventDefault();
  id = jQuery(this).parent().attr('id');
  if(jQuery('#'+id+' .annfu_sms_numero').val() != '') {
    jQuery.ajax({
      url: "http://www.annuncifunebri.it/api/v2/utenti",
      data: jQuery('#'+id+' form').serialize(),
      type: "PUT",
      crossDomain: true,
      dataType: 'json',
      success: function(data) {
        if(!('error' in data)) {
          jQuery('#'+id).addClass('annfu_success').html(data.text);
        } else {
          jQuery('#'+id+' .annfu_sms_error').html(data.text);
        }
      },
      error: function(data) {
        console.log('Error: ', data);
        jQuery('#'+id+' .annfu_sms_error').html(data.text);
      },
    });
  } else {
    jQuery('#'+id+'.annfu_sms_error').html('Devi inserire un numero valido');
  }
});

var extra = { 'page' : window.location.href, 'os' : "Unknown OS" };
if(navigator.appVersion.indexOf("Win") != -1) extra['os'] = "Windows";
if(navigator.appVersion.indexOf("Mac") != -1) extra['os'] = "MacOS";
if(navigator.appVersion.indexOf("X11") != -1) extra['os'] = "Unix";
if(navigator.appVersion.indexOf("Linux") != -1) extra['os'] = "Linux";

var nVer = navigator.appVersion;
var nAgt = navigator.userAgent;
extra['browser'] = navigator.appName;
extra['browser_version'] = ''+parseFloat(navigator.appVersion);
extra['user_agent'] = nAgt;
var nameOffset,verOffset,ix;

if ((verOffset=nAgt.indexOf("Opera"))!=-1) { // In Opera, the true version is after "Opera" or after "Version"
  extra['browser'] = "Opera";
  extra['browser_version'] = nAgt.substring(verOffset+6);
  if ((verOffset=nAgt.indexOf("Version"))!=-1) fullVersion = nAgt.substring(verOffset+8);
}
else if((verOffset=nAgt.indexOf("MSIE"))!=-1) { // In MSIE, the true version is after "MSIE" in userAgent
  extra['browser'] = "Microsoft Internet Explorer";
  extra['browser_version'] = nAgt.substring(verOffset+5);
}
else if((verOffset=nAgt.indexOf("Chrome"))!=-1) { // In Chrome, the true version is after "Chrome"
  extra['browser'] = "Chrome";
  extra['browser_version'] = nAgt.substring(verOffset+7);
}
else if((verOffset=nAgt.indexOf("Safari"))!=-1) { // In Safari, the true version is after "Safari" or after "Version"
  extra['browser'] = "Safari";
  extra['browser_version'] = nAgt.substring(verOffset+7);
  if((verOffset=nAgt.indexOf("Version"))!=-1) extra['browser_version'] = nAgt.substring(verOffset+8);
}
else if((verOffset=nAgt.indexOf("Firefox"))!=-1) { // In Firefox, the true version is after "Firefox"
  extra['browser'] = "Firefox";
  extra['browser_version'] = nAgt.substring(verOffset+8);
}
else if((nameOffset=nAgt.lastIndexOf(' ')+1) < (verOffset=nAgt.lastIndexOf('/')) ) // In most other browsers, "name/version" is at the end of userAgent
{
  extra['browser'] = nAgt.substring(nameOffset,verOffset);
  extra['browser_version'] = nAgt.substring(verOffset+1);
  if(browserName.toLowerCase()==browserName.toUpperCase()) browserName = navigator.appName;
}
// trim the fullVersion string at semicolon/space if present
if ((ix=extra['browser_version'].indexOf(";"))!=-1) extra['browser_version'] = extra['browser_version'].substring(0,ix);
if ((ix=extra['browser_version'].indexOf(" "))!=-1) extra['browser_version'] = extra['browser_version'].substring(0,ix);

jQuery('#annfu_form_cordoglio .controllato').on('keyup', function () {
  var text = jQuery(this).val();
  if(text.length >= 3) {
    jQuery.ajax({
        url: "http://www.annuncifunebri.it/api/v2/cordogliIniziati",
        data: {
            token: jQuery('#annfu_token').val(),
            hash: jQuery('#annfu_hash').val(),
            nome: jQuery('#annfu_nome').val(),
            mail: jQuery('#annfu_mail').val(),
            testo: jQuery('#annfu_testo').val(),
            recapito: jQuery('#annfu_recapito').val(),
            visibile: jQuery('#annfu_visibile').attr('checked') ? 0 : 1,
            extra: JSON.stringify(extra)
        },
        type: "POST",
        crossDomain: true,
        dataType: 'jsonp',
        success: function (data) {},
        error: function (data) {}
      });
  }
});

jQuery('#annfu_dati_personali').on('click', function () {
    if (jQuery(this).is(':checked')) {
        jQuery('#annfu_invio').removeProp('disabled');
    } else {
        jQuery('#annfu_invio').prop('disabled', 'disabled');
    }
});

//flip del box ricerca
jQuery(".annfu_annunci_filter").flip({ trigger: 'manual' });
jQuery("#annfu_filter_front").on("click", function(){
  jQuery(".annfu_annunci_filter .back form").removeClass("hidden");
  jQuery(".annfu_annunci_filter").removeClass('annfu_annunci_filter_front').addClass('annfu_annunci_filter_back');
  jQuery(".annfu_annunci_filter").flip(true);
});
jQuery("#annfu_filter_back").on("click", function(){
  jQuery(".annfu_annunci_filter").removeClass('annfu_annunci_filter_back').addClass('annfu_annunci_filter_front');
  jQuery(".annfu_annunci_filter").flip(false);
});

var provinciaOptions = jQuery(".annfu_provincia").html();
jQuery(".annfu_regione").on("change", function () {
    selected = jQuery(".annfu_regione option:selected").val();
    jQuery(".annfu_provincia").html(provinciaOptions);
    jQuery(".annfu_provincia option:not(.r_" + selected + ")").remove();
    jQuery(".annfu_provincia").prepend('<option value="" selected="selected" class="seleziona">Seleziona provincia</option>');

    jQuery("select").trigger('change.select2');
});

selected = jQuery(".annfu_regione option:selected").val();
jQuery(".annfu_provincia option").show();
jQuery(".annfu_provincia option:not(.r_"+selected+")").hide();

jQuery("select").select2();

//datepicker
jQuery(".datepicker").datepicker({'dateFormat': 'dd/mm/yy'});

//modal
jQuery('.annfu_copia_testo').on('click', function(){
	jQuery('#annfu_testo').val(jQuery(this).prev().text());
	jQuery('#annfu_modal_testi').modal('hide');
});
