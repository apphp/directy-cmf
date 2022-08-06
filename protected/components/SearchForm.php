<?php
/**
 * SearchForm - component for building site search 
 *
 * PUBLIC (static):         PRIVATE (static):
 * -----------              ------------------
 * init						_getSearch
 * draw
 *
 */

class SearchForm extends CComponent
{
	
	/**
     *	Returns the instance of object
     *	@return current class
     */
	public static function init()
	{
		return parent::init(__CLASS__);
	}

	/**
	 * Draws menu from database
	 * @param string $placement left|right|top|bottom
	 * @param array $params
	 * @SEE self::_getMenu() 
	 * @return HTML code
	 */
	public static function draw($placement = '', $params = array())
	{
        return self::_getSearch($placement, $params);
	}

	/**
	 * Returns HTML code of the site search control
	 * @param string $placement left|right|top|bottom
	 * @param array $params
	 * @return HTML code
	 */
	private static function _getSearch($placement = '', $params = array())
	{
		$output = '';
		$cRequest = A::app()->getRequest();
		
		$keywords = trim($cRequest->getQuery('keywords'));
		
		$output .= CHtml::openForm('search/find', 'get', array('class'=>'form-search'));
		$output .= CHtml::textField('keywords', htmlentities($keywords), array('class'=>'input-medium search-query', 'placeholder'=>A::t('app', 'Search'), 'maxlength'=>'1024', 'autocomplete'=>'off'));
		$output .= CHtml::hiddenField('search_category', '', array());
		$output .= CHtml::submitButton(A::t('app', 'Search'), array('class'=>'btn'));
		$output .= CHtml::closeForm();
		
		// Define events handling for search form
		A::app()->getClientScript()->registerScript(
			'formSearch',
			'jQuery(".form-search").each(function(){
				var self = $(this);
				self.find(".btn").click(function(){
					var keywords = self.find(\'input[name="keywords"]\').val();
					if(keywords == ""){						
						return false;	
					}
				});
			});',
			3
		);
		
		return $output;           
	}	

}
