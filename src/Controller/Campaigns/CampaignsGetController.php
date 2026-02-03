<?php

// src/Controller/CampaignsGetController.php
namespace App\Controller\Campaigns;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CampaignsGetController extends AbstractController
{
	#[Route('/campaigns/get/all', name: 'app_campaigns_get_all', methods: [
			'GET'
	])]
	public function index(): JsonResponse
	{
		// Получаем сервис через контейнер
		$campaignsService = $this->container->get('App\Service\CampaignsGet');

		try
		{
			$campaigns = $campaignsService->getActiveCampaigns();

			return new JsonResponse([
					'status' => 'success',
					'data' => [
							'campaigns' => $campaigns,
							'count' => count($campaigns)
					],
					'message' => 'Data fetched from Yandex Direct API'
			]);
		}
		catch ( \Exception $e )
		{
			return new JsonResponse([
					'status' => 'error',
					'message' => $e->getMessage()
			], 500);
		}
	}

	// Разрешаем доступ к сервису через контейнер
	public static function getSubscribedServices(): array
	{
		return array_merge(parent::getSubscribedServices(), [
				'App\Service\CampaignsGet' => 'App\Service\CampaignsGet'
		]);
	}
}