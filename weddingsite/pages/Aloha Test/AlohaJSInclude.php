<?php
/**
 * Wedding site Aloha processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 *
 */

/* Paths */
$assets_url = $modx->getOption('assets_url');
$wsJQueryPath = $modx->getOption('ws_jQuery.assets_url',null,$modx->getOption('assets_url').'components/ws_jQuery/');
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');
$wsAlohaPath = $modx->getOption('ws_aloha.assets_url',null,$modx->getOption('assets_url').'components/ws_aloha/');
$alohaCSS = $wsAlohaPath. "js/aloha/css/aloha.css";
$modx->regClientCSS($alohaCSS);
$alohaJQuery = '<script type="text/javascript" src="http://cdn.aloha-editor.org/latest/lib/vendor/jquery-1.7.2.js"></script>';
$modx->regClientStartupScript($alohaJQuery, true);
$alohaRequires = '<script type="text/javascript" src="http://cdn.aloha-editor.org/latest/lib/require.js"></script>';
$modx->regClientStartupScript($alohaRequires, true);
$alohaSetup = "<script> var Aloha = window.Aloha || ( window.Aloha = {} );
	
	Aloha.settings = {
		locale: 'en',
		plugins: {
			format: {
				config: [  'b', 'i', 'p', 'sub', 'sup', 'del', 'title', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'pre', 'removeFormat' ],
			  	editables : {
					// no formatting allowed for title
					'#alohaTitle'	: [ ]
			  	}
			},
			link: {
				editables : {
					// No links in the title.
					'#alohaTitle'	: [  ]
			  	}
			},
			list: {
				editables : {
					// No lists in the title.
					'#alohaTitle'	: [  ]
			  	}
			},
			abbr: {
				editables : {
					// No abbr in the title.
					'#alohaTitle'	: [  ]
			  	}
			},
			image: {
				'fixedAspectRatio': true,
				'maxWidth': 1024,
				'minWidth': 10,
				'maxHeight': 786,
				'minHeight': 10,
				'globalselector': '.global',
				'ui': {
					'oneTab': false
				},
				editables : {
					// No images in the title.
					'#alohaTitle'	: [  ]
			  	}
			}
		},
		sidebar: {
			disabled: true
		},
		contentHandler: {
		    allows: {
				elements: [
					'a', 'abbr', 'b', 'blockquote', 'br', 'caption', 'cite', 'code', 'col',
					'colgroup', 'dd', 'del', 'dl', 'dt', 'em', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
					'i', 'img', 'li', 'ol', 'p', 'pre', 'q', 'small', 'strike', 'strong',
					'sub', 'sup', 'table', 'tbody', 'td', 'tfoot', 'th', 'thead', 'tr', 'u',
					'ul', 'span', 'hr', 'object', 'div'
				],

				attributes: {
					'a': ['href', 'title', 'id', 'class', 'target', 'data-gentics-aloha-repository', 'data-gentics-aloha-object-id'],
					'div': [ 'id', 'class'],
					'abbr': ['title'],
					'blockquote': ['cite'],
					'br': ['class'],
					'col': ['span', 'width'],
					'colgroup': ['span', 'width'],
					'img': ['align', 'alt', 'height', 'src', 'title', 'width', 'class'],
					'ol': ['start', 'type'],
					'q': ['cite'],
					'p': ['class'],
					'table': ['summary', 'width'],
					'td': ['abbr', 'axis', 'colspan', 'rowspan', 'width'],
					'th': ['abbr', 'axis', 'colspan', 'rowspan', 'scope', 'width'],
					'ul': ['type'],
					'span': ['class','style','lang','xml:lang']
				},

				protocols: {
					'a': {'href': ['ftp', 'http', 'https', 'mailto', '__relative__']},
					'blockquote': {'cite': ['http', 'https', '__relative__']},
					'img': {'src' : ['http', 'https', '__relative__']},
					'q': {'cite': ['http', 'https', '__relative__']}
				}
			}
		}
	};
</script>";
$modx->regClientStartupScript($alohaSetup, true);

$context = $modx->context->get('key');
$alohaAJAXProcessorId = $modx->getOption('alohaAJAXProcessorId');
$alohaAJAXProcessorLink = $modx->makeURL($alohaAJAXProcessorId, $context, "", "full");
$alohaAJAXProcessorLink = '"' . $alohaAJAXProcessorLink . '"';

$resourceId = $modx->resource->get('id');

/* Setup */
$ready = '<script>
        WS = new Object;
        WS.resourceId = ' . $resourceId . ';
        WS.AlohaAJAXProcessorLink = ' . $alohaAJAXProcessorLink . '; 
        </script>
<script type="text/javascript" src="' . $wsAlohaPath . 'js/aloha/lib/aloha.js"
			data-aloha-plugins="common/ui,
					common/format,
		                        common/table,
		                        common/list,
		                        common/link,
		                        common/highlighteditables,
		                        common/block,
		                        common/undo,
		                        common/image,
		                        common/contenthandler,
		                        common/paste,
		                        common/commands,
		                        common/abbr"></script>';
$modx->regClientStartupScript($ready, true);

/* Aloha js */
$alohaJS =  $javascriptPath . "js/pages/aloha.js";
$modx->regClientStartupScript($alohaJS);


       


