<?php
/**
 * Cms model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------				---------------
 * __construct
 *
 */

namespace Modules\Cms\Models;

// Framework
use \A,
	\CModel,
	\CDatabase;


class Cms extends CModel
{

	public function __construct($params = array())
	{
		parent::__construct($params);
	}


}
