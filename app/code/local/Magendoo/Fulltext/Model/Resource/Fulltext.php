<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_CatalogSearch
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */


/**
 * CatalogSearch Fulltext Index resource model
 *
 * @category    Mage
 * @package     Mage_CatalogSearch
 * @author      Magento Core Team <core@magentocommerce.com>
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
