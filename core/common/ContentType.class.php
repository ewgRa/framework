<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ContentType extends Enumeration
	{
		const APPLICATION_PHP 	= 1;
		const TEXT_XSLT 		= 2;
		const TEXT_CSS 			= 3;
		const TEXT_JAVASCRIPT 	= 4;
		const TEXT_XML		 	= 5;
		
		protected $names = array(
			self::APPLICATION_PHP 	=> 'application/php',
			self::TEXT_XSLT 		=> 'text/xslt',
			self::TEXT_CSS 			=> 'text/css',
			self::TEXT_JAVASCRIPT 	=> 'text/javascript',
			self::TEXT_XML		 	=> 'text/xml'
		);
		
		private $extensions = array(
			self::APPLICATION_PHP 	=> 'php',
			self::TEXT_XSLT 		=> 'xsl',
			self::TEXT_CSS 			=> 'css',
			self::TEXT_JAVASCRIPT 	=> 'js',
			self::TEXT_XML		 	=> 'xml'
		);
		
		private $canBeJoined = array(
			self::APPLICATION_PHP 	=> false,
			self::TEXT_XSLT 		=> false,
			self::TEXT_CSS 			=> true,
			self::TEXT_JAVASCRIPT 	=> false,
			self::TEXT_XML		 	=> false
		);
		
		/**
		 * @return ContentType
		 */
		public static function create($id)
		{
			return new self($id);
		}
		
		/**
		 * @return ContentType
		 */
		public static function any()
		{
			return new self(self::APPLICATION_PHP);
		}
		
		/**
		 * @return ContentType
		 */
		public static function createByName($name)
		{
			$any = self::any();

			$names = $any->getNames();
			$names = array_flip($names);
			
			return self::create($names[$name]);
		}
		
		/**
		 * @return ContentType
		 */
		public static function createByExtension($extension)
		{
			$any = self::any();

			$extensions = $any->getExtensions();
			$extensions = array_flip($extensions);
			
			return self::create($extensions[$extension]);
		}
		
		public function getExtensions()
		{
			return $this->extensions;
		}
		
		public function getFileExtension()
		{
			return $this->extensions[$this->getId()];
		}

		public function canBeJoined()
		{
			return $this->canBeJoined[$this->getId()];
		}
	}
?>