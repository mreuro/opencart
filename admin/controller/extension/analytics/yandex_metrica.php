<?php
class ControllerExtensionAnalyticsYandexMetrica extends Controller {
    private $error = [];
    public function index() {
        $this->load->language('extension/analytics/yandex_metrica');
        $this->load->model('setting/setting');
        $data = [
            'heading_title' => $this->language->get('heading_title'),
            'text_edit' => $this->language->get('text_edit'),
            'text_enabled' => $this->language->get('text_enabled'),
            'entry_code' => $this->language->get('entry_code'),
            'text_disabled' => $this->language->get('text_disabled'),
            'text_signup' => $this->language->get('text_signup'),
            'entry_status' => $this->language->get('entry_status'),
            'button_save' => $this->language->get('button_save'),
            'button_cancel' => $this->language->get('button_cancel'),
            'breadcrumbs' => $this->breadcrumbs(),
            'action' => $this->url->link('extension/analytics/yandex_metrica', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], true),
            'cancel' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=analytics', true),
            'token' => $this->session->data['token'],
            'header' => $this->load->controller('common/header'),
            'column_left' => $this->load->controller('common/column_left'),
            'footer' => $this->load->controller('common/footer')
        ];
        $this->document->setTitle($data['heading_title']);

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('yandex_metrica', $this->request->post, $this->request->get['store_id']);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=analytics', true));
        }
        $data['error_warning'] = $this->error['warning'] ?: '';
        $data['error_code'] = $this->error['code'] ?: '';
        $data['yandex_metrica_code'] = $this->request->post['yandex_metrica_code'] ?: $this->model_setting_setting->getSettingValue('yandex_metrica_code', $this->request->get['store_id']);
        $data['yandex_metrica_status'] = $this->request->post['yandex_metrica_status'] ?: $this->model_setting_setting->getSettingValue('yandex_metrica_status', $this->request->get['store_id']);

        $this->response->setOutput($this->load->view('extension/analytics/yandex_metrica', $data));
    }

    private function breadcrumbs()
    {
        $result = [];
        $result[] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        ];
        $result[] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=analytics', true)
        ];
        $result[] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/analytics/yandex_metrica', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], true)
        ];
        return $result;
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/analytics/yandex_metrica')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (!$this->request->post['yandex_metrica_code']) {
            $this->error['code'] = $this->language->get('error_code');
        }
        return !$this->error;
    }
}