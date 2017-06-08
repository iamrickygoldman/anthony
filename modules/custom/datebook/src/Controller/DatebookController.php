<?php

namespace Drupal\datebook\Controller;

use Drupal\Core\Controller\ControllerBase;

class DatebookController extends ControllerBase
{
	public function content()
	{
		$user = \Drupal::currentUser();
		$canSave = $user->hasPermission('save calendar');
		$canDelete = $user->hasPermission('delete calendar');
		$canStudentSave = (!$canSave && $user->hasPermission('student save calendar'));
		$canStudentDelete = (!$canDelete && $user->hasPermission('student delete calendar'));

		$query = db_select('user__roles', 'e');
  		$query->join('users_field_data', 'u', 'e.entity_id = u.uid');
  		$students = $query
  			->fields('e', array('entity_id'))
  			->fields('u', array('name'))
  			->condition('roles_target_id', 'student', '=')
  			->execute()
  			->fetchAll();

		return array(
			'#theme' => 'datebook',
			'#canSave' => $canSave,
			'#canDelete' => $canDelete,
			'#canStudentSave' => $canStudentSave,
			'#canStudentDelete' => $canStudentDelete,
			'#students' => $students,
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
			'location',
			'start',
			'end',
			'type',
			'sid',
		);
		$data = db_select('datebook', 'e')
  			->fields('e', $fields)
  			->execute()
  			->fetchAll();

  		foreach ($data as $key => $item) {
  			switch ($item->type) {
  				case 'open':
  					$item->class = 'event-info';
  					break;
  				case 'closed':
  					$item->class = 'event-important';
  					break;
  			}
  			$item->id = $item->did;
  			$item->url = 'javascript:void(0);';

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
		$end = \DateTime::createFromFormat('m/d/Y h:i A', $_POST['date_end'], new \DateTimeZone('America/Los_Angeles'));

		if (!$start || !$end || !$_POST['date_title']) {
			return $this->redirect('datebook.content');
		}

		$start->setTimezone(new \DateTimeZone('GMT'));
		$end->setTimezone(new \DateTimeZone('GMT'));

		$fields = array(
			'uid' => '3',
			'title' => $_POST['date_title'],
			'description' => $_POST['date_description'],
			'location' => $_POST['date_location'],
			'start' => $start->format('Y-m-d H:i:s'),
			'end' => $end->format('Y-m-d H:i:s'),
			'type' => $_POST['date_type'],
		);
		if ($_POST['date_student']) {
			$fields['sid'] = $_POST['date_student'];
		}
		
		if ($_POST['date_id']) {
			db_update('datebook')
				->condition('did', $_POST['date_id'], '=')
				->fields($fields)
				->execute();
		}
		else {
			db_insert('datebook')
				->fields($fields)
				->execute();
		}

		return $this->redirect('datebook.content');
	}

	public function delete($id)
	{
		db_delete('datebook')
			->condition('did', $id, '=')
			->execute();

		return $this->redirect('datebook.content');
	}
}