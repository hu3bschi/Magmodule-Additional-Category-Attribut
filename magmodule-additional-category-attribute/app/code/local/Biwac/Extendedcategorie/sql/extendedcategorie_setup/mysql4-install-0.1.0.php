<?php
/**
* Attribut der Kategorie ergänzen.
* Als Beispiel wird ein weiteres Beschreibungsfeld ausgegeben:
* @author Christian Hübschi <christian.huebschi@biwac.ch>
* @see Mage_Catalog_Model_Resource_Eav_Mysql4_Setup
*/

$installer = $this;
$installer->startSetup();
$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$installer->addAttribute('catalog_category',    // entity_type_id in der eav_attribute Tabellle
                         'biwac_ext_cat_attrb', // attribute_code in der eav_attribute Tabelle
                         array(                 // Die Attribute koennen in der Tabelle eav_attribute korrigiert werden.
                         
    'type'                      => 'text', // Bei $installer->run(... muss die catalog_category_entity_text angepasst werden falls nicht text
    'backend'                   => '',
    'frontend'                  => '',
    'label'                     => 'Additional Description',
    'input'                     => 'textarea',
    'class'                     => 'additional-categorie-desciption', // CSS-Klasse zb. für JS-Validierung: validate-length maximum-length-255
    'source'                    => '',
    'global'                    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'                   => true,
    'required'                  => false,
    'default'                   => '',
    'searchable'                => false,
    'filterable'                => false,
    'comparable'                => false,
    'wysiwyg_enabled'           => true,
    'is_html_allowed_on_front'  => true,
    'visible_on_front'          => false,
    'unique'                     => false
));
 
$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'biwac_ext_cat_attrb',
    '5' // Position kann nachtraeglich in der Tabelle eav_entity_attribute korrigiert werden.
);
 
$attributeId = $installer->getAttributeId($entityTypeId, 'biwac_ext_cat_attrb');
 
$installer->run("
INSERT INTO `{$installer->getTable('catalog_category_entity_text')}` 
(`entity_type_id`, `attribute_id`, `entity_id`, `value`)
    SELECT '{$entityTypeId}', '{$attributeId}', `entity_id`, '1'
        FROM `{$installer->getTable('catalog_category_entity')}`;
");
 
//this will set data of your custom attribute for root category
Mage::getModel('catalog/category')
    ->load(1)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();
 
//this will set data of your custom attribute for default category
Mage::getModel('catalog/category')
    ->load(2)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();
 
$installer->endSetup();

?>