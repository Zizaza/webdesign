<?php

/**
 *
 * Framework module styles
 *
 * @version             1.0.0
 * @package             GK Framework
 * @copyright			Copyright (C) 2010 - 2011 GavickPro. All rights reserved.
 * @license                
 */
 
// No direct access.
defined('_JEXEC') or die;

/**
 * gk_style
 */
 
function modChrome_gk_style($module, $params, $attribs) {	
	if (!empty ($module->content)) {		
		$modnum_class = '';
		
		/**
		 *
		 *	We will get following classes:
		 *
		 *	gkmod-1 - for 1 module
		 *	gkmod-2 - for 2 modules
		 *	gkmod-3 - for 3 modules
		 *	gkmod-4 - for 4 modules
		 *	gkmod-more - for more than 4 modules
		 *
		 *	gkmod-last-1 - for more than 4 modules and 1 module at the end
		 *	gkmod-last-2 - for more than 4 modules and 2 module at the end
		 *	gkmod-last-3 - for more than 4 modules and 3 module at the end
		 *
		 **/
		if(isset($attribs['modnum'])) {
			$num = $attribs['modnum'];
			
			if($num > 4) {
				$num = $num % 4;
				
				if($num == 0) {
					$modnum_class = ' gkmod-4';
				} else {
					$modnum_class = ' gkmod-more gkmod-last-' . $num;
				}
			} else {
				$modnum_class = ' gkmod-' . $num;
			}
		}
		
		echo '<div class="box' . $params->get('moduleclass_sfx') . $modnum_class . '"><div>';
		
		if($module->showtitle) {	
			echo '<h3 class="header">'.$module->title.'</h3>';
		}
	
		echo '<div class="content">' . $module->content . '</div>';
		echo '</div></div>';
 	}
}

// EOF