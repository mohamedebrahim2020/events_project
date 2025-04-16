<?php

	/**
	 * file
	 * @Contains Drupal\events_manager\Controller\EventController.
	 */

	namespace Drupal\events_manager\Controller;

	use Drupal\Core\Controller\ControllerBase;
	use Drupal\Core\Database\Database;
	use Drupal\Core\Link;
	use Drupal\Core\Url;

	/**
	 * Implement Event class operations.
	 */
	class EventController extends ControllerBase {
		/**
		 * @return array
		 */
		public function index() {
			$config = \Drupal::config('events_manager.settings'); // Replace with your actual config name
			$oldEvents = $config->get('show_past_events');
			$today = date('Y-m-d 00:00:00');
			//create table header
			$header_table = [
				'id' => $this->t('id'),
				'title' => $this->t('title'),
				'image' => $this->t('image'),
				'description' => $this->t('description'),
				'start_time' => $this->t('start time'),
				'end_time' => $this->t('end time'),
				'category' => $this->t('category'),
				'view' => $this->t('View'),
				'delete' => $this->t('Delete'),
				'edit' => $this->t('Edit'),
			];

			// get data from database
			$query = \Drupal::database()->select('events', 'm');
			$query->fields('m', ['id', 'title', 'image', 'description', 'start_time', 'end_time', 'category']);
			if (!$oldEvents) {
				$query->condition('end_time', $today, '>=');
			}
			$pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit($config->get('events_per_page'));
			$results = $pager->execute()->fetchAll();
			$rows = [];
			foreach ($results as $data) {
				$url_delete = Url::fromRoute('event.delete_form', ['event' => $data->id], []);
				$url_edit = Url::fromRoute('event.edit_form', ['event' => $data->id], []);
				$url_view = Url::fromRoute('event.show_form', ['event' => $data->id], []);
				$linkDelete = Link::fromTextAndUrl('Delete', $url_delete);
				$linkEdit = Link::fromTextAndUrl('Edit', $url_edit);
				$linkView = Link::fromTextAndUrl('View', $url_view);

				//get data
				$rows[] = [
					'id' => $data->id,
					'title' => $data->title,
					'image' => $data->image,
					'description' => $data->description,
					'start_time' => $data->start_time,
					'end_time' => $data->end_time,
					'category' => $data->category,
					'view' => $linkView,
					'delete' => $linkDelete,
					'edit' =>  $linkEdit,
				];
			}
			// render table
			$form['table'] = [
				'#type' => 'table',
				'#header' => $header_table,
				'#rows' => $rows,
				'#empty' => $this->t('No data found'),
			];
			return $form;
		}

		/**
		 * @param int $event
		 * @return string[]
		 */
		public function show(int $event) {
			$conn = Database::getConnection();

			$query = $conn->select('events', 'm')
				->condition('id', $event)
				->fields('m');
			$data = $query->execute()->fetchAssoc();
			$title = $data['title'];
			$image = $data['image'];
			$description = $data['description'];
			$startTime = $data['start_time'];
			$endTime = $data['end_time'];
			$category = $data['category'];

			return [
				'#type' => 'markup',
				'#markup' => "<h1>$title</h1><br>
                          <img src='$image' alt='$title' width='100' height='100' /> <br>
                          <p> description : $description</p>
                          <p>starts at : $startTime</p>
                          <p>ends at : $endTime</p>
                          <p>category : $category</p>"
			];
		}
	}