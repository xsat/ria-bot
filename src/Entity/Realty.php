<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RealtyRepository;
use Doctrine\ORM\Mapping as ORM;
use Psr\Http\Message\ResponseInterface;

#[ORM\Entity(repositoryClass: RealtyRepository::class)]
class Realty
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'json')]
    private $data = [];

    public static function buildFromResponse(ResponseInterface $response): self
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $realty = new self();
        $realty->setId($data['realty_id'] ?? null);
        $realty->setData($data ?? []);

        return $realty;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
