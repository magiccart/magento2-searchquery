<?php
namespace Magiccart\SearchQuery\Plugin;


class Query extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magiccart\SearchQuery\Helper\Data $helper,
        array $data = []
    ) 
    {    
        parent::__construct($context, $data);
        $this->helper   = $helper;
    }

    public function aroundSaveIncrementalPopularity($subject, $callable)
    {
        return $subject;
    }

    public function aroundSaveNumResults($subject, $callable, $numResults)
    {
        $enabled = $this->helper->getConfigModule('general/enabled');
        $saveThis = true;
        if($enabled){
            $skip = $this->helper->getConfigModule('general/skip');
            $saveThis = (!$skip && $numResults);
        }
        if($saveThis){
            $subject->getResource()->saveIncrementalPopularity($subject);
            $subject->setNumResults($numResults);
            $subject->getResource()->saveNumResults($subject);
        }

        return $subject;
    }
}
