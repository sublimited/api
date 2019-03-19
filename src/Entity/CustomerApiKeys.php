<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerApiKeysRepository")
 */
class CustomerApiKeys
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=37, nullable=true)
     */
    private $api_key;

    /**
     * @ORM\Column(type="string", columnDefinition="enum", nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIssn(): ?string
    {
        return $this->issn;
    }

    public function setIssn(?string $issn): self
    {
        $this->issn = $issn;

        return $this;
    }


    public function getApiKey(): ?string
    {
        return $this->api_key;
    }

    public function setApiKey(?string $api_key): self
    {
        $this->api_key = $api_key;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->created_at;
    }

    public function setCreatedAt(?int $createdAt): self
    {
        $this->created_at = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?int
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?int $updatedAt): self
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?int
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?int $deletedAt): self
    {
        $this->deleted_at = $deletedAt;

        return $this;
    }

    public function getCustomer(): ?int
    {
        return $this->customer;
    }

    public function setCustomer(?int $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
