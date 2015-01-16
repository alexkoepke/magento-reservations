<?php
      
class Wage_Customreport_Block_Storeswitcher extends Mage_Adminhtml_Block_Store_Switcher
{

    /**
     * Get store groups for specified website
     *
     * @param Mage_Core_Model_Website $website
     * @return array
     */
    public function getStoreGroups($website)
    {
        if (!$website instanceof Mage_Core_Model_Website) {
            $website = Mage::app()->getWebsite($website);
        }
		Mage::log("groups ".print_r($website->getGroups(),true));
        return $website->getGroups();		
    }

     /**
     * Deprecated
     */
    public function getGroupCollection($website)
    {
        if (!$website instanceof Mage_Core_Model_Website) {
            $website = Mage::getModel('core/website')->load($website);
        }
		//foreach($website->getGroupCollection() as $key => $group){
		//	Mage::log("group collection ".print_r($group->getData(),true));			
		//}
		$collection = $website->getGroupCollection();
		//$collection->addItem($this->getMarketViewGroup());
        return $collection;
    }

    /**
     * Get store views for specified store group
     *
     * @param Mage_Core_Model_Store_Group $group
     * @return array
     */
    public function getStores($group)
    {
        if (!$group instanceof Mage_Core_Model_Store_Group) {
            $group = Mage::app()->getGroup($group);
        }
        $stores = $group->getStores();
        if ($storeIds = $this->getStoreIds()) {
            foreach ($stores as $storeId => $store) {
                if (!in_array($storeId, $storeIds)) {
                    unset($stores[$storeId]);
                }
            }
        }
		Mage::log("stores ".print_r($stores,true));
        return $stores;
    }

	public function getMarketViewGroup(){
		$marketGroup =  new Varien_Object();
		$marketGroup->setGroupId(10000);
		$marketGroup->setWebsiteId(1);
		$marketGroup->setName('Market Views');
		$marketGroup->setRootCategoryId(2670);
		$marketGroup->setDefaultStoreId(10000);
		return $marketGroup;
	}   
}
