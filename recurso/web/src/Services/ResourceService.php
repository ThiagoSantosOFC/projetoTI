<?php

namespace App\Services;

use App\Repositories\ResourceRepository;
use App\Models\Resource;
use App\Lib\Validator;

class ResourceService
{
    private ResourceRepository $repository;

    public function __construct()
    {
        $this->repository = new ResourceRepository();
    }

    public function getAllResources(?string $tipo = null): array
    {
        $resources = $this->repository->findAll();

        if ($tipo !== null) {
            $resources = array_values(
                array_filter($resources, fn(Resource $r) => $r->getTipo() === $tipo)
            );
        }

        return $resources;
    }

    public function getResource(string $id): ?Resource
    {
        return $this->repository->findById($id);
    }

    public function createResource(string $id, string $nome, string $tipo, string $unidade): bool
    {
        if (!Validator::validateId($id)) {
            return false;
        }
        if (!Validator::validateTipo($tipo)) {
            return false;
        }

        $nome = Validator::sanitize($nome);
        $unidade = Validator::sanitize($unidade);

        return $this->repository->create($id, $nome, $tipo, $unidade);
    }

    public function updateValue(string $id, string $valor): bool
    {
        $valor = Validator::sanitize($valor);
        return $this->repository->updateValue($id, $valor);
    }

    public function updateConfig(string $id, array $data): bool
    {
        $allowed = ['nome', 'tipo', 'unidade'];
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed, true)) {
                $sanitized[$key] = Validator::sanitize($value);
            }
        }

        if (isset($sanitized['tipo']) && !Validator::validateTipo($sanitized['tipo'])) {
            return false;
        }

        return $this->repository->updateConfig($id, $sanitized);
    }

    public function deleteResource(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getHistorico(string $id, int $limite = 20): array
    {
        return $this->repository->getHistorico($id, $limite);
    }

    public function clearHistory(string $id): bool
    {
        return $this->repository->clearLog($id);
    }

    public function executeCommand(string $id, string $comando): bool
    {
        $resource = $this->repository->findById($id);
        if ($resource === null) {
            return false;
        }
        if ($resource->getTipo() !== 'actuator') {
            return false;
        }

        $comando = Validator::sanitize($comando);
        return $this->repository->updateValue($id, $comando);
    }
}
