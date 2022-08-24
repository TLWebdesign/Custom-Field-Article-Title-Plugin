<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.cfarticletitle
 *
 * @copyright   (C) 2022 TLWebdesign.nl <https://www.tlwebdesign.nl>
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\CMS\Plugin\CMSPlugin;

/**
 * CF Article title plugin class.
 *
 * @since  1.0
 */
class PlgContentCfarticletitle extends CMSPlugin
{
	/**
	 * @var    \Joomla\CMS\Application\SiteApplication
	 *
	 * @since  3.9.0
	 */
	protected $app;

	/**
	 * The save event.
	 *
	 * @param   string   $context  The context
	 * @param   object   $item	   The item
	 * @param   boolean  $isNew    Is new item
	 * @param   array    $data     The validated data
	 *
	 * @return  boolean
	 *
	 * @since   4.0.0
	 */
    public function onContentAfterSave($context, $item, $isNew, $data = [])
	{
        // Check if data is an array and the item has an id
        if (!is_array($data) || empty($item->id) || empty($data['com_fields'])) 
		{
            return;
        }

		if ($context === 'com_content.article')
		{
		
			// Loading the fields
			$fields = FieldsHelper::getFields($context, $item);

			if (!$fields) 
			{
				return;
			}
			$cfname = $this->params->def('cfname', null);
			if ($cfname) 
			{
				foreach ($fields as $field) 
				{
					// Determine the value if it is (un)available from the data
					if ( array_key_exists($field->name, $data['com_fields']) && ($cfname == $field->name) ) 
					{
						$value = $data['com_fields'][$field->name] === false ? null : $data['title'];
						$model = Factory::getApplication()->bootComponent('com_fields')->getMVCFactory()->createModel('Field', 'Administrator', ['ignore_request' => true]);
						$model->setFieldValue($field->id, $item->id, $value);

					}
				}
			}
			return true;
		}
	}
}