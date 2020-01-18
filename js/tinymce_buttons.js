(function() {
    tinymce.PluginManager.add( 'highlight', function( editor, url ) {
        // Add Button to Visual Editor Toolbar
        editor.addButton('highlight', {
            title: 'highlight',
            cmd: 'highlight',
            icon: 'backcolor',
        });

        editor.addCommand('highlight', function() {
            var markOpen = '<mark>',
                markClose = '</mark>',
                highlight = markOpen + editor.selection.getContent() + markClose;

            editor.focus();
            if (-1 < editor.getContent().indexOf(highlight)) {

                editor.setContent(
                    editor.getContent().replace(highlight, editor.selection.getContent())
                );

            } else {

                editor.selection.setContent(
                    markOpen + editor.selection.getContent() + markClose
                );

            }
        });

    });
})();