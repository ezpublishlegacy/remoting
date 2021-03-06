<?php
	/**
	 * This is the path to your services folder.  It should be fully qualified
	 * and must include a trailing /  If you leave the value blank an attempt
	 * will be made to search the INCLUDE_PATH for it.
	 */
	$cfg['AmfphpPath'] = realpath(dirname(dirname(__FILE__))) . '/';
	$cfg['ServicesPath'] = $cfg['AmfphpPath'] . 'services/';


	/**
	 *  If you want to ommit some services from the Service Browser list
	 *  add them to this array.
	 */
	$cfg['OmitPath'] = array('lib/');



//  You should not have to edit anything below this line...

	/**
	 *  if ServicesPath not set, try to find it in DOCUMENT_ROOT
	 *  and the include path
	 */

	if( '' == $cfg['ServicesPath'] ) 
	{

		if( is_dir( $_SERVER[ 'DOCUMENT_ROOT' ] . '/flashservices/services' )) 
		{
			$cfg['ServicesPath'] = $_SERVER['DOCUMENT_ROOT'] .  '/flashservices/services/';
		}
			 
		else {
			$path = get_include_path();
	
			$exploded = explode( ':', get_include_path() );
			reset( $exploded );
			while( list( , $possibility ) = each( $exploded )) {
				if( is_dir( $possibility . '/flashservices/services' )) 
				{
					$cfg['ServicesPath'] = $possibility .  '/flashservices/services/';
					break;
				}
			}
		}
	}
	
?>
