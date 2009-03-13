<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MimeContentTypes
	{
		const APPLICATION_PHP 	= 'application/php';
		const TEXT_XSLT 		= 'text/xslt';
		const TEXT_CSS 			= 'text/css';
		const TEXT_JAVASCRIPT 	= 'text/javascript';
		
		const CSS_EXTENSION = 'css';
		
		private static $extensionMimeRef = array(
			self::CSS_EXTENSION => self::TEXT_CSS
		);
		
		public static function getFileExtension($mediaType)
		{
			$result = null;
			$extensionFlip = array_flip(self::$extensionMimeRef);
			
			if(isset($extensionFlip[$mediaType]))
				$result = $extensionFlip[$mediaType];
			
			return $result;
		}
		
		public static function getFileMimeType($fileExtension)
		{
			$result = null;
			
			if(isset(self::$extensionMimeRef[$fileExtension]))
				$result = self::$extensionMimeRef[$fileExtension];
				
			return $result;
		}
		
		public static function isMediaFile($contentType)
		{
			return in_array($contentType, array(self::TEXT_CSS));
		}
		
		public static function getLayoutMimeTypes()
		{
			return array(
				self::TEXT_XSLT,
				self::APPLICATION_PHP
			);
		}
	}
?>