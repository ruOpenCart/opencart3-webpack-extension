<?php
class ControllerExtensionModuleStarterKit extends Controller {
	private $errors = [];
	private $user_token;
	private $version = '3.0.0.0';
	private $author = 'ruOpenCart';

	public function __construct($registry) {
		parent::__construct($registry);

		$this->user_token = $this->session->data['user_token'];
	}

	public function install() {}

	public function uninstall() {}

	public function index() {
		$this->document->addScript('view/javascript/starter_kit.js');
		$this->document->addStyle('view/stylesheet/starter_kit.css');

		$this->load->language('extension/module/starter_kit');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		// Edit settings
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('module_starter_kit', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if (isset($this->request->post['apply']) && $this->request->post['apply']) {
				$this->response->redirect($this->getFullLink([ 'module' => 'extension/module/starter_kit']));
			}

			$this->response->redirect($this->getFullLink([ 'module' => 'marketplace/extension', 'params' => ['type' => 'module']]));
		}

		// Info
		$data = [
			'data_author' => $this->author,
			'data_version' => $this->version,
		];

		//Errors
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['warning'])) {
			$data['warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$data['warning'] = '';
		}

		// Settings
		$data['module_starter_kit_status'] = isset($this->request->post['module_starter_kit_status'])
			? $this->request->post['module_starter_kit_status']
			: $this->config->get('module_starter_kit_status');

		// Breadcrumbs
		$data['breadcrumbs'] = $this->getBreadcrumbs('extension/module/starter_kit');

		// Urls
		$data['url_action'] = $this->getFullLink([ 'module' => 'extension/module/starter_kit']);
		$data['url_cancel'] = $this->getFullLink([ 'module' => 'marketplace/extension', 'params' => ['type' => 'module']]);

		// Templates
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/starter_kit', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/starter_kit')) {
			$this->errors['warning'] = $this->language->get('error_permission');
		}

		return !$this->errors;
	}

	private function getBreadcrumbs($extension) {
		return [
			[
				'text' => $this->language->get('text_home'),
				'href' => $this->getFullLink(['module' => 'common/dashboard'])
			],
			[
				'text' => $this->language->get('text_extension'),
				'href' => $this->getFullLink([ 'module' => 'marketplace/extension', 'params' => ['type' => 'module']])
			],
			[
				'text' => $this->language->get('heading_title'),
				'href' => $this->getFullLink(['module' => $extension])
			]
		];
	}

	private function getFullLink($data = []) {
		$url = '';
		if (isset($data['params'])) {
			foreach ($data['params'] as $key => $value) {
				$url .= '&' . $key . '=' . $value;
			}
		}
		$url .= '&user_token=' . $this->user_token;

		return $this->url->link($data['module'], $url, true);
	}
}
