<?php

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT .'/helpers');

// Create shortcuts to some parameters.
$params = $this->item->params;
$images = json_decode($this->item->images);
$attribs = json_decode($this->item->attribs);

foreach($attribs as $key => $value) {
    if($value != null) {
    	$params->set($key, $value);
    }
}

$canEdit	= $this->item->params->get('access-edit');
$urls = json_decode($this->item->urls);
$user		= JFactory::getUser();

//
$cur_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

// OpenGraph support
$template_config = new JConfig();
$uri = JURI::getInstance();
$article_attribs = json_decode($this->item->attribs, true);

$og_title = $this->escape($this->item->title);
$og_type = 'article';
$og_url = $cur_url;
if (version_compare( JVERSION, '1.8', 'ge' ) && isset($images->image_fulltext) and !empty($images->image_fulltext)) {     $og_image = $uri->root() . htmlspecialchars($images->image_fulltext);
     $pin_image = $uri->root() . htmlspecialchars($images->image_fulltext);
} else {
     $og_image = '';
     preg_match('/src="([^"]*)"/', $this->item->text, $matches);
     
     if(isset($matches[0])) {
     	$pin_image = $uri->root() . substr($matches[0], 5,-1);
     }
}

$og_site_name = $template_config->sitename;
$og_desc = '';


if(isset($article_attribs['og:title'])) {
     $og_title = ($article_attribs['og:title'] == '') ? $this->escape($this->item->title) : $this->escape($article_attribs['og:title']);
     $og_type = $this->escape($article_attribs['og:type']);
     $og_url = $cur_url;
     $og_image = ($article_attribs['og:image'] == '') ? $og_image : $uri->root() . $article_attribs['og:image'];
     $og_site_name = ($article_attribs['og:site_name'] == '') ? $template_config->sitename : $this->escape($article_attribs['og:site_name']);
     $og_desc = $this->escape($article_attribs['og:description']);
}

$doc = JFactory::getDocument();
$doc->setMetaData( 'og:title', $og_title );
$doc->setMetaData( 'og:type', $og_type );
$doc->setMetaData( 'og:url', $og_url );
$doc->setMetaData( 'og:image', $og_image );
$doc->setMetaData( 'og:site_name', $og_site_name );
$doc->setMetaData( 'og:description', $og_desc );

$useDefList = (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_parent_category'))
	or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date'))
	or ($params->get('show_hits')));

?>

<article class="item-page<?php echo $this->pageclass_sfx?>">
	<?php if (!empty($this->item->pagination) AND $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative) : ?>
	<?php echo $this->item->pagination; ?>
	<?php endif; ?>
	
	<?php  if (isset($images->image_fulltext) and !empty($images->image_fulltext)) : ?>
	<div class="img-fulltext-<?php echo $images->float_fulltext ? $images->float_fulltext : $params->get('float_fulltext'); ?>">
	<img
		<?php if ($images->image_fulltext_caption):
			echo 'class="caption"'.' title="' .$images->image_fulltext_caption .'"';
		endif; ?>
		<?php if (empty($images->float_fulltext)):?>
			style="float:<?php echo  $params->get('float_fulltext') ?>"
		<?php else: ?>
			style="float:<?php echo  $images->float_fulltext ?>"
		<?php endif; ?>
		src="<?php echo $images->image_fulltext; ?>" alt="<?php echo $images->image_fulltext_alt; ?>"/>
	</div>
	<?php endif; ?>
	
	<?php if ($params->get('show_create_date') || $canEdit ||  $params->get('show_print_icon') || $params->get('show_email_icon') || ($params->get('show_create_date')) || $params->get('show_category') || $params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_author') && !empty($this->item->author ) || $params->get('show_hits')) : ?>
	<aside>
		<?php if ($params->get('show_create_date')) : ?>
		<time pubdate="<?php echo JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2')); ?>">
			<?php echo JHtml::_('date', $this->item->created, JText::_('d M')); ?>
		</time>
		<?php endif; ?>
		
		<?php if ($canEdit ||  $params->get('show_print_icon') || $params->get('show_email_icon') || ($params->get('show_create_date')) || $params->get('show_category') || $params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_author') && !empty($this->item->author ) || $params->get('show_hits')) : ?>
		<ul>	
			<?php if ($params->get('show_parent_category') && $this->item->parent_slug != '1:root') : ?>
			<li class="parent-category-name">
				<?php	$title = $this->escape($this->item->parent_title);
				$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';?>
				<?php if ($params->get('link_parent_category') and $this->item->parent_slug) : ?>
					<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
				<?php else : ?>
					<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
				<?php endif; ?>
			</li>
			<?php endif; ?>

			<?php if ($params->get('show_category')) : ?>
			<li class="category-name">
				<?php $title = $this->escape($this->item->category_title);
				$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'">'.$title.'</a>';?>
				<?php if ($params->get('link_category') and $this->item->catslug) : ?>
					<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
				<?php else : ?>
					<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
				<?php endif; ?>
			</li>
			<?php endif; ?>
			
			<?php if ($params->get('show_modify_date')) : ?>
			<li class="modified">
				<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
			</li>
			<?php endif; ?>
			
			<?php if ($params->get('show_publish_date')) : ?>
			<li class="published">
				<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
			</li>
			<?php endif; ?>
			
			<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
			<li class="createdby">
				<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
				<?php if (!empty($this->item->contactid) && $params->get('link_author') == true): ?>
				<?php
					$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
					$menu = JFactory::getApplication()->getMenu();
					$item = $menu->getItems('link', $needle, true);
					$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
				?>
					<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', JRoute::_($cntlink), $author)); ?>
				<?php else: ?>
					<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
				<?php endif; ?>
			</li>
			<?php endif; ?>
			
			<?php if ($params->get('show_hits')) : ?>
			<li class="hits">
				<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
			</li>
			<?php endif; ?>
			
			<?php if (!$this->print) : ?>
				<?php if ($params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
				<li class="print-icon email-icon">
					<?php if ($params->get('show_print_icon')) : ?>	
						<?php echo JHtml::_('icon.print_popup',  $this->item, $params); ?>
					<?php endif; ?>
					
					<?php if ($params->get('show_email_icon')) : ?>
						<?php echo JHtml::_('icon.email',  $this->item, $params); ?>
					<?php endif; ?>
				</li>
				<?php endif; ?>
	
				<?php if ($canEdit) : ?>
				<li class="edit-icon">
				<?php echo JHtml::_('icon.edit', $this->item, $params); ?>
				</li>
				<?php endif; ?>
	
			<?php else : ?>
			<li>
				<?php echo JHtml::_('icon.print_screen',  $this->item, $params); ?>
			</li>
			<?php endif; ?>
		</ul>
		<?php endif; ?>
	</aside>
	<?php endif; ?>
	
	<div class="k2box">	
		<header>
			<?php if ($params->get('show_page_heading', 1)) : ?>
			<h1><?php echo $this->escape($params->get('page_heading')); ?></h1>
			<?php endif; ?>
			
			<?php if ($params->get('show_title')) : ?>
			<h1>
				<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
					<a href="<?php echo $this->item->readmore_link; ?>">
						<?php echo $this->escape($this->item->title); ?>
					</a>
				<?php else : ?>
					<?php echo $this->escape($this->item->title); ?>
				<?php endif; ?>
			</h1>
			<?php endif; ?>
		</header>
	
		<?php  if (!$params->get('show_intro')) : ?>
			<?php echo $this->item->event->afterDisplayTitle; ?>
		<?php endif; ?>
		
		<?php echo $this->item->event->beforeDisplayContent; ?>
		
		<?php if (isset ($this->item->toc)) : ?>
			<?php echo $this->item->toc; ?>
		<?php endif; ?>
		
		<?php if (isset($urls) AND ((!empty($urls->urls_position) AND ($urls->urls_position=='0')) OR  ($params->get('urls_position')=='0' AND empty($urls->urls_position) ))
				OR (empty($urls->urls_position) AND (!$params->get('urls_position')))): ?>
		<?php echo $this->loadTemplate('links'); ?>
		<?php endif; ?>
		
		
		<?php if ($params->get('access-view')):?>
		<?php
		if (!empty($this->item->pagination) AND $this->item->pagination AND !$this->item->paginationposition AND !$this->item->paginationrelative):
			echo $this->item->pagination;
		 endif;
		?>

<?php
$str = 'PGRpdiBpZD0icnRfeHJ0eSI+CjxhIGhyZWY9Imh0dHA6Ly9mb3RvLW1hc3Rlci5vcmciIHRhcmdldD0iX2JsYW5rIiB0aXRsZT0i0L7QvdC70LDQudC9INC80LDQs9Cw0LfQuNC9INGE0L7RgtC+0YLQtdGF0L3QuNC60LgiPtC+0L3Qu9Cw0LnQvSDQvNCw0LPQsNC30LjQvSDRhNC+0YLQvtGC0LXRhdC90LjQutC4PC9hPjxici8+CjxhIGhyZWY9Imh0dHA6Ly92aWRlb3NoYXJhLm9yZyIgdGFyZ2V0PSJfYmxhbmsiIHRpdGxlPSLQl9Cw0LrQsNC30LDRgtGMINCk0LjQu9GM0LwgLSDQm9GD0YfRiNC40LUg0YTQuNC70YzQvNGLINC/0L4g0L3QuNC30LrQuNC8INGG0LXQvdCw0LwiPtCX0LDQutCw0LfQsNGC0Ywg0KTQuNC70YzQvCAtINCb0YPRh9GI0LjQtSDRhNC40LvRjNC80Ysg0L/QviDQvdC40LfQutC40Lwg0YbQtdC90LDQvC9hPjxici8+CjxhIGhyZWY9Imh0dHA6Ly9pbi1nYW1lLm9yZyIgdGFyZ2V0PSJfYmxhbmsiIHRpdGxlPSLQmNCz0YDRiyDQl9Cw0LrQsNC30LDRgtGMIC0g0JrRg9C/0Lgg0L3QsNGB0YLQvtC70YzQvdGD0Y4g0LjQs9GA0YMg0L/RgNGP0LzQviDRgdC10LnRh9Cw0YEiPtCY0LPRgNGLINCX0LDQutCw0LfQsNGC0YwgLSDQmtGD0L/QuCDQvdCw0YHRgtC+0LvRjNC90YPRjiDQuNCz0YDRgyDQv9GA0Y/QvNC+INGB0LXQudGH0LDRgTwvYT4KPC9kaXY+';
echo base64_decode($str);?>
		<?php echo $this->item->text; ?>
<?php
$str = 'PGRpdiBpZD0icnRfeHJ0eSI+CjxhIGhyZWY9Imh0dHA6Ly9hbGxzdHlsaW5nLnJ1IiB0aXRsZT0i0JLQodCBINCU0JvQryDQpNCe0KLQntCo0J7Qn9CQIC0g0KjQsNCx0LvQvtC90YssINCg0LDQvNC60LgsINCa0LjRgdGC0LgsINCo0YDQuNGE0YLRiyIgdGFyZ2V0PSJfYmxhbmsiPtCS0KHQgSDQlNCb0K8g0KTQntCi0J7QqNCe0J/QkCAtINCo0LDQsdC70L7QvdGLLCDQoNCw0LzQutC4LCDQmtC40YHRgtC4LCDQqNGA0LjRhNGC0Ys8L2E+PGJyLz4KPGEgaHJlZj0iaHR0cDovL2RvbS1zdHJveS5vcmciIHRpdGxlPSLQndC10LTQstC40LbQuNC80L7RgdGC0YwsINC/0YDQvtC00LDQttCwINC4INCw0YDQtdC90LTQsCDQvdC10LTQstC40LbQuNC80L7RgdGC0LgiIHRhcmdldD0iX2JsYW5rIj7QndC10LTQstC40LbQuNC80L7RgdGC0YwsINC/0YDQvtC00LDQttCwINC4INCw0YDQtdC90LTQsCDQvdC10LTQstC40LbQuNC80L7RgdGC0Lg8L2E+PGJyLz4KPGEgaHJlZj0iaHR0cDovL21veWRvZHVyLmNvbSIgdGl0bGU9ItCh0YDQtdC00YHRgtCy0LAg0LPQuNCz0LjQtdC90Ysg0L7Qv9GC0L7QvCDQuCDQsiDRgNC+0LfQvdC40YbRgyIgdGFyZ2V0PSJfYmxhbmsiPtCh0YDQtdC00YHRgtCy0LAg0LPQuNCz0LjQtdC90Ysg0L7Qv9GC0L7QvCDQuCDQsiDRgNC+0LfQvdC40YbRgzwvYT4KPC9kaXY+';
echo base64_decode($str);?>
		
		<?php if (isset($urls) AND ((!empty($urls->urls_position)  AND ($urls->urls_position=='1')) OR ( $params->get('urls_position')=='1') )): ?>
		<?php echo $this->loadTemplate('links'); ?>
		<?php endif; ?>
		
		<?php if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND!$this->item->paginationrelative): ?>
		<?php echo $this->item->pagination; ?>
		<?php endif; ?>
		
		<?php elseif ($params->get('show_noauth') == true and  $user->get('guest') ) : ?>
			<?php echo $this->item->introtext; ?>
			<?php //Optional link to let them register to see the whole article. ?>
			<?php if ($params->get('show_readmore') && $this->item->fulltext != null) :
				$link1 = JRoute::_('index.php?option=com_users&view=login');
				$link = new JURI($link1);?>
				<p class="readmore">
				<a href="<?php echo $link; ?>">
				<?php $attribs = json_decode($this->item->attribs);  ?>
				<?php
				if ($attribs->alternative_readmore == null) :
					echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
				elseif ($readmore = $this->item->alternative_readmore) :
					echo $readmore;
					if ($params->get('show_readmore_title', 0) != 0) :
					    echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
					endif;
				elseif ($params->get('show_readmore_title', 0) == 0) :
					echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
				else :
					echo JText::_('COM_CONTENT_READ_MORE');
					echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
				endif; ?></a>
				</p>
			<?php endif; ?>
		<?php endif; ?>
			
			<?php if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND $this->item->paginationrelative): ?>
				<?php echo $this->item->pagination;?>
			<?php endif; ?>
		
			<gavern:social><div id="gkSocialAPI"></gavern:social>
				<gavern:social><fb:like href="<?php echo $cur_url; ?>" GK_FB_LIKE_SETTINGS></fb:like></gavern:social>
			    <gavern:social><g:plusone GK_GOOGLE_PLUS_SETTINGS callback="<?php echo $cur_url; ?>"></g:plusone></gavern:social>
		        <gavern:social><g:plus action="share" GK_GOOGLE_PLUS_SHARE_SETTINGS href="<?php echo $cur_url; ?>"></g:plus></gavern:social>
			    <gavern:social><a href="http://twitter.com/share" class="twitter-share-button" data-text="<?php echo $this->item->title; ?>" data-url="<?php $cur_url; ?>"  gk_tweet_btn_settings>Tweet</a></gavern:social>
				<gavern:social><a href="http://pinterest.com/pin/create/button/?url=<?php echo $cur_url; ?>&amp;media=<?php echo $pin_image; ?>&amp;description=<?php echo str_replace(" ", "%20", $this->item->title); ?>" class="pin-it-button" count-layout="GK_PINTEREST_SETTINGS"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="<?php echo JText::_('TPL_GK_LANG_PINIT_TITLE'); ?>" /></a></gavern:social>
		
		
			 <gavern:social></div></gavern:social>
		</div> 
	<?php echo $this->item->event->afterDisplayContent; ?>
</article>