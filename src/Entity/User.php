<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $cupom = null;

    #[ORM\Column(length: 255)]
    private ?string $senha = null;

    #[ORM\Column(length: 255)]
    private ?string $cpf = null;

    #[ORM\Column(length: 255)]
    private ?string $pix = null;

    #[ORM\Column]
    private ?float $meuSaldo = null;

    #[ORM\Column(length: 255)]
    private ?string $elo = null;

    #[ORM\Column]
    private array $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCupom(): ?string
    {
        return $this->cupom;
    }

    public function setCupom(string $cupom): static
    {
        $this->cupom = $cupom;

        return $this;
    }

    public function getSenha(): ?string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): static
    {
        $this->senha = $senha;

        return $this;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): static
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function getPix(): ?string
    {
        return $this->pix;
    }

    public function setPix(string $pix): static
    {
        $this->pix = $pix;

        return $this;
    }

    public function getMeuSaldo(): ?float
    {
        return $this->meuSaldo;
    }

    public function setMeuSaldo(float $meuSaldo): static
    {
        $this->meuSaldo = $meuSaldo;

        return $this;
    }

    public function getElo(): ?string
    {
        return $this->elo;
    }

    public function setElo(string $elo): static
    {
        $this->elo = $elo;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }
}
