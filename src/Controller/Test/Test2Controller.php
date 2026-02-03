<?php

namespace App\Controller\Test;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\ModuleProcess\Aion\Test2;

class Test2Controller extends AbstractController
{

	#[Route('/test2', name: 'app_test2', methods: [
			'GET'
	])]
	public function index(): Response
	{
		$Test2 = new Test2(null);

		$rows = $Test2->do_it();

		return $this->json([
				'status' => 'success',
				'data' => [
						'rows' => $rows
				],
				'message' => 'Data fetched '
		]);
	}
}
