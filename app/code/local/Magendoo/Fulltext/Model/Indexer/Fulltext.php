<?php

/**
 * CatalogSearch fulltext indexer model
 *
 * @category    Mage
 * @package     Mage_CatalogSearch
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Magendoo_Fulltext_Model_Indexer_Fulltext extends Mage_CatalogSearch_Model_Indexer_Fulltext
{
    /**
     * Rebuild all index data
     *
     */
    public function reindexAll()
    {
        $prefix = __CLASS__.'::'.__FUNCTION__;
        $engine = Mage::helper('catalogsearch')->getEngine();

        if(!$engine || !($engine instanceof Mage_CatalogSearch_Model_Resource_Fulltext_Engine)) {
            Mage::log($prefix.' - Engine: '.($engine?get_class($engine):' NONE ').' - run parent:reindexAll',null,'fulltext-reindex.log');
            return parent::reindexAll();
            Mage::log($prefix.' - Engine: '.($engine?get_class($engine):' NONE ').' - done parent:reindexAll',null,'fulltext-reindex.log');
        }

        Mage::log($prefix.' - start Magendoo_Fulltext rebuildAllIndexes',null,'fulltext-reindex.log');

        try {
            Mage::getResourceSingleton('fulltext/fulltext')->rebuildAllIndexes();
            Mage::log($prefix.' - done Magendoo_Fulltext rebuildAllIndexes',null,'fulltext-reindex.log');
        } catch(Exception $e) {
            Mage::logException($e);
            Mage::log($prefix.' - error Magendoo_Fulltext rebuildAllIndexes (see exception.log): '.$e->getMessage(),null,'fulltext-reindex.log');
            throw $e;
        }

    }

}
