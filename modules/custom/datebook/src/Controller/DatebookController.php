<?php

namespace Drupal\datebook\Controller;

use Drupal\Core\Controller\ControllerBase;

class DatebookController extends ControllerBase
{
	public function content()
	{
		return array(
			'#theme' => 'datebook',
			'#controller' => $this->t('controller var'),
		);
	}

	public function get()
	{
		$start = $_REQUEST['from'] / 1000;
		$start = date('Y-m-d H:i:s', $start);
		$end = $_REQUEST['to'] / 1000;
		$end = date('Y-m-d H:i:s', $end);

		$data = array();
		$data[] = array(
			'id' => '0',
			'title' => 'Practice',
			'description' => 'Full team training.',
			'class' => 'event-important',
			'start' => date('U') * 1000,
			'end' => (date('U') + 2 * 60 * 60) * 1000,
		);

		echo json_encode(array('success' => 1, 'result' => $data));
		die;
	}

	public function save()
	{
		$start = new \DateTime();
		$start->add('10 hours');
		$start->add('2 days');
		$end = new \DateTime();
		$end->add('12 hours');
		$end->add('2 days');
		$fields = array(
			'uid' => '1',
			'title' => 'Spring Training',
			'description' => '1-on-1 spring training with Sally Davis',
			'start' => $start->format('Y-m-d H:i:s'),
			'end' => $end->format('Y-m-d H:i:s'),
		);
		db_insert('datebook')
			->fields($fields)
			->execute();

		die;
	}
}