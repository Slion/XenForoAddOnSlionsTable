<?php

namespace Slions\Table\XF\BbCode\Renderer;

#use XF\BbCode\Traverser;
#use XF\Str\Formatter;
#use XF\Template\Templater;
#use XF\Util\Arr;

/**
 * This class renders BbCode to HTML notably for page view.
 * It is calling custom BbCode callbacks from renderTag.
 * Custom BbCode can notably use renderSubTree to render children BbCodes and other content such as emojis, smiley, markdown, I guess.
 * 
 * Based on \XF\BbCode\Renderer\Html
 * Which derives from \XF\BbCode\Renderer\AbstractRenderer
 * Which derives from \XF\BbCode\Traverser
 */
class Html extends XFCP_Html
{
	/**
	 * Override to add support for cell options such as colspan
	 */
	protected function renderTableCell(array $tag, array $options)
	{
		return \Slions\Table\Main::renderTableCell($this,$tag,$options);
	}

	/**
	 * Copied from base class to disable filler
	 */
	public function renderTagTable(array $children, $option, array $tag, array $options)
	{
		return \Slions\Table\Main::renderTagTable($this, $children, $option, $tag, $options);
	}

	/**
	 * Override to inject attributes.
	 * Needed to make it public.
	 */	
	public function renderFinalTableHtml($tableHtml, $tagOption, $extraContent)
	{
		$hasStyles = false;
		$attributes = "";
        if (is_array($tagOption)) {
            // Build our HTML attribute such as style
            // TODO: restrict to some attributes as with this one could inject JavaScript I guess
            foreach ($tagOption as $key => $value) {	                
				if ($key=='style') 
				{
					$hasStyles = true;
					if (!str_contains($value,"width:"))
					{
						// No width specified, add 100% width then
						$value .= ' width: 100%;';
					}
				}

				$attributes .= "$key='$value' ";
            }            
        }

		if (!$hasStyles) 
		{
			// Could this not be done with CSS using like table.bbCode class?
			$attributes .= "style='width: 100%' ";
		}

		return "<div class=\"bbTable\">\n<table $attributes>$tableHtml</table>\n$extraContent</div>";
	}

	/**
	 * Needed to make it public 
	 */
	public function renderTableRow(array $tag, array $options, &$columnCount, array &$lostAndFound)
	{
		return parent::renderTableRow($tag, $options, $columnCount, $lostAndFound);
	}

}


