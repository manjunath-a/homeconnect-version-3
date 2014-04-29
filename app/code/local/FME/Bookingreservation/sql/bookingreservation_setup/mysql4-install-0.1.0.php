<?php

$installer = new Mage_Eav_Model_Entity_Setup('core_setup');



$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('bookingreservation')};
CREATE TABLE {$this->getTable('bookingreservation')} (                                  
                      `bookingreservation_id` int(11) unsigned NOT NULL auto_increment,  
                      `booking_order_id` int(11) NOT NULL,                               
                      `product_id` int(11) NOT NULL,                                     
                      `store_id` int(11) NOT NULL,                                       
                      `reserve_options` text NOT NULL,                                   
                      `reserve_day` date NOT NULL,                                       
                      `reserve_from_time` varchar(100) NOT NULL,                         
                      `reserve_to_time` varchar(100) NOT NULL default '',                
                      `buffer_period` int(11) NOT NULL,                                  
                      `customer_id` int(11) NOT NULL,                                    
                      `staffmember_id` int(11) NOT NULL default '0',                     
                      `status` varchar(32) NOT NULL,                                     
                      `created_time` datetime default NULL,                              
                      `update_time` datetime default NULL,
                      `days_booking` varchar(255) default NULL, 
                      PRIMARY KEY  (`bookingreservation_id`)                             
                    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		    
		    
DROP TABLE IF EXISTS {$this->getTable('booking_staff_products')};
CREATE TABLE {$this->getTable('booking_staff_products')} (                         
                          `booking_staff_products_id` int(11) NOT NULL auto_increment,  
                          `staffmembers_id` int(11) default NULL,                       
                          `product_id` int(11) default NULL,                            
                          UNIQUE KEY `bs_product_id` (`booking_staff_products_id`)      
                        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
			
			
DROP TABLE IF EXISTS {$this->getTable('staffmembers')};			
CREATE TABLE {$this->getTable('staffmembers')} (                                  
                `staffmembers_id` int(11) unsigned NOT NULL auto_increment,  
                `member_name` varchar(255) NOT NULL default '',              
                `member_age` int(11) default NULL,                           
                `profession` text,                                           
                `member_pic` varchar(255) default '',                        
                `staff_email` varchar(255) default NULL,                     
                `status` smallint(6) NOT NULL default '0',                   
                `created_time` datetime default NULL,                        
                `update_time` datetime default NULL,                         
                `all_days_start` varchar(100) default '',                    
                `all_days_end` varchar(100) default '',                      
                `monday_start` varchar(100) default '',                      
                `monday_end` varchar(100) default '',                        
                `tuesday_start` varchar(100) default '',                     
                `tuesday_end` varchar(100) default '',                       
                `wednesday_start` varchar(100) default '',                   
                `wednesday_end` varchar(100) default '',                     
                `thursday_start` varchar(100) default '',                    
                `thursday_end` varchar(100) default '',                      
                `friday_start` varchar(100) default '',                      
                `friday_end` varchar(100) default '',                        
                `saturday_start` varchar(100) default '',                    
                `saturday_end` varchar(100) default '',                      
                `sunday_start` varchar(100) default '',                      
                `sunday_end` varchar(100) default '',                        
                PRIMARY KEY  (`staffmembers_id`)                             
              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;   
    ");

    
    
//create fields for additional price(homeservice_amount) to qoute_address, orders, invoice, creditmemo

$installer->run("
                ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `homeservice_amount` DECIMAL( 10, 2 ) NOT NULL;
                ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `base_homeservice_amount` DECIMAL( 10, 2 ) NOT NULL;
                
                ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `homeservice_amount` DECIMAL( 10, 2 ) NOT NULL;
                ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_homeservice_amount` DECIMAL( 10, 2 ) NOT NULL;
                
                ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `homeservice_amount_invoiced` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_homeservice_amount_invoiced` DECIMAL( 10, 2 ) NOT NULL;
                
                ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `homeservice_amount` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `base_homeservice_amount` DECIMAL( 10, 2 ) NOT NULL;
                
                ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `homeservice_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_homeservice_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;
		
		ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `homeservice_amount` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `base_homeservice_amount` DECIMAL( 10, 2 ) NOT NULL;
                 
                ");



//Create product attributes for booking specific

	$installer->addAttribute('catalog_product', 'is_booking_product', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Is Booking & Reservation Product',		     
                     'type'	=> 'int',
                     'input'    => 'boolean',                     
                     'visible'  => true,
                     'required' => true,
                     'position' => 1,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'note'    => "is product a booking and reservation product"
        ));
	
        $installer->addAttribute('catalog_product', 'is_one_slot_price', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Enable First Slot Price',		     
                     'type'	=> 'int',
                     'input'    => 'boolean',                  
                     'visible'  => true,
                     'required' => false,
                     'position' => 2,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'note'     => "Enable One Slot by default, and its price"
        ));
        
	$installer->addAttribute('catalog_product', 'monday_business_hrs_starts', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Monday - Business hrs starts',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 3,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default'	=> '',
		     'note'	=> '09:00 am (set starting time for Monday)'
        ));
	
	$installer->addAttribute('catalog_product', 'monday_business_hrs_ends', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Monday - Business hrs ends',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 4,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' 	=> '',
		     'note'	=> '04:30 pm (set ending time for Monday)'
        ));
	
	$installer->addAttribute('catalog_product', 'tuesday_business_hrs_starts', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Tuesday - Business hrs starts',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 5,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' 	=> '',
		     'note'	=> '09:00 am (set starting time for Tuesday)'
        ));
	
	$installer->addAttribute('catalog_product', 'tuesday_business_hrs_ends', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Tuesday - Business hrs ends',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 6,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' 	=> '',
		     'note'	=> '04:30 pm (set ending time for Tuesday)'
		     
        ));
	
	$installer->addAttribute('catalog_product', 'wednesday_business_hrs_starts', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Wednesday - Business hrs starts',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 7,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' => '',
		     'note'	=> '09:00 am (set starting time for Wednesday)'
        ));
	
	$installer->addAttribute('catalog_product', 'wednesday_business_hrs_ends', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Wednesday - Business hrs ends',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 8,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' 	=> '',
		     'note'	=> '04:30 pm (set ending time for Wednesday)'
        ));
	
	$installer->addAttribute('catalog_product', 'thursday_business_hrs_starts', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Thursday - Business hrs starts',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 9,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' => '',
		     'note'	=> '09:00 am (set starting time for Thursday)'
        ));
	
	$installer->addAttribute('catalog_product', 'thursday_business_hrs_ends', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Thursday - Business hrs ends',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 10,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' => '',
		     'note'	=> '04:30 pm (set ending time for Thursday)'
        ));
	
	$installer->addAttribute('catalog_product', 'friday_business_hrs_starts', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Friday - Business hrs starts',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 11,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' => '',
		     'note'	=> '09:00 am (set starting time for Friday)'
        ));
	
	$installer->addAttribute('catalog_product', 'friday_business_hrs_ends', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Friday - Business hrs ends',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 12,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' 	=> '',
		     'note'	=> '04:30 pm (set ending time for Friday)'
        ));
	
	$installer->addAttribute('catalog_product', 'saturday_business_hrs_starts', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Saturday - Business hrs starts',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 13,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' => '',
		     'note'	=> '09:00 am (set starting time for Saturday)'
        ));
	
	$installer->addAttribute('catalog_product', 'saturday_business_hrs_ends', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Saturday - Business hrs ends',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 14,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' 	=> '',
		     'note'	=> '04:30 pm (set ending time for Saturday)'
        ));
	
	$installer->addAttribute('catalog_product', 'sunday_business_hrs_starts', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Sunday - Business hrs starts',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 15,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' => '',
		     'note'	=> 'closed (set starting time for Sunday)'
        ));
	
	$installer->addAttribute('catalog_product', 'sunday_business_hrs_ends', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Sunday - Business hrs ends',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 16,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' => '',
		     'note'	=> 'closed (set ending time for Sunday)'
        ));
	
	$installer->addAttribute('catalog_product', 'booking_time_slot', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Booking Time Slot',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 17,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' => '',
		     'note'	=> '15min (time slot - show a box/slot within an hour (60min), at frontend Time Schedule Table  )'
        ));
	
	$installer->addAttribute('catalog_product', 'buffer_period', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Buffer Period',		     
                     'type'	=> 'varchar',
                     'input'    => 'text',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 18,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' => '',
		     'note'	=> '5min (buffer time - maininance/cleaning time after each serve)'
        ));
	
	$installer->addAttribute('catalog_product', 'booking_tier_prices', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Booking Tier Prices',		     
                     'type'	=> 'text',
                     'input'    => 'textarea',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 19,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'default' => '',
		     'note'	=> '60 Minutes = $99,<br>
				    90 Minutes = $139,<br>
				    120 Minutes = $159'
        ));
	
	$installer->addAttribute('catalog_product', 'booking_type', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Booking Type',		     
                     'type'	=> 'varchar',
                     'input'    => 'select',                     
                     'visible'  => true,
                     'required' => false,
                     'position' => 20,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'source'	=> 'bookingreservation/resource_bookingtypes',
		     'note'	=> 'select booking type - Mobile booking has option for customer to select service at their home and it adds travel/driving charges to them'
        ));


        $installer->addAttribute('catalog_product', 'is_full_day_booking', array(
                     'group'	=> 'Booking & Reservation',
                     'label'    => 'Is Full Day Booking',		     
                     'type'	=> 'int',
                     'input'    => 'boolean',                  
                     'visible'  => true,
                     'required' => false,
                     'position' => 21,
                     'global'   => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
		     'note'     => "is full day booking product"
        ));


$installer->endSetup(); 
