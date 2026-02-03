<?php

// src/Controller/CampaignGetDirectController.php
namespace App\Controller\Campaigns;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CampaignGetDirectController extends AbstractController
{
	#[Route('/campaign/get/direct/{campaignId}', name: 'app_campaigns_get_direct', methods: [
			'GET'
	])]
	public function index(int $campaignId): JsonResponse
	{
		$campaignsService = $this->container->get('App\Service\CampaignGetDirect');

		try
		{
			
			
			$campaign = $campaignsService->getCampaign($campaignId);

			return new JsonResponse([
					'status' => 'success',
					'data' => $campaign,
					'message' => 'Campaign data fetched from Yandex Direct API'
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
	public static function getSubscribedServices(): array
	{
		return array_merge(parent::getSubscribedServices(), [
				'App\Service\CampaignGetDirect' => 'App\Service\CampaignGetDirect'
		]);
	}
}

