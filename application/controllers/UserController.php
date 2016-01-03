<?php

class UserController extends My_Controller_Action {
	

	public function init()
	{
		$this->view->user = $this->getUser();

		$this->_helper->ajaxContext
                        ->addActionContext('delete', 'html')
                        ->addActionContext('toggle-admin-role', 'html')
                        ->addActionContext('isAdmin', 'html')
                        ->initContext('html');
	}

	public function indexAction() 
	{
		$users = My_Model::get('Users')->fetchAll();
		$this->view->messages = $this->_helper->flashMessenger->getMessages();
		
		$this->view->users = $users;
		$this->view->title = 'Users';
	}

	public function isAdminAction()
	{
		// Disables view script call
		$this->_helper->viewRenderer->setNoRender(true);

		// Disables layout to action mapping
		$this->_helper->layout->disableLayout();

		echo $this->getUser()->isAdmin() ? '1' : '0';
	}

	public function deleteAction() 
	{
		if ($this->getUser()->isAdmin()) {
			$userId = $this->_request->getParam('id');
			if (!empty($userId)) {
				$user = My_Model::get('Users')->getById($userId);
				if ($user !== NULL) {
					$user->delete();
				}
			}
		}

		$users = My_Model::get('Users')->fetchAll();
		$this->view->users = $users;
	}

	public function toggleAdminRoleAction() 
	{
		if ($this->getUser()->isAdmin()) {
			$userId = $this->_request->getParam('id');
			if (!empty($userId)) {
				$user = My_Model::get('Users')->getById($userId);
				if ($user !== NULL) {
					$user->setadmin($user->getadmin() == 0 ? 1 : 0);
					$user->save();
				}
			}
		}

		$users = My_Model::get('Users')->fetchAll();
		$this->view->users = $users;
	}

	//public function editAction()
	//{
	//	$candidateId = $this->_request->getParam('id');
//
	//	// Creates form instance
	//	$form = new CandidateForm();
	//	$this->view->form = $form;
//
	//	// Edit candidate page
	//	if (!empty($candidateId)) {
	//		$this->view->title = 'Edit Candidate';
//
	//		$candidate = My_Model::get('Candidates')->getById($candidateId);
	//		$candidateData = $candidate->get_data();
//
	//		$candidateData['datum_narozeni'] = $this->transformDateToFormFormat($candidateData['datum_narozeni']);
//
	//		$this->fillFormWithData($form, $candidate);
	//		$form->setDefaults($candidateData);
//
	//		$avatar = $candidate->getFoto();
	//		if ($avatar !== NULL) {
	//			$base64 = base64_encode($avatar->getfoto());
	//			$form->avatar->setAttrib('src', "data:image/gif;base64," . $base64);	
	//		}
	//	}
	//	// Create candidate page
	//	else {
	//		$this->view->title = 'Create new Candidate';
//
	//		$this->fillFormWithData($form, NULL);
	//	}
//
	//	// ########################### POST ###########################
	//	// Handles form submission
	//	
	//	if ($this->_request->isPost()) {
	//		if($this->_request->getPost('saveButton', false)) {
	//			if ($form->isValid($this->_request->getPost())) {
	//				$formValues = $form->getValues();
//
	//				// Profile photo
	//				$photo;
	//				if ($form->profilePhoto->isUploaded()) {
//
	//					if (!$form->profilePhoto->receive()) {
	//						print "Error receiving the file";
	//					}
//
	//					// Reads location and creates blob
	//					$profilePhotoLocation = $form->profilePhoto->getFileName();
	//					$profilePhotoBlob = file_get_contents($profilePhotoLocation);
//
	//					if (!empty($profilePhotoBlob)) {
	//						// Creates photo object
	//						$photo = My_Model::get('Photos')->createRow();
	//						$photo->foto = $profilePhotoBlob;
	//						$photo->nazev = array_pop(explode("/", $profilePhotoLocation));
	//						$photo->save();
	//					}
	//					// Deletes file from directory (is already in DB)
	//					unlink($profilePhotoLocation);
	//				}
//
	//				// Converts dates into DB format
	//				$formValues['datum_narozeni'] = $this->transformDateToDbFormat($formValues['datum_narozeni']);
	//				
	//				// Adds photo id
	//				if (!empty($photo)) {
	//					$formValues['id_foto'] = $photo->getid_foto();
	//				}
//
	//				// Adds last update date
	//				date_default_timezone_set('UTC');
	//				$formValues['datum_aktualizace'] = date("Y-n-j");
//
	//				$candidate;
	//				// Editing existing candidate
	//				if (!empty($candidateId)) {
	//					$candidate = My_Model::get('Candidates')->getById($candidateId);
	//				}
	//				// Creates new candidate
	//				else {
	//					$candidate = My_Model::get('Candidates')->createRow();
	//				}
//
	//				// Extracts kandidat_technologie
	//				$newTechnologieIds = $formValues['kandidat_technologie'];
	//				unset($formValues['kandidat_technologie']);
//
	//				// Extracts kandidat_priloha
	//				$deleteAttachments = $formValues['attachmentsCheckGroup'];
	//				unset($formValues['attachmentsCheckGroup']);				
//
	//				// Updates candidate object in DB
	//				$candidate->updateFromArray($formValues);
//
	//				$cht = My_Model::get('CandidatesHasTechnologies');
	//				$oldTechnologies = $cht->fetchAll($cht->select()->where('id_kandidat = ?', $candidate->getid_kandidat()));
//
	//				// Deletes kandidat_technologie objects
	//				foreach ($oldTechnologies as $oTechnology) {
	//					$deleteOld = true;
//
	//					for ($i = 0 ; $i < count($newTechnologieIds) ; $i++) {
	//						// Relation still exists
	//						if ($oTechnology->id_technologie === $newTechnologieIds[$i]) {
	//							$deleteOld = false;
	//							unset($newTechnologieIds[$i]);
	//							break;
	//						}
	//					}
//
	//					// Removes object, that doesn't exist (after update)
	//					if($deleteOld) {
	//						$oTechnology->delete();
	//					}
	//				}
//
	//				// Creates objects kandidat_technologie
	//				foreach ($newTechnologieIds as $nTechnologyId) {
	//					$new = My_Model::get('CandidatesHasTechnologies')->createRow();
	//					$new->id_kandidat = $candidate->getid_kandidat();
	//					$new->id_technologie = $nTechnologyId;
	//					$new->save();
	//				}
//
	//				// Attachements
	//				// Deletes
	//				if ($deleteAttachments !== NULL) {
	//					foreach ($deleteAttachments as $dId) {
	//						$row = My_Model::get('Attachments')->getById($dId);
	//						$row->delete();
	//					}
	//				}
//
	//				// Creates
	//				if (!$form->attachments->receive()) {
	//					print "Error receiving the file";
	//				}
//
	//				// Reads location and creates blob
	//				$attsLocations = $form->attachments->getFileName();
//
	//				if (!is_array($attsLocations)) {
	//					$attsLocations = array($attsLocations);
	//				}
//
	//				foreach ($attsLocations as $location) {
	//					$attachmentBlob = file_get_contents($location);
//
	//					if (!empty($attachmentBlob)) {
//
	//						// Creates attachment object
	//						$attachment = My_Model::get('Attachments')->createRow();
	//						$attachment->priloha = $attachmentBlob;
	//						$attachment->nazev = array_pop(explode("/", $location));
	//						$attachment->save();
//
	//						// Creates kandidat_priloha object
	//						$connection = My_Model::get('CandidatesHasAttachments')->createRow();
	//						$connection->id_priloha = $attachment->getid_priloha();
	//						$connection->id_kandidat = $candidate->getid_kandidat();
	//						$connection->save();
	//					}
	//					// Deletes file from temp directory (is already in DB)
	//					unlink($location);
	//				}
//
	//				// Redirects
	//				$this->_helper->redirector->gotoRoute(array('controller' => 'candidate', 'action' => 'detail', 'id' => $candidate->getid_kandidat()), 'default', true);
	//			}
	//		}
	//		else if($this->_request->getPost('closeButton', false)) {
	//			if (!empty($candidateId)) {
	//				$this->_helper->redirector->gotoRoute(array('controller' => 'candidate', 'action' => 'detail', 'id' => $candidateId), 'default', true);
	//			}
	//			else {
	//				$this->_helper->redirector->gotoRoute(array('controller' => 'candidate', 'action' => 'index'), 'default', true);
	//			}
	//		}
	//		else if($this->_request->getPost('deleteButton', false)) {
	//			if (!empty($candidateId)) {
	//				My_Model::get('Candidates')->getById($candidateId)->delete();
	//			}
	//			$this->_helper->redirector->gotoRoute(array('controller' => 'candidate', 'action' => 'index'), 'default', true);
	//		}
	//	}
	//}
//
	//public function detailAction()
	//{
	//	$this->view->title = 'Detail of Candidate';
	//	$this->view->messages = $this->_helper->flashMessenger->getMessages();
//
	//	$messageForm = new MessageForm();
	//	$this->view->messageForm = $messageForm;
//
	//	$candidateId = $this->_request->getParam('id');
	//	if (!empty($candidateId)) {
	//		$candidate = My_Model::get('Candidates')->getById($candidateId);
//
//
	//		if ($candidate !== null) {
	//			$advancedInfoId = $candidate->getid_pokrocile_informace();
//
	//			if (!empty($advancedInfoId)) {
	//				$advancedInfo = My_Model::get('AdvancedInformations')->getById($advancedInfoId);
	//				$this->view->advancedInformations = $advancedInfo;
	//			}
	//		}
	//	}
//
	//	if ($candidate === null) {
	//		$this->_helper->flashMessenger->addMessage("Kandidát nebyl nalezen");
	//		$this->_helper->redirector->gotoRoute(array('controller' => 'candidate',
	//			'action' => 'index'),
	//		'default',
	//		true);
	//	}
	//	else {
	//		$this->view->candidate = $candidate;
//
	//		$avatar = $candidate->getFoto();
	//		if ($avatar !== NULL) {
	//			$base64 = base64_encode($avatar->getfoto());
	//			$this->view->candidateAvatarBase64 = $base64;
	//		}
	//		
	//		$available_tests = My_Model::get('Tests')->fetchAll();
	//		$this->view->available_tests = $available_tests;	
	//		
	//		$assigned_tests = $candidate->getAssignedTests();
	//		$this->view->assigned_tests = $assigned_tests;
//
	//		// Handles send message action
	//		if ($this->_request->isPost()) {
	//			$this->sendMessage($this->getParam('message'), $candidateId);
	//			$messageForm->message->setValue('');
	//		}
//
	//		// Direct chat
	//		$this->view->chatMessages = $this->loadDirectChatContent($candidateId);
	//	}
	//}
}