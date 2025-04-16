<?php
	/**
	 * @file
	 * @Contains Drupal\events_manager\Form\EventDeleteForm.
	 */
	namespace Drupal\events_manager\Form;

	use Drupal\Core\Form\FormStateInterface;
	use Drupal\Core\Form\ConfirmFormBase;
	use Drupal\Core\Url;

	/**
	 * Class EventDeleteForm
	 * @package Drupal\events_manager\Form
	 */
	class EventDeleteForm extends ConfirmFormBase {
		public $event;

		/**
		 * {@inheritdoc}
		 */
		public function getFormId() {
			return 'delete_form';
		}

		public function getQuestion() {
			return $this->t('Delete data');
		}

		public function getCancelUrl() {
			return new Url('event.list');
		}

		public function getDescription() {
			return $this->t('Do you want to delete data number %event ?', ['%event' => $this->event]);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getConfirmText() {
			return $this->t('Delete it!');
		}

		/**
		 * {@inheritdoc}
		 */
		public function getCancelText() {
			return $this->t('Cancel');
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildForm(array $form, FormStateInterface $form_state, $event = NULL) {

			$this->event = $event;
			return parent::buildForm($form, $form_state);
		}

		/**
		 * {@inheritdoc}
		 */
		public function validateForm(array &$form, FormStateInterface $form_state) {
			parent::validateForm($form, $form_state);
		}

		/**
		 * {@inheritdoc}
		 */
		public function submitForm(array &$form, FormStateInterface $form_state) {
			$query = \Drupal::database();
			$query->delete('events')
				->condition('id', $this->event)
				->execute();
			\Drupal::messenger()->addStatus('Successfully deleted.');
			$form_state->setRedirect('event.list');
		}
	}