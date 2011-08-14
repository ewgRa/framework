<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RobokassaCurrency extends Enumeration
	{
		const BANK_CARD 	= 1;
		const QIWI_POCKET	= 2;

		protected $names = array(
			self::BANK_CARD   => 'Банковская карта',
			self::QIWI_POCKET => 'Qiwi кошелек'
		);

		protected $aliases = array(
			self::BANK_CARD   => 'BANKOCEAN2R',
			self::QIWI_POCKET => 'QiwiR'
		);

		/**
		 * @return RobokassaCurrency
		 */
		public static function create($id)
		{
			return new self($id);
		}

		public function getAlias()
		{
			return $this->aliases[$this->getId()];
		}
	}
?>