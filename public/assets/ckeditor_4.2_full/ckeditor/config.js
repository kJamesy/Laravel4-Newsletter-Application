/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.toolbar = 
	[
	    ['Templates', '-', 'Preview'],
	    ['Cut', 'Copy', 'Paste', '-', 'PasteText', 'PasteFromWord'],
	    ['Undo', 'Redo'],
	    ['Print'],
	    ['Find', 'Replace'],
	    ['SelectAll', '-', 'Scayt'],
	    ['Maximize'],
	    ['Source'],
	    '/',
	    ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Superscript', 'Subscript'], 
	    ['Link', 'Unlink', 'Anchor'], 
	    ['NumberedList', 'BulletedList'],
	    ['Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
	    '/',
	    ['Format', 'Font', 'FontSize'],
	    ['TextColor', 'BGColor'],
	    ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'],
	];	

	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	config.allowedContent = true;		
};
