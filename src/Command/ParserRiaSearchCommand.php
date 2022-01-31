<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Realty;
use App\Repository\RealtyRepository;
use App\Service\Contract\RealtyServiceInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class ParserRiaSearchCommand extends Command
{
    private ClientInterface $client;

    private RealtyServiceInterface $realtyService;

    private RealtyRepository $realtyRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        ClientInterface $client,
        RealtyServiceInterface $realtyService,
        RealtyRepository $realtyRepository,
        EntityManagerInterface $entityManager,
    ) {
        $this->client = $client;
        $this->realtyService = $realtyService;
        $this->realtyRepository = $realtyRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('parser:ria:search');
        $this->addArgument('search_params', InputArgument::REQUIRED);
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $searchUrl = str_replace(
            '{PARAMS}',
            $input->getArgument('search_params'),
            $_SERVER['RIA_SEARCH_URL'] ?? ''
        );

        $response = $this->client->request('GET', $searchUrl);
        $data = json_decode($response->getBody()->getContents(), true);

        foreach ($data['items'] ?? [] as $id) {
            $realty = $this->realtyRepository->find($id);
            if (!$realty) {
                $realtyUrl = str_replace(
                    '{ID}',
                    (string)$id,
                    $_SERVER['RIA_REALTY_URL'] ?? ''
                );
                $response = $this->client->request('GET', $realtyUrl);

                $realty = Realty::buildFromResponse($response);

                var_dump($realty);exit;
                $this->entityManager->persist($realty);
            }
        }

        $this->entityManager->flush();

        return 0;
    }
}
