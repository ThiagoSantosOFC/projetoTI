<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\ResourceService;
use App\Lib\Validator;

class ResourceController
{
    private ResourceService $resourceService;

    public function __construct()
    {
        $this->resourceService = new ResourceService();
    }

    /**
     * GET /api/resources[?tipo=sensor|actuator]
     */
    public function index(Request $req): Response
    {
        $tipo = $req->getQueryParam('tipo', null);

        if ($tipo !== null && !Validator::validateTipo($tipo)) {
            return (new Response())->json(['error' => 'Tipo inválido. Use sensor ou actuator.'], 400);
        }

        $resources = $this->resourceService->getAllResources($tipo);
        $data = array_map(fn($r) => $r->toArray(), $resources);

        return (new Response())->json($data);
    }

    /**
     * GET /api/resources/{id}
     */
    public function show(Request $req, array $params): Response
    {
        $id = $params['id'] ?? '';
        $resource = $this->resourceService->getResource($id);

        if ($resource === null) {
            return (new Response())->json(['error' => 'Recurso não encontrado.'], 404);
        }

        return (new Response())->json($resource->toArray());
    }

    /**
     * POST /api/resources
     */
    public function store(Request $req): Response
    {
        $id      = Validator::sanitize($req->getParam('id', ''));
        $nome    = Validator::sanitize($req->getParam('nome', ''));
        $tipo    = Validator::sanitize($req->getParam('tipo', ''));
        $unidade = Validator::sanitize($req->getParam('unidade', ''));

        $errors = [];

        if ($id === '') {
            $errors[] = 'ID é obrigatório.';
        } elseif (!Validator::validateId($id)) {
            $errors[] = 'ID deve conter apenas letras, números, _ ou -.';
        }

        if ($nome === '') {
            $errors[] = 'Nome é obrigatório.';
        }

        if ($tipo === '') {
            $errors[] = 'Tipo é obrigatório.';
        } elseif (!Validator::validateTipo($tipo)) {
            $errors[] = 'Tipo deve ser sensor ou actuator.';
        }

        if ($unidade === '') {
            $errors[] = 'Unidade é obrigatória.';
        }

        if ($errors !== []) {
            return (new Response())->json(['error' => implode(' ', $errors)], 400);
        }

        $created = $this->resourceService->createResource($id, $nome, $tipo, $unidade);

        if (!$created) {
            return (new Response())->json(['error' => 'Recurso já existe ou não foi possível criar.'], 409);
        }

        return (new Response())->json([
            'success' => true,
            'message' => 'Recurso criado com sucesso.',
            'id'      => $id,
        ], 201);
    }

    /**
     * PUT /api/resources/{id} — replace resource config
     */
    public function update(Request $req, array $params): Response
    {
        $id = $params['id'] ?? '';

        if ($this->resourceService->getResource($id) === null) {
            return (new Response())->json(['error' => 'Recurso não encontrado.'], 404);
        }

        $data = [];

        $nome = $req->getParam('nome');
        if ($nome !== null) {
            $data['nome'] = Validator::sanitize($nome);
        }

        $tipo = $req->getParam('tipo');
        if ($tipo !== null) {
            $data['tipo'] = Validator::sanitize($tipo);
        }

        $unidade = $req->getParam('unidade');
        if ($unidade !== null) {
            $data['unidade'] = Validator::sanitize($unidade);
        }

        if ($data === []) {
            return (new Response())->json(['error' => 'Nenhum dado para atualizar.'], 400);
        }

        $updated = $this->resourceService->updateConfig($id, $data);

        if (!$updated) {
            return (new Response())->json(['error' => 'Não foi possível atualizar.'], 500);
        }

        return (new Response())->json([
            'success' => true,
            'message' => 'Recurso atualizado com sucesso.',
        ]);
    }

    /**
     * PATCH /api/resources/{id} — update value (used by SBC/MCU). Logs to log.txt.
     */
    public function patch(Request $req, array $params): Response
    {
        $id    = $params['id'] ?? '';
        $valor = Validator::sanitize($req->getParam('valor', ''));

        if ($valor === '') {
            return (new Response())->json(['error' => 'Valor é obrigatório.'], 400);
        }

        $updated = $this->resourceService->updateValue($id, $valor);

        if (!$updated) {
            return (new Response())->json(['error' => 'Recurso não encontrado.'], 404);
        }

        return (new Response())->json([
            'success' => true,
            'message' => 'Valor atualizado com sucesso.',
            'valor'   => $valor,
            'hora'    => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * DELETE /api/resources/{id}
     */
    public function destroy(Request $req, array $params): Response
    {
        $id = $params['id'] ?? '';
        $deleted = $this->resourceService->deleteResource($id);

        if (!$deleted) {
            return (new Response())->json(['error' => 'Recurso não encontrado.'], 404);
        }

        return (new Response())->json([
            'success' => true,
            'message' => 'Recurso eliminado com sucesso.',
        ]);
    }

    /**
     * GET /api/resources/{id}/historico[?limite=20]
     */
    public function historico(Request $req, array $params): Response
    {
        $id     = $params['id'] ?? '';
        $limite = max(1, (int) $req->getQueryParam('limite', 20));

        $entries = $this->resourceService->getHistorico($id, $limite);

        return (new Response())->json([
            'id'      => $id,
            'registos' => $entries,
            'total'   => count($entries),
        ]);
    }

    /**
     * POST /api/resources/{id}/comando — dashboard -> actuator
     */
    public function comando(Request $req, array $params): Response
    {
        $id      = $params['id'] ?? '';
        $comando = Validator::sanitize($req->getParam('comando', ''));

        if ($comando === '') {
            return (new Response())->json(['error' => 'Comando é obrigatório.'], 400);
        }

        $executed = $this->resourceService->executeCommand($id, $comando);

        if (!$executed) {
            return (new Response())->json([
                'error' => 'Recurso não encontrado ou não é um atuador.',
            ], 404);
        }

        return (new Response())->json([
            'success' => true,
            'message' => 'Comando enviado com sucesso.',
            'comando' => $comando,
        ]);
    }

    /**
     * DELETE /api/resources/{id}/historico — clear log.txt
     */
    public function limparHistorico(Request $req, array $params): Response
    {
        $id = $params['id'] ?? '';

        if ($this->resourceService->getResource($id) === null) {
            return (new Response())->json(['error' => 'Recurso não encontrado.'], 404);
        }

        $this->resourceService->clearHistory($id);

        return (new Response())->json([
            'success' => true,
            'message' => 'Histórico limpo com sucesso.',
        ]);
    }
}
