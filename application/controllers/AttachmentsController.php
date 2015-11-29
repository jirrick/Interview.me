<?php

define('APACHE_MIME_TYPES_URL','http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types');

class AttachmentsController extends Zend_Controller_Action {
	
	

	public function indexAction()
	{
		$attachmentId = $this->_request->getParam('id');
		
		// Disables view script call
		$this->_helper->viewRenderer->setNoRender(true);

		// Disables layout to action mapping
		$this->_helper->layout->disableLayout();

		// Loads attachment
		$att = My_Model::get('Attachments')->getById($attachmentId);

		// Reads attachment extension
		$fileExtenstion = array_pop(explode(".", $att->getnazev()));

		// Loads mime types
		$mimeTypes = $this->mimeTypes();

		// Sets Content type
		$this->_response->setHeader('Content-Type', $mimeTypes[$fileExtenstion]);

		// Prints mediablob
		echo $att->priloha;
	}
	

	private function mimeTypes()
	{
		$s=array();
		foreach(@explode("\n",@file_get_contents(APACHE_MIME_TYPES_URL))as $x)
			if(isset($x[0])&&$x[0]!=='#'&&preg_match_all('#([^\s]+)#',$x,$out)&&isset($out[1])&&($c=count($out[1]))>1)
				for($i=1;$i<$c;$i++)
					$s[$out[1][$i]]=$out[1][0];
				return $s;

		return NULL;
	}
}
