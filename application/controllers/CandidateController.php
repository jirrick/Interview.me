<?php

class CandidateController extends My_Controller_Action {
	

	public function init()
	{
		$this->view->user = $this->getUser();
	}

	public function indexAction() 
	{
		$candidates = My_Model::get('Candidates')->fetchAll();
		
		$this->view->candidates = $candidates;
		$this->view->title = 'Candidates';
	}


	public function editAction()
	{
		$candidateId = $this->_request->getParam('id');

		// Creates form instance
		$form = new CandidateForm();
		$this->view->form = $form;

		// Loads data from database
		$statuses = My_Model::get('Statuses')->fetchAll();
		$positions = My_Model::get('Positions')->fetchAll();
		$technologies = My_Model::get('Technologies')->fetchAll();
		$seniorities = My_Model::get('Seniorities')->fetchAll();

		// Fills form selects and multiselect data
		$form->getElement('id_status')->setMultiOptions($this->transformStatuses($statuses));
		$form->getElement('id_pozice')->setMultiOptions($this->transformPositions($positions));
		$form->getElement('kandidat_technologie')->setMultiOptions($this->transformTechnologies($technologies));
		$form->getElement('id_seniorita')->setMultiOptions($this->transformSeniorities($seniorities));

		// Edit candidate page
		if (!empty($candidateId)) {
			$this->view->title = 'Edit Candidate';

			$candidate = My_Model::get('Candidates')->getById($candidateId);
			$candidateData = $candidate->get_data();

			$candidateData['datum_narozeni'] = $this->transformDateToFormFormat($candidateData['datum_narozeni']);
			$candidateData['datum_pohovoru'] = $this->transformDateToFormFormat($candidateData['datum_pohovoru']);

			$form->setDefaults($candidateData);

			$avatar = $candidate->getFoto();
			if ($avatar !== NULL) {
				$base64 = base64_encode($avatar->getfoto());
				$form->avatar->setAttrib('src', "data:image/gif;base64," . $base64);	
			}
		}
		// Create candidate page
		else {
			$this->view->title = 'Create new Candidate';

		}

		// ########################### POST ###########################
		// Handles form submission
		
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$formValues = $form->getValues();

				// Profile photo
				$photo;
				if (!empty($form->profilePhoto)) {
					
					if (!$form->profilePhoto->receive()) {
						print "Error receiving the file";
					}

					// Reads location and creates blob
					$profilePhotoLocation = $form->profilePhoto->getFileName();
					$profilePhotoBlob = file_get_contents($profilePhotoLocation);

					if (!empty($profilePhotoBlob)) {
						// Creates photo object
						$photo = My_Model::get('Photos')->createRow();
						$photo->foto = $profilePhotoBlob;
						$photo->jmeno = array_pop(explode("/", $profilePhotoLocation));
						$photo->save();
					}
					// Deletes file from directory (is already in DB)
					unlink($profilePhotoLocation);
				}


				// Converts dates into DB format
				$formValues['datum_narozeni'] = $this->transformDateToDbFormat($formValues['datum_narozeni']);
				$formValues['datum_pohovoru'] = $this->transformDateToDbFormat($formValues['datum_pohovoru']);

				// Adds photo id
				if (!empty($photo)) {
					$formValues['id_foto'] = $photo->getid_foto();
				}

				// Adds last update date
				date_default_timezone_set('UTC');
				$formValues['datum_aktualizace'] = date("Y-n-j");

				$candidate;
				// Editing existing candidate
				if (!empty($candidateId)) {
					$candidate = My_Model::get('Candidates')->getById($candidateId);
				}
				// Creates new candidate
				else {
					$candidate = My_Model::get('Candidates')->createRow();
				}

				// Extracts kandidat_technologie
				$newTechnologieIds = $formValues['kandidat_technologie'];
				unset($formValues['kandidat_technologie']);

				// Updates candidate object in DB
				$candidate->updateFromArray($formValues);

				$cht = My_Model::get('CandidatesHasTechnologies');
				$oldTechnologies = $cht->fetchAll($cht->select()->where('id_kandidat = ?', $candidate->getid_kandidat()));

				// Deletes kandidat_technologie objects
				foreach ($oldTechnologies as $oTechnology) {
					$deleteOld = true;
					
					for ($i = 0 ; $i < count($newTechnologieIds) ; $i++) {
						// Relation still exists
						if ($oTechnology->id_technologie === $newTechnologieIds[$i]) {
							$deleteOld = false;
							unset($newTechnologieIds[$i]);
							break;
						}
					}

					// Removes object, that doesn't exist (after update)
					if($deleteOld) {
						$oTechnology->delete();
					}
				}

				// Creates objects kandidat_technologie
				foreach ($newTechnologieIds as $nTechnologyId) {
					$new = My_Model::get('CandidatesHasTechnologies')->createRow();
					$new->id_kandidat = $candidate->getid_kandidat();
					$new->id_technologie = $nTechnologyId;
					$new->save();
				}

				$this->_helper->redirector->gotoRoute(array('controller' => 'candidate', 'action' => 'index'), 'default', true);
			}
		}
	}

	

	public function detailAction()
	{
		$this->view->title = 'Detail of Candidate';

		$candidateId = $this->_request->getParam('id');
		if (!empty($candidateId)) {
			$candidate = My_Model::get('Candidates')->getById($candidateId);
		}

		if ($candidate === null) {
			$this->_helper->flashMessenger->addMessage("Kandidát nebyl nalezen");
			$this->_helper->redirector->gotoRoute(array('controller' => 'candidate',
				'action' => 'index'),
			'default',
			true);
		}
		else {
			$this->view->candidate = $candidate;

			$avatar = $candidate->getFoto();
			if ($avatar !== NULL) {
				$base64 = base64_encode($avatar->getfoto());
				$this->view->candidateAvatarBase64 = $base64;
			}
			
		}
	}

	// Helper methods

	private function transformDateToDbFormat($dateString)
	{
		$components = explode("/", $dateString);
		return $components[2]."-".$components[0]."-".$components[1];
	}

	private function transformDateToFormFormat($dateString)
	{
		$components = explode("-", $dateString);
		return $components[1]."/".$components[2]."/".$components[0];
	}

	private function transformStatuses($arr)
	{
		$rVal = array();
		foreach ($arr as $row) {
			$rVal[$row->id_status] = $row->nazev;
		}
		return $rVal;
	}

	private function transformPositions($arr)
	{
		$rVal = array();
		foreach ($arr as $row) {
			$rVal[$row->id_pozice] = $row->nazev;
		}
		return $rVal;
	}

	private function transformTechnologies($arr)
	{
		$rVal = array();
		foreach ($arr as $row) {
			$rVal[$row->id_technologie] = $row->nazev;
		}
		return $rVal;
	}

	private function transformSeniorities($arr)
	{
		$rVal = array();
		foreach ($arr as $row) {
			$rVal[$row->id_seniorita] = $row->nazev;
		}
		return $rVal;
	}
	
}

?>