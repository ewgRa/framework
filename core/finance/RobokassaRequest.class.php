<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RobokassaRequest
	{
		const ORDER_ID_KEY = 'InvId';

		const LANGUAGE_RU = 'ru';

		const SERVER_URL = 'https://merchant.roboxchange.com/Index.aspx';

		const TEST_SERVER_URL = 'http://test.robokassa.ru/Index.aspx';

		private $testMode = false;

		private $login = null;
		private $password = null;
		private $total = null;
		private $orderId = null;
		private $description = null;
		private $userEmail = null;

		private $language = self::LANGUAGE_RU;

		public static function create()
		{
			return new self();
		}

		public function setTestMode($testMode = true)
		{
			$this->testMode = ($testMode === true);
			return $this;
		}

		public function isTestMode()
		{
			return ($this->testMode === true);
		}

		public function testMode()
		{
			return $this->setTestMode(true);
		}

		public function productionMode()
		{
			return $this->setTestMode(false);
		}

		public function setLogin($login)
		{
			$this->login = $login;
			return $this;
		}

		public function setPassword($password)
		{
			$this->password = $password;
			return $this;
		}

		public function setTotal($total)
		{
			$this->total = str_replace(',', '.', $total);
			return $this;
		}

		public function setOrderId($orderId)
		{
			$this->orderId = $orderId;
			return $this;
		}

		public function setDescription($description)
		{
			$this->description = $description;
			return $this;
		}

		public function setUserEmail($userEmail)
		{
			$this->userEmail = $userEmail;
			return $this;
		}

		/**
		 * @return HttpUrl
		 */
		public function getUrl()
		{
			return
				HttpUrl::createFromString(
					$this->isTestMode()
						? self::TEST_SERVER_URL
						: self::SERVER_URL
				)->
				setQuery(
					http_build_query(
						array(
							'MrchLogin' => $this->login,
							'OutSum' => $this->total,
							'InvId' => $this->orderId,
							'Desc' => $this->description,
							'SignatureValue' => $this->compileSecureHash(),
							'Email' => $this->userEmail,
							'Culture' => $this->language
						)
					)
				);
		}

		private function compileSecureHash()
		{
			return md5(
				join(
					':',
					array(
						$this->login,
						$this->total,
						$this->orderId,
						$this->password
					)
				)
			);
		}
	}
?>