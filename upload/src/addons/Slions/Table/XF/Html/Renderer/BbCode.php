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
			$this->_handlers['td'] = ['filterCallback' => ['$this', 'handleTagTD']];
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
	public function handleTagTD($text, \XF\Html\Tag $tag)
	{
		//\XF::logError("handleTagTD - " . get_class($this));

		//return parent::handleTagH($text,$tag);	

		// Probably just to apply basic formatting: italic, bold, underline and such
		// Yes it looks like this allows italic in our header to be properly translated from HTML to BbCode
		// However we don't yet support doing the translation from BbCode back to HTML
		// Somehow that was removed from XenForo 2.2.4 
		//$text = $this->renderCss($tag, $text);

		$id = $tag->attribute('colspan');
		// As BB code are usually displayed in uppercase	
		$tagName = strtoupper($tag->tagName());		

		// rtrim because there was one extra newline
		// Seems to be only an issue when some CSS BB code is applied like CENTER or LEFT
		if (empty($id))
		{
			return '[' . $tagName . "]" . rtrim($text) . "[/". $tagName ."]";
		}
		else
		{
			return '[' . $tagName . ' colspan=\'' . $id . "']" . retrim($text) . "[/". $tagName ."]";
		}		
	}




}


