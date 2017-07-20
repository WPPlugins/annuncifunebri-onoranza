var textarea = jQuery('#annfu_textarea');
var editor = ace.edit("annfu_editor");
editor.setTheme("ace/theme/twilight");
editor.getSession().setMode("ace/mode/css");

editor.getSession().on('change', function(){
  textarea.val(editor.getSession().getValue());
});

editor.getSession().setValue(textarea.val());

jQuery('.colorpicker-component').colorpicker();
jQuery('#annfu_options_reset').on('click', function(){
  return confirm('Sei sicuro di voler resettare ai valori di default');
});