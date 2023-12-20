<?php

namespace Slions\Table\XF\BbCode;

#use XF\BbCode\Traverser;
#use XF\Str\Formatter;
#use XF\Template\Templater;
#use XF\Util\Arr;

/**
 */
class RuleSet extends XFCP_RuleSet
{

    /**
     * Needed to support option keys.
     * See: https://xenforo.com/community/threads/font-awesome-5-editor-button-management-markdown-support-and-more.154701/#post-1287382
     */
    public function addDefaultTags()
	{
        // Make sure our parent implementation does its job 
        parent::addDefaultTags();

		// Modify table tags to support options
		// Funny enough setting hasOption to true breaks render when no options
		$this->modifyTag('table', [
			//'hasOption' => true,
			'supportOptionKeys' => RuleSet::OPTION_KEYS_BOTH
		]);

		$this->modifyTag('tr', [
			//'hasOption' => true,
			'supportOptionKeys' => RuleSet::OPTION_KEYS_BOTH
		]);

		$this->modifyTag('th', [
			//'hasOption' => true,
			'supportOptionKeys' => RuleSet::OPTION_KEYS_BOTH
		]);

		$this->modifyTag('td', [
			//'hasOption' => true,		
			'supportOptionKeys' => RuleSet::OPTION_KEYS_BOTH
		]);
		
	
	}

}


