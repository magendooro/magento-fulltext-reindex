<?php


/**
 * CatalogSearch Fulltext Index into temporary table resource model
 *
 * @category    Magendoo
 * @package     Magendoo_CatalogSearch
 * @author      Emil [carco] Sirbu <emil.sirbu@gmail.com>
 */
class Magendoo_Fulltext_Model_Resource_Fulltext extends Mage_CatalogSearch_Model_Resource_Fulltext
{

    /**
     * Init resource model
     *
     */
    protected function _construct()
    {

        $this->_init('catalogsearch/fulltext', 'product_id');
        //$this->_engine = Mage::helper('catalogsearch')->getEngine();
        $this->_engine = Mage::getResourceSingleton('fulltext/engine'); //override default engine
    }



    /**
     * Regenerate search index for store(s)
     *
     * @param  int|null $storeId
     * @param  int|array|null $productIds
     * @return Magendoo_Fulltext_Model_Resource_Fulltext
     */
    public function rebuildAllIndexes()
    {

        $storeIds = array_keys(Mage::app()->getStores());
        foreach ($storeIds as $storeId) {
            $this->_rebuildStoreIndex($storeId);
        }


        $adapter = $this->_getWriteAdapter();

        $this->_engine->swapTables();
        $adapter->truncateTable($this->getTable('catalogsearch/result'));
        $adapter->update($this->getTable('catalogsearch/search_query'), array('is_processed' => 0));

        return $this;
    }


    /**
     * Reset search results - override default method to do nothing (Search results will be resets at end of reindex, @see rebuildAllIndexes )
     *
     * @return Magendoo_Fulltext_Model_Resource_Fulltext
     */
    public function resetSearchResults()
    {
        return $this;
    }

    /**
     * Delete search index data for store - override default method to do nothing (search index will be deleted at end of reindex, @see rebuildAllIndexes)
     *
     * @param int $storeId Store View Id
     * @param int $productId Product Entity Id
     * @return Magendoo_Fulltext_Model_Resource_Fulltext
     */
    public function cleanIndex($storeId = null, $productId = null)
    {
        return $this;
    }

}
