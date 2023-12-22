<?php

namespace Slions\Table\XF\Html\Renderer;

#use XF\BbCode\Traverser;
#use XF\Str\Formatter;
#use XF\Template\Templater;
#use XF\Util\Arr;

/**
 * This class is doing convertion from HTML to BbCode in our Froala editor when user switches from WYSIWYG to raw BB code editor 
 * That's also obviously used when saving from our WYSIWIG editor to obtain the raw text which is stored in our database.
 */
class BbCode extends XFCP_BbCode
{

	/**
	 * Constructor.
	 *
	 * @param array $options
	 */
	/*
	public function __construct(array $options = [])
	{
		\XF::logError("__construct - " . get_class($this));
		parent::__construct($options);
	}
	*/

	protected $modded = false;


	public function preFilter($html)
	{

		if (!$this->modded)
		{
			$this->modded = true;
			$this->_handlers['td'] = ['filterCallback' => ['$this', 'handleTagTableCell']];
			$this->_handlers['th'] = ['filterCallback' => ['$this', 'handleTagTableCell']];
			//$this->_handlers['td'] = ['filterCallback' => __NAMESPACE__ . '\BbCode::handleTagTD'];

		}

		return parent::preFilter($html);
	}


		/**
	 * Handles heading tags.
	 * This only works because we override that existing heading handler.
	 * 
	 * @param string $text Child text of the tag
	 * @param Tag $tag HTML tag triggering call
	 *
	 * @return string
	 */
	public function handleTagTableCell($text, \XF\Html\Tag $tag)
	{
		//\XF::logError("handleTagTD - " . get_class($this));

		//return parent::handleTagH($text,$tag);	

		// Probably just to apply basic formatting: italic, bold, underline and such
		// Yes it looks like this allows italic in our header to be properly translated from HTML to BbCode
		// However we don't yet support doing the translation from BbCode back to HTML
		// Somehow that was removed from XenForo 2.2.4 
		//$text = $this->renderCss($tag, $text);

		$colspan = $tag->attribute('colspan');
		$rowspan = $tag->attribute('rowspan');
		$style = $tag->attribute('style');
		$styles = "";

		if ($style) 
		{
			// Rebuild our CSS styles
			foreach ($style as $key => $value) {	
                $styles .= "$key: $value;";             
            }
		}

		// As BB code are usually displayed in uppercase	
		$tagName = strtoupper($tag->tagName());

		return "[$tagName" . 
		(empty($colspan)?"":" colspan='$colspan'") . 
		(empty($rowspan)?"":" rowspan='$rowspan'") . 
		(empty($styles)?"":" style='$styles'") . 
		//(empty($width)?"":" width='$width'") . 
		"]$text" . "[/$tagName]";
	}

}


