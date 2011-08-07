<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RobokassaResultRequest
	{
		private $form = null;

		private $password = null;

		public static function create(HttpRequest $request)
		{
			return new self($request);
		}

		public function __construct(HttpRequest $request)
		{
			$this->form =
				Form::create()->
				addPrimitive(
					PrimitiveInteger::create(RobokassaRequest::ORDER_ID_KEY)->
					setRequired()
				)->
				addPrimitive(
					PrimitiveFloat::create('OutSum')->
					addPreImportFilter(
						StringReplaceFilter::create()->addReplacement(',', '.')
					)->
					setRequired()
				)->
				addPrimitive(
					PrimitiveString::create('SignatureValue')->
					setRequired()
				);

			$this->form->
				import($request->getGet())->
				importMore($request->getPost());
		}

		public function setPassword($password)
		{
			$this->password = $password;
			return $this;
		}

		public function isValid()
		{
			return
				!$this->form->hasErrors()
				&& md5(
					join(
						':',
						array(
							$this->form->getRawValue('OutSum'),
							$this->form->getValue(RobokassaRequest::ORDER_ID_KEY),
							$this->password
						)
					)
				) == strtolower($this->form->getValue('SignatureValue'));
		}

		public function getTotal()
		{
			return $this->form->getValue('OutSum');
		}

		public function getOrderId()
		{
			return $this->form->getValue(RobokassaRequest::ORDER_ID_KEY);
		}
	}
?>