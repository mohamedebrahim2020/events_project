<?php

	namespace Drupal\events_manager\Form;

	use Drupal\Core\Form\FormBase;
	use Drupal\Core\Form\FormStateInterface;
	use Drupal\Core\Database\Database;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\DependencyInjection\ContainerInterface;

	/**
	 * Provides a form for creating or editing an event.
	 */
	class EventForm extends FormBase {

		/**
		 * Current event ID for editing (if applicable).
		 *
		 * @var int|null
		 */
		protected $eventId;

		/**
		 * Request stack to access route parameters.
		 */
		protected $request;

		public function __construct(RequestStack $request) {
			$this->request = $request;
		}

		public static function create(ContainerInterface $container) {
			return new static(
				$container->get('request_stack')
			);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getFormId() {
			return 'my_module_event_form';
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildForm(array $form, FormStateInterface $form_state) {
			$id = $this->request->getCurrentRequest()->get('event');
			$this->eventId = $id;

			$defaults = [
				'title' => '',
				'image' => '',
				'description' => '',
				'start_time' => NULL,
				'end_time' => NULL,
				'category' => '',
			];

			if ($id) {
				$connection = Database::getConnection();
				$record = $connection->select('events', 'e')
					->fields('e')
					->condition('id', $id)
					->execute()
					->fetchAssoc();

				if ($record) {
					$defaults = [
						'title' => $record['title'],
						'image' => $record['image'],
						'description' => $record['description'],
						'start_time' => \Drupal\Core\Datetime\DrupalDateTime::createFromTimestamp(strtotime($record['start_time'])),
						'end_time' => \Drupal\Core\Datetime\DrupalDateTime::createFromTimestamp(strtotime($record['end_time'])),
						'category' => $record['category'],
					];
				}
			}

			$form['title'] = [
				'#type' => 'textfield',
				'#title' => $this->t('Title'),
				'#required' => TRUE,
				'#default_value' => $defaults['title'],
			];

			$form['image'] = [
				'#type' => 'textfield',
				'#title' => $this->t('Image URI'),
				'#default_value' => $defaults['image'],
			];

			$form['description'] = [
				'#type' => 'textarea',
				'#title' => $this->t('Description'),
				'#default_value' => $defaults['description'],
			];

			$form['start_time'] = [
				'#type' => 'datetime',
				'#title' => $this->t('Start Time'),
				'#required' => TRUE,
				'#default_value' => $defaults['start_time'],
			];

			$form['end_time'] = [
				'#type' => 'datetime',
				'#title' => $this->t('End Time'),
				'#required' => TRUE,
				'#default_value' => $defaults['end_time'],
			];

			$form['category'] = [
				'#type' => 'textfield',
				'#title' => $this->t('Category'),
				'#required' => TRUE,
				'#default_value' => $defaults['category'],
			];

			$form['actions']['submit'] = [
				'#type' => 'submit',
				'#value' => $id ? $this->t('Update Event') : $this->t('Save Event'),
			];

			return $form;
		}

		/**
		 * {@inheritdoc}
		 */
		public function validateForm(array &$form, FormStateInterface $form_state) {
			$start = $form_state->getValue('start_time')->getTimestamp();
			$end = $form_state->getValue('end_time')->getTimestamp();

			if ($end < $start) {
				$form_state->setErrorByName('end_time', $this->t('End time must be after start time.'));
			}
		}

		/**
		 * {@inheritdoc}
		 */
		public function submitForm(array &$form, FormStateInterface $form_state) {
			$connection = Database::getConnection();

			$data = [
				'title' => $form_state->getValue('title'),
				'image' => $form_state->getValue('image'),
				'description' => $form_state->getValue('description'),
				'start_time' => date('Y-m-d H:i:s', $form_state->getValue('start_time')->getTimestamp()),
				'end_time' => date('Y-m-d H:i:s', $form_state->getValue('end_time')->getTimestamp()),
				'category' => $form_state->getValue('category'),
			];

			if ($this->eventId) {
				$connection->update('events')
					->fields($data)
					->condition('id', $this->eventId)
					->execute();

				$this->messenger()->addMessage($this->t('Event updated.'));
			} else {
				$connection->insert('events')
					->fields($data)
					->execute();

				$this->messenger()->addMessage($this->t('Event created.'));
			}
			$form_state->setRedirect('event.list');
		}

	}
