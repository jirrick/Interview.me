<?php

class CandidateController extends My_Controller_Action {
	

	public function init()
	{
		$user = $this->getUser();
		if ($user !== NULL) {
			$this->view->user = $user;
        	$avatar = $user->getFoto();
			if ($avatar !== NULL) {
				$base64 = base64_encode($avatar->getfoto());
				$this->view->avatarBase64 = $base64;
			}
        }

		$this->_helper->ajaxContext
                        ->addActionContext('detail', 'html')
                        ->addActionContext('base-advanced-informations', 'html')
                        ->addActionContext('edit-advanced-informations', 'html')
                        ->addActionContext('save-advanced-informations', 'html')
                        ->addActionContext('detail-advanced-informations', 'html')
                        ->initContext('html');
	}

	public function indexAction() 
	{
		$candidates = My_Model::get('Candidates')->fetchAll();
		$this->view->messages = $this->_helper->flashMessenger->getMessages();
		
		$this->view->candidates = $candidates;
		$this->view->title = 'Candidates';
	}

	public function editAction()
	{
		// Only for administrators
		if (!$this->getUser() || !$this->getUser()->isAdmin()) {
			// Redirects to candidate list
			$this->_helper->redirector->gotoRoute(array('controller' => 'candidate', 'action' => 'index'), 'default', true);
			return;
		}

		$candidateId = $this->_request->getParam('id');

		// Creates form instance
		$form = new CandidateForm();
		$this->view->form = $form;

		// Edit candidate page
		if (!empty($candidateId)) {
			$this->view->title = 'Edit Candidate';

			$candidate = My_Model::get('Candidates')->getById($candidateId);
			$candidateData = $candidate->get_data();

			$candidateData['datum_narozeni'] = $this->transformDateToFormFormat($candidateData['datum_narozeni']);

			$this->fillFormWithData($form, $candidate);
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

			$this->fillFormWithData($form, NULL);
		}

		// ########################### POST ###########################
		// Handles form submission
		
		if ($this->_request->isPost()) {
			if($this->_request->getPost('saveButton', false)) {
				if ($form->isValid($this->_request->getPost())) {
					$formValues = $form->getValues();

					// Profile photo
					$photo;
					if ($form->profilePhoto->isUploaded()) {

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
							$photo->nazev = array_pop(explode("/", $profilePhotoLocation));
							$photo->save();
						}
						// Deletes file from directory (is already in DB)
						unlink($profilePhotoLocation);
					}

					// Converts dates into DB format
					$formValues['datum_narozeni'] = $this->transformDateToDbFormat($formValues['datum_narozeni']);
					
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

					// Extracts kandidat_priloha
					$deleteAttachments = $formValues['attachmentsCheckGroup'];
					unset($formValues['attachmentsCheckGroup']);				

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

					// Attachements
					// Deletes
					if ($deleteAttachments !== NULL) {
						foreach ($deleteAttachments as $dId) {
							$row = My_Model::get('Attachments')->getById($dId);
							$row->delete();
						}
					}

					// Creates
					if (!$form->attachments->receive()) {
						print "Error receiving the file";
					}

					// Reads location and creates blob
					$attsLocations = $form->attachments->getFileName();

					if (!is_array($attsLocations)) {
						$attsLocations = array($attsLocations);
					}

					foreach ($attsLocations as $location) {
						$attachmentBlob = file_get_contents($location);

						if (!empty($attachmentBlob)) {

							// Creates attachment object
							$attachment = My_Model::get('Attachments')->createRow();
							$attachment->priloha = $attachmentBlob;
							$attachment->nazev = array_pop(explode("/", $location));
							$attachment->save();

							// Creates kandidat_priloha object
							$connection = My_Model::get('CandidatesHasAttachments')->createRow();
							$connection->id_priloha = $attachment->getid_priloha();
							$connection->id_kandidat = $candidate->getid_kandidat();
							$connection->save();
						}
						// Deletes file from temp directory (is already in DB)
						unlink($location);
					}

					// Redirects
					$this->_helper->redirector->gotoRoute(array('controller' => 'candidate', 'action' => 'detail', 'id' => $candidate->getid_kandidat()), 'default', true);
				}
			}
			else if($this->_request->getPost('closeButton', false)) {
				if (!empty($candidateId)) {
					$this->_helper->redirector->gotoRoute(array('controller' => 'candidate', 'action' => 'detail', 'id' => $candidateId), 'default', true);
				}
				else {
					$this->_helper->redirector->gotoRoute(array('controller' => 'candidate', 'action' => 'index'), 'default', true);
				}
			}
			else if($this->_request->getPost('deleteButton', false)) {
				if (!empty($candidateId)) {
					My_Model::get('Candidates')->getById($candidateId)->delete();
				}
				$this->_helper->redirector->gotoRoute(array('controller' => 'candidate', 'action' => 'index'), 'default', true);
			}
		}
	}

	public function detailAction()
	{
		$this->view->title = 'Detail of Candidate';
		$this->view->messages = $this->_helper->flashMessenger->getMessages();

		$messageForm = new MessageForm();
		$this->view->messageForm = $messageForm;

		$candidateId = $this->_request->getParam('id');
		if (!empty($candidateId)) {
			$candidate = My_Model::get('Candidates')->getById($candidateId);


			if ($candidate !== null) {
				$advancedInfoId = $candidate->getid_pokrocile_informace();

				if (!empty($advancedInfoId)) {
					$advancedInfo = My_Model::get('AdvancedInformations')->getById($advancedInfoId);
					$this->view->advancedInformations = $advancedInfo;
				}
			}
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
			
			$available_tests = My_Model::get('Tests')->fetchAll();
			$this->view->available_tests = $available_tests;	
			
			$assigned_tests = $candidate->getAssignedTests();
			$this->view->assigned_tests = $assigned_tests;

			// Handles send message action
			if ($this->_request->isPost()) {
				$this->sendMessage($this->getParam('message'), $candidateId);
				$messageForm->message->setValue('');
			}

			// Direct chat
			$this->view->chatMessages = $this->loadDirectChatContent($candidateId);
		}
	}

	private function sendMessage($messageString, $candidateId)
	{
		$user = $this->getUser();

		// Message is not empty
		if (!empty($messageString) && $user !== NULL) {
			

			date_default_timezone_set('Europe/Prague');
			$now = date("Y-n-j H:i:s");

			$newMessage = My_Model::get('Messages')->createRow();
			$newMessage->text = $messageString;
			$newMessage->id_uzivatel = $user->getid_uzivatel();
			$newMessage->id_kandidat = $candidateId;
			$newMessage->datum_vytvoreni = $now;

			$newMessage->save();
		}
	}

	public function baseAdvancedInformationsAction()
	{
		// Only for administrators
		if (!$this->getUser() || !$this->getUser()->isAdmin()) {
			return;
		}

		$candidateId = $this->_request->getParam('id');
		if (!empty($candidateId)) {
			$candidate = My_Model::get('Candidates')->getById($candidateId);
		}

		if (!empty($candidateId)) {
			$candidate = My_Model::get('Candidates')->getById($candidateId);

			if ($candidate !== null) {
				$advancedInfoId = $candidate->getid_pokrocile_informace();

				if (!empty($advancedInfoId)) {
					$advancedInfo = My_Model::get('AdvancedInformations')->getById($advancedInfoId);
					$this->view->advancedInformations = $advancedInfo;
				}
			}
		}
	}

	public function detailAdvancedInformationsAction()
	{
		// Only for administrators
		if (!$this->getUser() || !$this->getUser()->isAdmin()) {
			return;
		}

		$candidateId = $this->_request->getParam('id');
		if (!empty($candidateId)) {
			$candidate = My_Model::get('Candidates')->getById($candidateId);
		}

		if (!empty($candidateId)) {
			$candidate = My_Model::get('Candidates')->getById($candidateId);

			if ($candidate !== null) {
				$advancedInfoId = $candidate->getid_pokrocile_informace();

				if (!empty($advancedInfoId)) {
					$advancedInfo = My_Model::get('AdvancedInformations')->getById($advancedInfoId);
					$this->view->advancedInformations = $advancedInfo;
				}
			}
		}
	}

	public function editAdvancedInformationsAction()
	{
		// Only for administrators
		if (!$this->getUser() || !$this->getUser()->isAdmin()) {
			return;
		}

		$candidateId = $this->_request->getParam('id');

		$form = new AdvancedInformationsForm();
		$form->setAction($this->view->url(array('controller' => 'candidate', 'action' => 'save-advanced-informations', 'id' => $candidateId), 'default', true));
		$this->view->advancedInformationsForm = $form;
		
		if (!empty($candidateId)) {
			$candidate = My_Model::get('Candidates')->getById($candidateId);

			if ($candidate !== null) {
				$advancedInfoId = $candidate->getid_pokrocile_informace();

				if (!empty($advancedInfoId)) {
					$advancedInfo = My_Model::get('AdvancedInformations')->getById($advancedInfoId);
					$this->view->advancedInformations = $advancedInfo;
				}
			}
		}

		// Advanced informations exists
		if ($advancedInfo !== null) {
			$this->fillAIFormWithData($form, $advancedInfo);

			$advancedInfosData = $advancedInfo->get_data();
			$advancedInfosData['datum_pohovoru'] = $this->transformDateToFormFormat($advancedInfosData['datum_pohovoru']);
			$advancedInfosData['datum_pristiho_kontaktu'] = $this->transformDateToFormFormat($advancedInfosData['datum_pristiho_kontaktu']);
			$advancedInfosData['datum_zahajeni'] = $this->transformDateToFormFormat($advancedInfosData['datum_zahajeni']);

			$form->setDefaults($advancedInfosData);
		}
		else {
			$this->fillAIFormWithData($form, NULL);
		}
	}

	public function saveAdvancedInformationsAction()
	{
		// Only for administrators
		if (!$this->getUser() || !$this->getUser()->isAdmin()) {
			return;
		}
		
		$candidateId = $this->_request->getParam('id');
		
		if (!empty($candidateId)) {
			$candidate = My_Model::get('Candidates')->getById($candidateId);

			if ($candidate !== null) {
				$advancedInfoId = $candidate->getid_pokrocile_informace();

				if (!empty($advancedInfoId)) {
					$advancedInfo = My_Model::get('AdvancedInformations')->getById($advancedInfoId);
				}
			}
		}

		// ########################### POST ###########################
		// Handles save action

		if ($this->_request->isPost()) {
			$formValues = $this->_request->getParam('formValues');

			if ($formValues === NULL) {
				return;
			}

			// Sets null to empty fields
			$nullCount = 0;
			foreach ($formValues as $vKey => $vValue) {
				if (!is_array($vValue) && strlen($vValue) === 0) {
					$formValues[$vKey] = null;
					$nullCount++;
				}
			}

			// Clicked save button but form wasn't filled. (form contains two selects —› two fields aren't empty).
			if (count($formValues) - $nullCount == 2) {
				return;
			}

			// Transforms date to DB format
			if (!empty($formValues['datum_pohovoru'])) {
				$formValues['datum_pohovoru'] = $this->transformDateToDbFormat($formValues['datum_pohovoru']);
			}
			if (!empty($formValues['datum_pristiho_kontaktu'])) {
				$formValues['datum_pristiho_kontaktu'] = $this->transformDateToDbFormat($formValues['datum_pristiho_kontaktu']);
			}
			if (!empty($formValues['datum_zahajeni'])) {
				$formValues['datum_zahajeni'] = $this->transformDateToDbFormat($formValues['datum_zahajeni']);
			}

			// Creates new AI if doesn't exist
			$isNewAi = false;
			if ($advancedInfo === null) {
				$isNewAi = true;
				$advancedInfo = My_Model::get('AdvancedInformations')->createRow();
			}

			// Extracts perzonalista_informace
			$newInterviewersIds = $formValues["perzonalista_informace["];
			unset($formValues['perzonalista_informace']);		

			// Updates AI
			$advancedInfo->updateFromArray($formValues);		

			$aiHasInterviewers = My_Model::get('AdvancedInformationsHasInterviewers');
			$oldAiHasInterviewers = $aiHasInterviewers->fetchAll($aiHasInterviewers->select()->where('id_pokrocile_informace = ?', $advancedInfo->getid_pokrocile_informace()));

			// Deletes perzonalista_informace objects
			foreach ($oldAiHasInterviewers as $x) {
				$deleteOld = true;

				for ($i = 0 ; $i < count($newInterviewersIds) ; $i++) {
					// Relation still exists
					if ($x->id_uzivatel === $newInterviewersIds[$i]) {
						$deleteOld = false;
						unset($newInterviewersIds[$i]);
						break;
					}
				}

				// Removes object, that doesn't exist (after update)
				if($deleteOld) {
					$x->delete();
				}
			}

			// Creates objects perzonalista_informace
			if ($newInterviewersIds !== NULL) {
				foreach ($newInterviewersIds as $x) {
					$new = $aiHasInterviewers->createRow();
					$new->id_pokrocile_informace = $advancedInfo->getid_pokrocile_informace();
					$new->id_uzivatel = $x;
					$new->save();
				}
			}

			// Assigns AI id to candidate
			if ($isNewAi) {
				$candidate->id_pokrocile_informace = $advancedInfo->id_pokrocile_informace;
				$candidate->save();
			}

			// Assigns AI into view
			$this->view->advancedInformations = $advancedInfo;
		}
	}

	// Content 

	private function loadDirectChatContent($candidateId)
	{
		$currentUser = $this->getUser();

		$mTable = My_Model::get('Messages');
		$messages = $mTable->fetchAll($mTable->select()->where('id_kandidat = ?', $candidateId));

		$uTable = My_Model::get('Users');

		$content = array();
		foreach ($messages as $m) {
			$creator = $uTable->getById($m->getid_uzivatel());

			$message = array();
			$message['text'] = $m->gettext();
			$message['date'] = str_replace('-', '.', $m->getdatum_vytvoreni());
			$message['name'] = $creator->getjmeno() . ' ' . $creator->getprijmeni();

			if ($currentUser !== NULL) {
				$message['right'] = $creator->getid_uzivatel() == $currentUser->getid_uzivatel();
			}

			$avatar = $creator->getFoto();
			if ($avatar !== NULL) {
				$base64 = base64_encode($avatar->getfoto());
				$message['avatarBase64'] = $base64;
			}

			$content[] = $message;
		}
		
		return $content;
	}

	// Helper methods

	private function fillFormWithData($form, $candidate)
	{
		// Loads data from database
		$statuses = My_Model::get('Statuses')->fetchAll();
		$positions = My_Model::get('Positions')->fetchAll();
		$technologies = My_Model::get('Technologies')->fetchAll();
		$seniorities = My_Model::get('Seniorities')->fetchAll();

		// Fills form selects and multiselect data
		$form->id_status->setMultiOptions($this->transformStatuses($statuses));
		$form->id_pozice->setMultiOptions($this->transformPositions($positions));
		$form->kandidat_technologie->setMultiOptions($this->transformTechnologies($technologies));
		$form->id_seniorita->setMultiOptions($this->transformSeniorities($seniorities));

		if ($candidate !== NULL) {
			$techs = $candidate->getTechnologies();
			if (!empty($techs)) {
				$techsDefault = array();
				for ($i = 0 ; $i < count($techs) ; $i++) {
					$techsDefault[$i] = $techs[$i]->getid_technologie();
				}
				$form->kandidat_technologie->setValue($techsDefault);
			}

			$atts = $candidate->getAttachments();

			if (!empty($atts)) {
				$checkGroupContent = array();
				foreach ($atts as $att) {
					$checkGroupContent[$att->id_priloha] = $att->nazev;
				}

				$form->attachmentsCheckGroup->setMultiOptions($checkGroupContent);
			}
		}
	}

	private function fillAIFormWithData($form, $advancedInfos)
	{
		// Loads data from database
		$contracts = My_Model::get('Contracts')->fetchAll();
		$currencies = My_Model::get('Currencies')->fetchAll();
		$users = My_Model::get('Users')->fetchAll();
		
		// Fills form selects and multiselect data
		$form->id_uvazek->setMultiOptions($this->transformContracts($contracts));
		$form->id_mena->setMultiOptions($this->transformCurrencies($currencies));
		$form->perzonalista_informace->setMultiOptions($this->transformUsers($users));

		if ($advancedInfos !== NULL) {
			$interviewers = $advancedInfos->getInterviewers();
			if (!empty($interviewers)) {
				$interviewersDefault = array();
				for ($i = 0 ; $i < count($interviewers) ; $i++) {
					$interviewersDefault[$i] = $interviewers[$i]->getid_uzivatel();
				}
				$form->perzonalista_informace->setValue($interviewersDefault);
			}
		}
	}

	private function transformDateToDbFormat($dateString)
	{
		$components = explode("/", $dateString);
		return $components[2]."-".$components[0]."-".$components[1];
	}

	private function transformDateToFormFormat($dateString)
	{
		if (strlen($dateString) == 0) {
			return "";
		}
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

	private function transformContracts($arr)
	{
		$rVal = array();
		foreach ($arr as $row) {
			$rVal[$row->id_uvazek] = $row->nazev;
		}
		return $rVal;
	}

	private function transformCurrencies($arr)
	{
		$rVal = array();
		foreach ($arr as $row) {
			$rVal[$row->id_mena] = $row->kod_meny;
		}
		return $rVal;
	}

	private function transformUsers($arr)
	{
		$rVal = array();
		foreach ($arr as $row) {
			$rVal[$row->id_uzivatel] = $row->getjmeno()." ".$row->getprijmeni();
		}
		return $rVal;
	}
	
}
