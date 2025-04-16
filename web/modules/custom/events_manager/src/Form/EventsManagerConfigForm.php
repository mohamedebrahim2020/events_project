<?php
namespace Drupal\events_manager\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class EventsManagerConfigForm extends ConfigFormBase {
protected function getEditableConfigNames() {
return ['events_manager.settings'];
}

public function getFormId() {
return 'events_manager_settings';
}

public function buildForm(array $form, FormStateInterface $form_state) {
$config = $this->config('events_manager.settings');

$form['show_past_events'] = [
'#type' => 'checkbox',
'#title' => $this->t('Show past events'),
'#default_value' => $config->get('show_past_events'),
];

$form['events_per_page'] = [
'#type' => 'number',
'#title' => $this->t('Number of events per page'),
'#default_value' => $config->get('events_per_page'),
'#min' => 1,
'#max' => 100,
];

return parent::buildForm($form, $form_state);
}

public function submitForm(array &$form, FormStateInterface $form_state) {
$this->config('events_manager.settings')
->set('show_past_events', $form_state->getValue('show_past_events'))
->set('events_per_page', $form_state->getValue('events_per_page'))
->save();

// Log the configuration change in the custom table.
\Drupal::database()->insert('config_log')
->fields([
	'uid' => \Drupal::currentUser()->id(),
	'message' => 'Configuration updated: ' . json_encode($form_state->getValues()),
	'created' =>  date('Y-m-d H:i:s', \Drupal::time()->getCurrentTime()),
	])
->execute(); // Execute the insert query.
parent::submitForm($form, $form_state);
}
}
