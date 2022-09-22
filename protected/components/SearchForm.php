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
	 * Draws search form
	 * @param array $params
	 * @SEE self::_getSearch() 
	 * @return HTML code
	 */
	public static function draw($params = array())
	{
        return self::_getSearch($params);
	}

	/**
	 * Returns HTML code of the site search control
	 * @param array $params
	 * Ex.:
	 * <form class="form-search" action="search/find" method="get">
	 * 		<div class="input-group">
	 * 			<input value="" name="search_category" id="search_category" type="hidden">
	 * 			<input class="inputClass" placeholder="placeHolder" maxlength="1024" autocomplete="off" value="" name="keywords" id="keywords" type="search">
	 * 			<input class="btn" name="ap0" value="Search" type="submit">
	 * 			- or -
	 * 			buttonHtml
	 * 		</div>
	 * </form>
	 * @return HTML code
	 */
	private static function _getSearch($params = array())
	{
		$output = '';
		$cRequest = A::app()->getRequest();
		$keywords = $cRequest->getQuery('keywords');
		
		// Don't search on array
		if(is_array($keywords)){
			return $output;
		}
		
		$keywords = trim($keywords);
		$innerWrapper = isset($params['innerWrapper']) ? (bool)$params['innerWrapper'] : false;
		$inputClass = isset($params['inputClass']) ? $params['inputClass'] : 'input-medium search-query';
		$placeHolder = isset($params['placeHolder']) ? $params['placeHolder'] : A::te('app', 'Search');
		$buttonHtml = isset($params['buttonHtml']) ? $params['buttonHtml'] : CHtml::submitButton(A::t('app', 'Search'), array('class'=>'btn'));
		
		$output .= CHtml::openForm('search/find', 'get', array('class'=>'form-search'));
		if($innerWrapper) $output .= CHtml::openTag('div', array('class'=>'input-group'));
		$output .= CHtml::hiddenField('search_category', '', array());
		$output .= CHtml::searchField('keywords', htmlspecialchars($keywords), array('class'=>$inputClass, 'placeholder'=>$placeHolder, 'maxlength'=>'1024', 'autocomplete'=>'off'));
		$output .= $buttonHtml;
		if($innerWrapper) $output .= CHtml::closeTag('div');
		$output .= CHtml::closeForm();
		
		// Define events handling for search form
		A::app()->getClientScript()->registerScript(
			'formSearch',
			'jQuery(".form-search").each(function(){
				var self = jQuery(this);
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
