<?php
class ControllerExtensionModuleStarterKit extends Controller {
	private $errors = [];

	public function index() {
		$this->document->addStyle('catalog/view/theme/default/stylesheet/starter_kit.css');
		$this->document->addScript('catalog/view/javascript/starter_kit.js');

		$data = [];

		$this->load->language('extension/module/starter_kit');

		return $this->load->view('extension/module/starter_kit', $data);
	}
}
