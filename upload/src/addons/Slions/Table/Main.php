<?php

namespace Slions\Table;


class Main 
{    
    /**
     * Add support for cell options such as colspan
     */
    public static function renderTableCell(/*XF\BbCode\Renderer\Html*/ $aRenderer, array $tag, array $options)
    {
        //\XF::dump("renderTableCell");
        //\XF::dump($tag);
        //\XF::dump($options);

        //TODO: define map of valid attributes

        //
        $cellOptions = "";
        if (is_array($tag['option'])) {
            // Build our HTML attribute such as colspan
            // TODO: restrict to some attributes as with this one could inject JavaScript maybe
            foreach ($tag['option'] as $key => $value) {				
                $cellOptions .= "$key='$value' ";				
            }
            //$cellOptions = implode(' ', $tag['option']);
        }
        
        // From parent implementation
        $output = $aRenderer->renderSubTree($tag['children'], $options);
        return "<$tag[tag] $cellOptions>$output</$tag[tag]>";
    }


    /**
	 * Copied from base class to disable filler
	 */
	public static function renderTagTable(/*XF\BbCode\Renderer\Html*/ $aRenderer, array $children, $option, array $tag, array $options)
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
					$rows[] = $aRenderer->renderTableRow($child, $options, $columnCount, $lostAndFound);
					$columnCounts[] = $columnCount;
				}
				else
				{
					$lostAndFound[] = $aRenderer->renderSubTree([$child], $options);
				}
			}
			else if (trim($child) !== '')
			{
				$lostAndFound[] = $aRenderer->renderSubTree([$child], $options);
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

		return $aRenderer->renderFinalTableHtml(implode('', $rows), $option, implode("\n", $lostAndFound));
	}

}

