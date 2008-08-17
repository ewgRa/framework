<?php
	/* $Id$ */

	class ContentController extends Controller
	{
		private $units = null;
		
		private function setUnits($units)
		{
			$this->units = $units;
			return $this;
		}
		
		private function getUnits()
		{
			return $this->units;
		}
		
		public function importSettings($settings)
		{
			$this->setUnits($settings['units']);

			$this->setCacheKey($this->getUnits());
			
			return $this;
		}
		
		public function getModel()
		{
			$dbQuery = "
				SELECT *
				FROM " . Database::me()->getTable('Contents') . " t1
				INNER JOIN " . Database::me()->getTable('ContentsData') . " t2
					ON( t1.id = t2.content_id AND t2.language_id = ? )
				WHERE
					t1.id IN (?)
			";
			
			$dbResult = Database::me()->query(
				$dbQuery,
				array(Localizer::me()->getLanguageID(), $this->getUnits())
			);

			return Database::me()->resourceToArray($dbResult);
		}
	}
?>