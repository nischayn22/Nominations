<?php

class ApiApprove extends ApiBase {

    public function execute() {
        global $wgScript, $wgUser;

        $pageName = $this->getMain()->getVal('title');
        $pageName = str_replace( ' ', '_', $pageName );

	if (!$wgUser->isAllowed('approve-nominations')) {
    	   header("Location: " . $wgScript . '/' . $pageName );
    	   die();
	}

        $approve_action = $this->getMain()->getVal('approveaction');

	$title = Title::newFromText( $this->getMain()->getVal('create_title') );
	$article = new Article( $title );
	$article->doEdit( $this->getMain()->getVal('create_text'), "Page created by admin because nomination decision is $approve_action" );

	$oldTitle = Title::newFromText($pageName);
	$pageObj = WikiPage::factory( $oldTitle );
	$pageObj->doDeleteArticleReal( $approve_action, false, 0, true );

	$this->sendMails();
	header("Location: " . $wgScript . '/' . $this->getMain()->getVal('create_title') );
    	die();
    }


    public function sendMails() {
      global $nominationsSenderEmailAddress;

      $mailHeaders = "From: $nominationsSenderEmailAddress \r\n";
      $mailHeaders .= 'MIME-Version: 1.0' . "\r\n";
      $mailHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

      if($this->getMain()->getVal('recipients1') != '' && $this->getMain()->getVal('subject1') != '' && $this->getMain()->getVal('message1') != '') 
	   mail(
	      $this->getMain()->getVal('recipients1'),
	      $this->getMain()->getVal('subject1'),
	      $this->getMain()->getVal('message1'),
	      $mailHeaders
	   );
      if($this->getMain()->getVal('recipients2') != '' && $this->getMain()->getVal('subject2') != '' && $this->getMain()->getVal('message2') != '') 
	   mail(
	      $this->getMain()->getVal('recipients2'),
	      $this->getMain()->getVal('subject2'),
	      $this->getMain()->getVal('message2'),
	      $mailHeaders
	   );
      if($this->getMain()->getVal('recipients3') != '' && $this->getMain()->getVal('subject3') != '' && $this->getMain()->getVal('message3') != '') 
	   mail(
	      $this->getMain()->getVal('recipients3'),
	      $this->getMain()->getVal('subject3'),
	      $this->getMain()->getVal('message3'),
	      $mailHeaders
	   );
      if($this->getMain()->getVal('recipients4') != '' && $this->getMain()->getVal('subject4') != '' && $this->getMain()->getVal('message4') != '') 
	   mail(
	      $this->getMain()->getVal('recipients4'),
	      $this->getMain()->getVal('subject4'),
	      $this->getMain()->getVal('message4'),
	      $mailHeaders
	   );
      if($this->getMain()->getVal('recipients5') != '' && $this->getMain()->getVal('subject5') != '' && $this->getMain()->getVal('message5') != '') 
	   mail(
	      $this->getMain()->getVal('recipients5'),
	      $this->getMain()->getVal('subject5'),
	      $this->getMain()->getVal('message5'),
	      $mailHeaders
	   );
      if($this->getMain()->getVal('recipients6') != '' && $this->getMain()->getVal('subject6') != '' && $this->getMain()->getVal('message6') != '') 
	   mail(
	      $this->getMain()->getVal('recipients6'),
	      $this->getMain()->getVal('subject6'),
	      $this->getMain()->getVal('message6'),
	      $mailHeaders
	   );
    }

    public function getDescription() {
         return 'Api to approve or reject a proposal.';
    }
 
    public function getAllowedParams() {
        return array_merge( (array)parent::getAllowedParams(), array(
            'title' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => true
            ),
            'approveaction' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => true
            ),
            'recipients1' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'subject1' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'message1' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'recipients2' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'subject2' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'message2' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'recipients3' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'subject3' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'message3' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'recipients4' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'subject4' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'message4' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'recipients5' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'subject5' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'message5' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'recipients6' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'subject6' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
            'message6' => array (
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => false
            ),
        ));
    }
 
    public function getParamDescription() {
        return array_merge( parent::getParamDescription(), array(
            'title' => 'The title of the proposal page',
	    'approveaction' => 'The desired action - approve or reject',
        ) );
    }
 
}