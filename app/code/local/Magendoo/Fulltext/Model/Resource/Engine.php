<?php

/**
 * CatalogSearch Fulltext Index Engine resource model
 *
 * @category    Magendoo
 * @package     Magendoo_Fulltext
 * @author      Emil [carco] Sirbu <emil.sirbu@gmail.com>
 */
class Magendoo_Fulltext_Model_Resource_Engine extends Mage_CatalogSearch_Model_Resource_Fulltext_Engine
{


    protected $_tmpTable  = null;


    /**
     * Multi add entities data to TEMPORARY fulltext search table
     *
     * @param int $storeId
     * @param array $entityIndexes
     * @param string $entity 'product'|'cms'
     * @return Magendoo_Fulltext_Model_Resource_Engine
     */
    public function saveEntityIndexes($storeId, $entityIndexes, $entity = 'product')
    {


        $data    = array();
        $storeId = (int)$storeId;
        foreach ($entityIndexes as $entityId => $index) {
            $data[] = array(
                'product_id'    => (int)$entityId,
                'store_id'      => $storeId,
                'data_index'    => $index
            );
        }

        if ($data) {
            Mage::getResourceHelper('catalogsearch')
                ->insertOnDuplicate($this->getTempTable(), $data, array('data_index'));
        }

        return $this;
    }


    public function swapTables() {
        $adapter = $this->_getWriteAdapter();
        $mainTable  = $this->getMainTable();
        $prevTable  = $this->getMainTable().'_prev';
        $tempTable  = $this->getTempTable();

        $adapter->dropTable($prevTable);
        $adapter->query("RENAME TABLE `{$mainTable}` TO `{$prevTable}`,`{$tempTable}` TO `{$mainTable}`");
    }



    public function getTempTable() {
        if(is_null($this->_tmpTable)) {
            $mainTable = $this->getMainTable();
            $this->_tmpTable = $mainTable.'_tmp';
            $this->_getWriteAdapter()->dropTable($this->_tmpTable);
            $this->_getWriteAdapter()->query("CREATE TABLE `{$this->_tmpTable}` LIKE  `{$mainTable}`");
        }
        return $this->_tmpTable;
    }

}
