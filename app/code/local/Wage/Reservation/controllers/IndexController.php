<?php

class Wage_Reservation_IndexController extends Mage_Core_Controller_Front_Action
{
    const XML_PATH_EMAIL_RECIPIENT  = 'reservation/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'reservation/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'reservation/email/email_template';

    public function postAction()
    {
        $post = $this->getRequest()->getPost();
        if ($post) {
            $store = Mage::app()->getStore();
            $storeId = $store->getId();
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = false;

                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['email']) , 'EmailAddress')) {
                    $error = true;
                }

                if ($error) {
                    throw new Exception();
                }

                //Save info in reservation table through Magento default way
                //$reservertionOb = Mage::getModel('reservation/reservation')->saveReservation($postObject, $storeId);

                //save info in reservation table direct sql query
                $write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database
                $orderTable = Mage::getSingleton( 'core/resource' )->getTableName( 'reservation/reservation_order' );
                $itemTable = Mage::getSingleton( 'core/resource' )->getTableName( 'reservation/reservation_order_item' );
                $orderData = array(
                    'store_id' => $storeId,
                    'email' => $postObject->getEmail(),
                    'name' => $postObject->getName(),
                    'reserve_date' => $postObject->getReserveDate(),
                    'sign_up' => 0,
                    'created_date' => date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())),
                );
                //$write->query( $query, $binds );
                $write->insert($orderTable,$orderData);

                $query = 'SELECT reservation_id FROM ' . $orderTable . ' WHERE email = '
                    . (int)$postObject->getEmail();
                $results = $write->fetchAll($query);

                $reservationArray = array_pop($results);
                $reservation_id = $reservationArray['reservation_id'];

                $write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database
                $items =  Mage::getModel('reservation/reservation')->getItems($postObject);
                foreach ($items as $item) {
                    $itemData = array();
                    $itemData = array(
                        'reservation_id' => $reservation_id,
                        'product_id' => $item['id'],
                        'product_name' => $item['name'],
                        'sku' => $item['sku'],
                        'ordered_qty' => $item['qty'],
                        'product_price' => $item['price'],
                    );
                    try {
                        $write->insert($itemTable,$itemData);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }

                /* To Subscribe */
                if (isset($post['is_subscribed'])) {
                    Mage::getModel('newsletter/subscriber')->subscribe(trim($post['email']));
                }

                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $storeId))
                    ->addBcc(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT, $storeId))
                    ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $storeId),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER, $storeId),
                        $post['email'],
                        null,
                        array('reservation' => $postObject, 'store' => $store)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);

                //$this->_redirect('*/*/success', array('id' => $reservertionOb));
                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addError(Mage::helper('reservation')->__('Unable to submit your reservation. Please, try again later'));
                //$this->_redirect('*/*/');
                return;
            }

        } else {
            //$this->_redirect('*/*/');
        }
    }

    public function successAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Reservation Success'));
        $this->renderLayout();
    }
}
