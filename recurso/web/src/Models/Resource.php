<?php

namespace App\Models;

class Resource
{
    private ?string $id = null;
    private ?string $nome = null;
    private ?string $tipo = null;
    private ?string $valor = null;
    private ?string $unidade = null;
    private ?string $hora = null;
    private ?string $log = null;

    public static function fromArray(array $data): self
    {
        $resource = new self();
        $resource->id      = $data['id'] ?? null;
        $resource->nome    = $data['nome'] ?? null;
        $resource->tipo    = $data['tipo'] ?? null;
        $resource->valor   = $data['valor'] ?? null;
        $resource->unidade = $data['unidade'] ?? null;
        $resource->hora    = $data['hora'] ?? null;
        $resource->log     = $data['log'] ?? null;
        return $resource;
    }

    public function toArray(): array
    {
        return [
            'id'      => $this->id,
            'nome'    => $this->nome,
            'tipo'    => $this->tipo,
            'valor'   => $this->valor,
            'unidade' => $this->unidade,
            'hora'    => $this->hora,
            'log'     => $this->log,
        ];
    }

    // Getters
    public function getId(): ?string     { return $this->id; }
    public function getNome(): ?string   { return $this->nome; }
    public function getTipo(): ?string   { return $this->tipo; }
    public function getValor(): ?string  { return $this->valor; }
    public function getUnidade(): ?string{ return $this->unidade; }
    public function getHora(): ?string   { return $this->hora; }
    public function getLog(): ?string    { return $this->log; }

    // Setters
    public function setId(?string $val): void     { $this->id = $val; }
    public function setNome(?string $val): void   { $this->nome = $val; }
    public function setTipo(?string $val): void   { $this->tipo = $val; }
    public function setValor(?string $val): void  { $this->valor = $val; }
    public function setUnidade(?string $val): void{ $this->unidade = $val; }
    public function setHora(?string $val): void   { $this->hora = $val; }
    public function setLog(?string $val): void    { $this->log = $val; }
}
