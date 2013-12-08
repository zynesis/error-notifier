<?php

App::uses('Component', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('ComponentCollection', 'Controller');
App::uses('AuthComponent', 'AuthComponent.Controller/Component');

App::import('Controller', 'App');

class NotifierComponent extends Component {

	public function __construct($request = null, $response = null)
	{
		parent::__construct($request, $response);
		Configure::load('ErrorNotifier.bootstrap' , 'default' , false);
    }

	public function email($message, $template) {
		$from = Configure::read('EmailNotifier.from');
		$to = Configure::read('EmailNotifier.to');
		$subject = Configure::read('EmailNotifier.subject');

		$Email = new CakeEmail();
		$Email->viewVars($message);
		try {
			//$Email->template($template, 'default');
			$Email->emailFormat('html');
			$Email->from($from);
			$Email->to($to);
			$Email->subject($subject);
			$Email->send();
		} catch (SocketException $e) {
			$this->log($e->getMessage(), 'mail_error');
		}
	}

	private function _postMessage() {
		$CakeRequest = new CakeRequest();
		$Collection = new ComponentCollection();
		$AuthComponent = new AuthComponent($Collection);

		$url = $CakeRequest->url;
		$data = json_encode($CakeRequest->data, JSON_PRETTY_PRINT);
		$params = json_encode($CakeRequest->params, JSON_PRETTY_PRINT);
		$userId = $AuthComponent->user('id');
		$date = date('Y-m-d H:i:s');
	
		$message = array(
			'url' => $CakeRequest->url,
			'data' => json_encode($CakeRequest->data, JSON_PRETTY_PRINT),
			'params' => json_encode($CakeRequest->params, JSON_PRETTY_PRINT),
			'userId' => $AuthComponent->user('id'),
			'date' => date('Y-m-d H:i:s')
		);

		$this->email($message, 'post_message');
	}

	private function _errorMessage($description, $file, $line) {
		$message = array(
			'description' => $description,
			'file' => $file,
			'line' => $line
		);

		$this->email($message, 'error_message');
	}

	public static function handleError($code, $description, $file = null, $line = null, $context = null) {
		$Collection = new ComponentCollection();
		$Notifier = new NotifierComponent($Collection);
		$Notifier->_errorMessage($description, $file, $line);
		return ErrorHandler::handleError($code, $description, $file, $line, $context);
	}
}