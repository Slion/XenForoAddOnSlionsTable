<?php

namespace Slions\Table\XF\BbCode\Renderer;

#use XF\BbCode\Traverser;
#use XF\Str\Formatter;
#use XF\Template\Templater;
#use XF\Util\Arr;

/**
 * That guy decides which BbCode tag gets rendered in our HTML WYSIWYG editor.
 */
class EditorHtml extends XFCP_EditorHtml
{
	/**
	 * Override to add support for cell options such as colspan
	 */
	protected function renderTableCell(array $tag, array $options)
	{
		return \Slions\Table\Main::renderTableCell($this,$tag,$options);
	}

	/**
	 * This one is specific to EditorHtml as it is doing special break-start processing to avoid extra line breaks
	 */
	public function renderTagTable(array $children, $option, array $tag, array $options)
	{
		$wasInTable = !empty($options['inTable']);
		$options['inTable'] = true;

		$output = \Slions\Table\Main::renderTagTable($this, $children, $option, $tag, $options);
		$output = preg_replace('#\s*<break-start />\s*#i', "\n", $output);
		if (!$wasInTable)
		{
			$output = "<break-start />\n$output<break-start />\n";
		}

		return $output;
	}

	/**
	 * Override to inject styles.
	 * Needed to make it public 
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

		$output = "<table $attributes>$tableHtml</table>";

		if (strlen($extraContent))
		{
			$output .= "<p>$extraContent</p>";
		}

		return $output;
	}

	/**
	 * Needed to make it public 
	 */	
	public function renderTableRow(array $tag, array $options, &$columnCount, array &$lostAndFound)
	{
		return parent::renderTableRow($tag, $options, $columnCount, $lostAndFound);
	}

}


