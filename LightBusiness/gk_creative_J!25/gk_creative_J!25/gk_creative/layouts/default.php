<?php

/**
 *
 * Default view
 *
 * @version             1.0.0
 * @package             Gavern Framework
 * @copyright			Copyright (C) 2010 - 2011 GavickPro. All rights reserved.
 *               
 */
 
// No direct access.
defined('_JEXEC') or die;
// 
$this->layout->generateLayout();
//
$app = JFactory::getApplication();
$user = JFactory::getUser();
// getting User ID
$userID = $user->get('id');
// getting params
$option = JRequest::getCmd('option', '');
$view = JRequest::getCmd('view', '');
// defines if com_users
define('GK_COM_USERS', $option == 'com_users' && ($view == 'login' || $view == 'registration'));
// other variables
$btn_login_text = ($userID == 0) ? JText::_('TPL_GK_LANG_LOGIN') : JText::_('TPL_GK_LANG_LOGOUT');
$tpl_page_suffix = $this->page_suffix != '' ? ' class="'.$this->page_suffix.'"' : '';

?>
<!DOCTYPE html>
<html lang="<?php echo $this->APITPL->language; ?>" <?php echo $tpl_page_suffix; ?>>
<head>
	<?php if($this->browser->get('browser') == 'ie8' || $this->browser->get('browser') == 'ie7' || $this->browser->get('browser') == 'ie6') : ?>
	<meta http-equiv="X-UA-Compatible" content="IE=9">
	<?php endif; ?>
    <?php if($this->API->get("chrome_frame_support", '0') == '1' && ($this->browser->get('browser') == 'ie8' || $this->browser->get('browser') == 'ie7' || $this->browser->get('browser') == 'ie6')) : ?>
    <meta http-equiv="X-UA-Compatible" content="chrome=1"/>
    <?php endif; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
    <jdoc:include type="head" />
    <?php $this->layout->loadBlock('head'); ?>
	<?php $this->layout->loadBlock('cookielaw'); ?>
</head>
<body<?php echo $tpl_page_suffix; ?><?php if($this->browser->get("tablet") == true) echo ' data-tablet="true"'; ?><?php if($this->browser->get("mobile") == true) echo ' data-mobile="true"'; ?><?php $this->layout->generateLayoutWidths(); ?>>	
	<?php if ($this->browser->get('browser') == 'ie7' || $this->browser->get('browser') == 'ie6') : ?>
	<!--[if lte IE 7]>
	<div id="ieToolbar"><div><?php echo JText::_('TPL_GK_LANG_IE_TOOLBAR'); ?></div></div>
	<![endif]-->
	<?php endif; ?>	
	
	<?php if(count($app->getMessageQueue())) : ?>
	<jdoc:include type="message" />
	<?php endif; ?>
		 
    <section id="gkPageTop">                    	
	    <div id="gkPageNav">
		    <div>
			    <?php $this->layout->loadBlock('logo'); ?>
			    
			    <div id="gkMainMenu">
			    	<?php
			    		$this->mainmenu->loadMenu($this->API->get('menu_name','mainmenu')); 
			    	    $this->mainmenu->genMenu($this->API->get('startlevel', 0), $this->API->get('endlevel',-1));
			    	?>   
			    	
			    	<div id="gkMobileMenu">
			    		<?php echo JText::_('TPL_GK_LANG_MOBILE_MENU'); ?>
			    		<select onChange="window.location.href=this.value;">
				    	<?php 
				    	    $this->mobilemenu->loadMenu($this->API->get('menu_name','mainmenu')); 
				    	    $this->mobilemenu->genMenu($this->API->get('startlevel', 0), $this->API->get('endlevel',-1));
				    	?>
			    		</select>
			    	</div>
			    </div>
		    </div>
    	</div>

	    <?php if($this->API->modules('header_top')) : ?>
	    <section id="gkHeaderTop">
	    	<jdoc:include type="modules" name="header_top" style="<?php echo $this->module_styles['header_top']; ?>" />
	    </section>
	    <?php endif; ?>
    </section>
    
    <?php if($this->API->modules('top1')) : ?>
    <section id="gkTop1">
    	<div>
    		<jdoc:include type="modules" name="top1" style="<?php echo $this->module_styles['top1']; ?>"  modnum="<?php echo $this->API->modules('top1'); ?>" />
    	</div>
    </section>
    <?php endif; ?>
    
    <section id="gkPage">
		<div id="gkPageWrap">
			<?php if($this->API->modules('top2')) : ?>
			<section id="gkTop2">
				<jdoc:include type="modules" name="top2" style="<?php echo $this->module_styles['top2']; ?>" modnum="<?php echo $this->API->modules('top2'); ?>" />
			</section>
			<?php endif; ?>
		
			<div id="gkPageContent">
				<?php if($this->API->modules('sidebar') && $this->API->get('sidebar_position', 'right') == 'left') : ?>
				<aside id="gkSidebar">
					<jdoc:include type="modules" name="sidebar" style="<?php echo $this->module_styles['sidebar']; ?>" />
				</aside>
				<?php endif; ?>
			 
		    	<section id="gkContent">
		    		<div>					
						<?php if($this->API->modules('mainbody_top')) : ?>
						<section id="gkMainbodyTop">
							<jdoc:include type="modules" name="mainbody_top" style="<?php echo $this->module_styles['mainbody_top']; ?>" />
						</section>
						<?php endif; ?>	
						
						<?php if($this->API->modules('breadcrumb') || $this->getToolsOverride()) : ?>
						<section id="gkBreadcrumb">
							<?php if($this->API->modules('breadcrumb')) : ?>
							<jdoc:include type="modules" name="breadcrumb" style="<?php echo $this->module_styles['breadcrumb']; ?>" />
							<?php endif; ?>
							
							<?php if($this->getToolsOverride()) : ?>
								<?php $this->layout->loadBlock('tools/tools'); ?>
							<?php endif; ?>
						</section>
						<?php endif; ?>
						
						<section id="gkMainbody">
							<?php if(($this->layout->isFrontpage() && !$this->API->modules('mainbody')) || !$this->layout->isFrontpage()) : ?>
								<jdoc:include type="component" />
							<?php else : ?>
								<jdoc:include type="modules" name="mainbody" style="<?php echo $this->module_styles['mainbody']; ?>" />
							<?php endif; ?>
						</section>
						
						<?php if($this->API->modules('mainbody_bottom')) : ?>
						<section id="gkMainbodyBottom">
							<jdoc:include type="modules" name="mainbody_bottom" style="<?php echo $this->module_styles['mainbody_bottom']; ?>" />
						</section>
						<?php endif; ?>
					</div>
		    	</section>
		    	
		    	<?php if($this->API->modules('sidebar') && $this->API->get('sidebar_position', 'right') == 'right') : ?>
		    	<aside id="gkSidebar">
		    		<jdoc:include type="modules" name="sidebar" style="<?php echo $this->module_styles['sidebar']; ?>" />
		    	</aside>
		    	<?php endif; ?>
			</div>
		    
			<?php if($this->API->modules('bottom1')) : ?>
			<section id="gkBottom1">
				<jdoc:include type="modules" name="bottom1" style="<?php echo $this->module_styles['bottom1']; ?>" modnum="<?php echo $this->API->modules('bottom1'); ?>" />
			</section>
			<?php endif; ?>
	    </div>
    </section>
    
    <?php if($this->API->modules('header_bottom')) : ?>
    <section id="gkHeaderBottom">
    	<jdoc:include type="modules" name="header_bottom" style="<?php echo $this->module_styles['header_bottom']; ?>" />
    </section>
    <?php endif; ?>
    
    <?php if($this->API->modules('bottom2')) : ?>
    <section id="gkBottom2">
    	<div>
    		<jdoc:include type="modules" name="bottom2" style="<?php echo $this->module_styles['bottom2']; ?>" modnum="<?php echo $this->API->modules('bottom2'); ?>" />
    	</div>
    </section>
    <?php endif; ?>
    
    <?php if($this->API->modules('bottom3')) : ?>
    <section id="gkBottom3">
    	<div>
    		<jdoc:include type="modules" name="bottom3" style="<?php echo $this->module_styles['bottom3']; ?>" modnum="<?php echo $this->API->modules('bottom3'); ?>" />
    	</div>
    </section>
    <?php endif; ?>
    
    <?php if($this->API->modules('bottom4')) : ?>
    <section id="gkBottom4">
    	<div>
    		<jdoc:include type="modules" name="bottom4" style="<?php echo $this->module_styles['bottom4']; ?>" modnum="<?php echo $this->API->modules('bottom4'); ?>" />
    	</div>
    </section>
    <?php endif; ?>
    
    <?php $this->layout->loadBlock('footer'); ?> 
    	
   	<?php $this->layout->loadBlock('social'); ?>
	<jdoc:include type="modules" name="debug" />
</body>
</html>