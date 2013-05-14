<?php

/**
 * @package		K2
 * @author		GavickPro http://gavick.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<article class="itemView">
	<?php echo $this->item->event->BeforeDisplay; ?>
	<?php echo $this->item->event->K2BeforeDisplay; ?>
	
	<figure>
		<?php if($this->item->params->get('latestItemImage') && !empty($this->item->image)): ?>
	   	<a class="itemImage" href="<?php echo $this->item->link; ?>" title="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>">
			<img src="<?php echo $this->item->image; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px;height:auto;" />
		</a>
		<?php endif; ?>
	
		<figcaption>
			<?php if($this->item->params->get('latestItemTitle')): ?>
			<h2>
				<?php if ($this->item->params->get('latestItemTitleLinked')): ?>
					<a href="<?php echo $this->item->link; ?>"><?php echo $this->item->title; ?></a>
				<?php else: ?>
					<?php echo $this->item->title; ?>
				<?php endif; ?>
			</h2>
			<?php endif; ?>
	
			<div class="itemCategory">
				<span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
				<a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a>
			</div>
	
			<?php if($this->item->params->get('latestItemDateCreated')): ?>
			<time datetime="<?php echo JHtml::_('date', $this->item->created, 'Y-m-d'); ?>">
				<?php echo JHTML::_('date', $this->item->created, JText::_('F j, Y')); ?>
			</time>
			<?php endif; ?>
	  	</figcaption>
  	</figure>

	<?php echo $this->item->event->AfterDisplay; ?>
  	<?php echo $this->item->event->K2AfterDisplay; ?>
</article>