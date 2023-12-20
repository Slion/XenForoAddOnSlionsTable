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
	public function renderTagTableBase(array $children, $option, array $tag, array $options)
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
	 * This one is specific to EditorHtml as it is doing special break-start processing to avoid extra line breaks
	 */
	public function renderTagTable(array $children, $option, array $tag, array $options)
	{
		$wasInTable = !empty($options['inTable']);
		$options['inTable'] = true;

		$output = $this->renderTagTableBase($children, $option, $tag, $options);
		$output = preg_replace('#\s*<break-start />\s*#i', "\n", $output);
		if (!$wasInTable)
		{
			$output = "<break-start />\n$output<break-start />\n";
		}

		return $output;
	}


	/*
	protected function endsInBlockTag($text)
	{
		return true;
		return preg_match('#</(p|div|blockquote|pre|ol|ul)>$#i', substr(rtrim($text), -20));
	}
	*/

	/**
	 * In our working case with HEADING this is taking care of removing our <break />
	 * From EditorHtml.php
	 * 
	 * We had to override and copy this function implementation in order to fix our extra new line whenever editor HTML is rendered.
	 * See: https://xenforo.com/community/threads/parse-custom-bbcode-in-editorhtml-cant-add-new-tags.147361/
	 */
	/*
	public function filterFinalOutput($output)
	{
		//return $output;
		$debug = false;

		$btOpen = $this->blockTagsOpenRegex;
		$btClose = $this->blockTagsCloseRegex;

		//protected $blockTagsOpenRegex = '<p|<div|<blockquote|<ul|<ol|<table|<h\\d|<hr';
		//protected $blockTagsCloseRegex = '</p>|</div>|</blockquote>|</ul>|</ol>|</table>|</h\\d>|</hr>';
	

		$debugNl = ($debug ? "\n" : '');

		if ($debug) { echo '<hr /><b>Original:</b><br />'. nl2br(htmlspecialchars($output)); }

		$output = $this->cleanUpInlineListWrapping($output);

		$output = preg_replace('#\s*<break-start />(?>\s*)(?!' . $btOpen . '|' . $btClose . '|<break-start|$)#i', $debugNl . "<p>", $output);
		$output = preg_replace('#\s*<break-start />#i', '', $output);
		$output = preg_replace('#(' . $btClose . ')\s*<break />#i', "\\1", $output);
		// Original: creates an extra <p><br /></p>
		//$output = preg_replace('#<break />\s*(' . $btOpen . '|' . $btClose . ')#i', "</p>" . ($debug ? "\n" : '') . "\\1", $output);
		// Fixed:
		$output = preg_replace('#<break />\s*(' . $btOpen . '|' . $btClose . ')#i', $debugNl . "\\1", $output);

		// Original, causes one extra <p><br /></p> if we have at least one empty line between text and next header
		//$output = preg_replace('#<break />\s*#i', "</p>" . $debugNl . "<p>", $output);
		// Fix:
		$output = preg_replace('#<break />\s*#i', $debugNl . "<p />", $output);

		if ($debug) { echo '<hr /><b>Post-break:</b><br />'. nl2br(htmlspecialchars($output)); }

		$output = trim($output);
		if (!preg_match('#^(' . $btOpen . ')#i', $output))
		{
			$output = '<p>' . $output;
		}
		if (!preg_match('#(' . $btClose . ')$#i', $output))
		{
			$output .= '</p>';
		}
		else if (preg_match('#(</blockquote>|</table>|</ol>|</ul>)$#i', $output))
		{
			$output .= $debugNl . '<p></p>';
		}

		$output = preg_replace_callback('#(<p[^>]*>)(.*)(</p>)#siU',
			[$this, 'replaceEmptyContent'], $output
		);
		$output = str_replace('<empty-content />', '', $output); // just in case

		$output = $this->fixListStyles($output);

		if ($debug) { echo '<hr /><b>Final:</b><br />'. nl2br(htmlspecialchars($output)); }

		return $output;
	}
	*/

}


