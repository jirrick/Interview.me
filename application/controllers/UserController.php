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

	public function detailAction()
	{
		$this->view->title = 'Detail of User';
		$this->view->messages = $this->_helper->flashMessenger->getMessages();

		$userId = $this->_request->getParam('id');
		if (!empty($userId)) {
			$user = My_Model::get('Users')->getById($userId);

			if ($user !== NULL) {
				$this->view->user = $user;

				$avatar = $user->getFoto();
				if ($avatar !== NULL) {
					$base64 = base64_encode($avatar->getfoto());
					$this->view->avatarBase64 = $base64;
				}
			}
		}
	}
	
	public function editAction()
	{
		$this->view->title = 'Edit user profile';
		$this->view->messages = $this->_helper->flashMessenger->getMessages();

		$form = new UserForm();
		$this->view->form = $form;

		$userId = $this->_request->getParam('id');
		if (!empty($userId)) {
			$user = My_Model::get('Users')->getById($userId);

			if ($user !== NULL) {
				$form->setDefaults($user->get_data());

				$avatar = $user->getFoto();
				if ($avatar !== NULL) {
					$base64 = base64_encode($avatar->getfoto());
					$form->avatar->setAttrib('src', "data:image/gif;base64," . $base64);	
				}
			}
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
					
					// Adds photo id
					if (!empty($photo)) {
						$formValues['id_fotografie'] = $photo->getid_foto();
					}

					if ($user === NULL) {
						$user = My_Model::get('Users')->createRow();
					}

					$user->updateFromArray($formValues);
					$this->_helper->redirector->gotoRoute(array('controller' => 'user', 'action' => 'detail', 'id' => $userId), 'default', true);
				}
			}
			else if($this->_request->getPost('closeButton', false)) {
				if (!empty($userId)) {
					$this->_helper->redirector->gotoRoute(array('controller' => 'user', 'action' => 'detail', 'id' => $userId), 'default', true);
				}
				else {
					$this->_helper->redirector->gotoRoute(array('controller' => 'user', 'action' => 'index'), 'default', true);
				}
			}
			else if($this->_request->getPost('deleteButton', false)) {
				if (!empty($userId)) {
					My_Model::get('Users')->getById($userId)->delete();
				}
				$this->_helper->redirector->gotoRoute(array('controller' => 'user', 'action' => 'index'), 'default', true);
			}
		}
	}

}