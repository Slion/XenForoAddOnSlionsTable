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

	/*
	public function addDefaultTags()
	{
		// This is an override so we need to call the parent function to preserve stock behaviour
		parent::addDefaultTags();
		
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
	*/


    /**
	 * Add support for cell options such as colspan
	 */
	protected function renderTableCell(array $tag, array $options)
	{
		//\XF::dump("renderTableCell");	
		//\XF::dump($tag);
		//\XF::dump($options);

		//
		$cellOptions = "";
		if (is_array($tag['option'])) {
			// Build our HTML attribute such as colspan
			// TODO: restrict to some attributes as with this one could inject JavaScript maybe
			foreach ($tag['option'] as $key => $value) {				
				$cellOptions = "$key='$value' ";				
			}
			//$cellOptions = implode(' ', $tag['option']);
		}
		
		// From parent implementation
		$output = $this->renderSubTree($tag['children'], $options);
		return "<$tag[tag] $cellOptions>$output</$tag[tag]>";
	}


	/**
	 * Copied from base class to disable filler
	 */
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

	/**
	 * Inject our rendered TOC and reset it.
	 */
	/*
	public function filterFinalOutput($output)
	{
		//\XF::logError("Html::filterFinalOutput");

		// $tocId = 0;
		// $min = 0;
		// $max = 8;

		// Look-up our special TOC marker and extract id, min and max depth
		$output = preg_replace_callback('~\[TOC-(\d+)-(\d+)-(\d+)\]~i', function ($matches) 
		{
            $render = \Slions\Toc\BbCode::getToc($matches[1])->renderHtmlToc($matches[2],$matches[3]);
			//
			\Slions\Toc\BbCode::resetToc($matches[1]);
			return $render;
        }
		, $output);

		// Added that for BB code help page to render properly
		// See: https://staging.slions.net/help/bb-codes/
		\Slions\Toc\BbCode::resetToc(0);

		//{
			// $tocId = $res[1];
			// $min = $res[2];
			// $max = $res[3];
			
			// $tocRender = Slions\Toc\BbCode::getToc($tocId)->renderHtmlToc($min,$max);
		//}
		

		// TODO: render our TOC

		return parent::filterFinalOutput($output);
	}
	*/




}


