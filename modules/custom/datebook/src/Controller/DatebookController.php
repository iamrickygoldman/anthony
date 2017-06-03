<?php

namespace Drupal\datebook\Controller;

use Drupal\Core\Controller\ControllerBase;

class DatebookController extends ControllerBase
{
	public function content()
	{
		$user = \Drupal::currentUser();
		$canSave = $user->hasPermission('save calendar');
		return array(
			'#theme' => 'datebook',
			'#canSave' => $canSave,
		);
	}

	public function get()
	{
		/*
		$start = $_REQUEST['from'] / 1000;
		$start = date('Y-m-d H:i:s', $start);
		$end = $_REQUEST['to'] / 1000;
		$end = date('Y-m-d H:i:s', $end);
		*/

		$fields = array(
			'did',
			'title',
			'description',
			'start',
			'end',
		);
		$data = db_select('datebook', 'e')
  			->fields('e', $fields)
  			->execute()
  			->fetchAll();

  		foreach ($data as $key => $item) {
  			$item->class = 'event-important';
  			$item->id = $item->did;
  			$item->url = $item->description;

  			$start = \DateTime::createFromFormat('Y-m-d H:i:s', $item->start, new \DateTimeZone('GMT'));
  			$start->setTimezone('America/Los_Angeles');
  			$item->start = $start->getTimeStamp() * 1000;
			$end = \DateTime::createFromFormat('Y-m-d H:i:s', $item->end, new \DateTimeZone('GMT'));
			$end->setTimezone('America/Los_Angeles');
			$item->end = $end->getTimeStamp() * 1000;

  			$data[$key] = (array)$item;
  		}

		echo json_encode(array('success' => 1, 'result' => $data));
		die;
	}

	public function save()
	{
		$start = \DateTime::createFromFormat('m/d/Y h:i A', $_POST['date_start'], new \DateTimeZone('America/Los_Angeles'));
		$start->setTimezone(new \DateTimeZone('GMT'));
		$end = \DateTime::createFromFormat('m/d/Y h:i A', $_POST['date_end'], new \DateTimeZone('America/Los_Angeles'));
		$end->setTimezone(new \DateTimeZone('GMT'));
		$fields = array(
			'uid' => '3',
			'title' => $_POST['date_title'],
			'description' => $_POST['date_description'],
			'start' => $start->format('Y-m-d H:i:s'),
			'end' => $end->format('Y-m-d H:i:s'),
		);
		
		db_insert('datebook')
			->fields($fields)
			->execute();

		return $this->redirect('datebook.content');
	}
}