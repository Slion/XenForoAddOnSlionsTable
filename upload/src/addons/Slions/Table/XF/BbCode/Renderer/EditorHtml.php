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

	public function addDefaultTags()
	{
		// This is an override so we need to call the parent function to preserve stock behaviour
		parent::addDefaultTags();
		
		//\XF::dump("addDefaultTags");

		// Add our new BbCode tags
		// Some reason that's not working?
		//$this->addTag('h1', ['replace' => ['<h1>', '</h1>']]);
		//$this->addTag('h2', ['replace' => ['<h2>', '</h2>']]);
		//$this->addTag('h3', ['replace' => ['<h3>', '</h3>']]);
		//$this->addTag('h4', ['replace' => ['<h4>', '</h4>']]);
		//$this->addTag('h5', ['replace' => ['<h5>', '</h5>']]);
		//$this->addTag('h6', ['replace' => ['<h6>', '</h6>']]);
		//$this->addTag('doo', ['replace' => ['<h1>', '</h1>']]);


	}


	protected function renderTableCell(array $tag, array $options)
	{
		//\XF::dump("renderTableCell");	
		//\XF::dump($tag);
		//\XF::dump($options);

		//
		$cellOptions = "";
		if (is_array($tag['option'])) {
			// Build our HTML attribute such as colspan
			foreach ($tag['option'] as $key => $value) {				
				$cellOptions = "$key='$value' ";				
			}
			//$cellOptions = implode(' ', $tag['option']);
		}
		
		// From parent implementation
		$output = $this->renderSubTree($tag['children'], $options);
		return "<$tag[tag] $cellOptions>$output</$tag[tag]>";
	}

	
	public function renderTagTable(array $children, $option, array $tag, array $options)
	{
		$rows = [];
		$columnCounts = [];
		$lostAndFound = [];
		foreach ($children as $child)
		{
			if (is_array($child))
			{
				if ($child['tag'] === 'tr')
				{
					$rows[] = $this->renderTableRow($child, $options, $columnCount, $lostAndFound);
					$columnCounts[] = $columnCount;
				}
				else
				{
					$lostAndFound[] = $this->renderSubTree([$child], $options);
				}
			}
			else if (trim($child) !== '')
			{
				$lostAndFound[] = $this->renderSubTree([$child], $options);
			}
		}

		$maxColumnCount = max($columnCounts ?: [0]);
		foreach ($columnCounts as $i => $columnCount)
		{
			if ($columnCount < $maxColumnCount)
			{
				$td = strpos($rows[$i], '<th') !== false ? 'th' : 'td';
				$filler = ""; //str_repeat("<$td></$td>", $maxColumnCount - $columnCount);
				$rows[$i] = preg_replace('#</tr>$#', "$filler\\0", $rows[$i]);
			}
		}

		return $this->renderFinalTableHtml(implode('', $rows), $option, implode("\n", $lostAndFound));
	}

}


