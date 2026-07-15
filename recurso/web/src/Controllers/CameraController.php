<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Lib\ImageHandler;
use App\Lib\FileStore;

class CameraController
{
    private string $imageDir;

    public function __construct()
    {
        $this->imageDir = dirname(__DIR__, 2) . '/data/camera/imagens';
    }

    /**
     * POST /api/camera/upload
     * Receives image via multipart POST, validates (.jpg/.png, ≤1MB),
     * saves to data/camera/imagens/, updates ultima_hora.txt
     */
    public function upload(Request $req): Response
    {
        $file = $req->getFile('imagem');
        if ($file === null) {
            return (new Response())->json(['error' => 'Nenhuma imagem enviada.'], 400);
        }

        $savedPath = ImageHandler::processUpload($file, $this->imageDir);

        if ($savedPath === null) {
            return (new Response())->json([
                'error' => 'Falha no upload. Apenas .jpg/.png, máximo 1MB.',
            ], 400);
        }

        // Update ultima_hora.txt
        $ultimaHoraPath = dirname($this->imageDir) . '/ultima_hora.txt';
        FileStore::writeFile($ultimaHoraPath, date('Y-m-d H:i:s'));

        return (new Response())->json([
            'success'  => true,
            'message'  => 'Imagem enviada com sucesso.',
            'caminho'  => $savedPath,
            'hora'     => date('Y-m-d H:i:s'),
        ], 201);
    }

    /**
     * GET /api/camera/ultima
     * Returns last image path + timestamp
     */
    public function ultima(Request $req): Response
    {
        $lastImage = ImageHandler::getLastImage($this->imageDir);

        $ultimaHoraPath = dirname($this->imageDir) . '/ultima_hora.txt';
        $ultimaHora = FileStore::readFile($ultimaHoraPath);

        if ($lastImage === null) {
            return (new Response())->json([
                'caminho' => null,
                'hora'    => $ultimaHora ?: null,
                'message' => 'Nenhuma imagem disponível.',
            ]);
        }

        return (new Response())->json([
            'caminho' => $lastImage,
            'hora'    => $ultimaHora ?: date('Y-m-d H:i:s', filemtime($lastImage)),
        ]);
    }

    /**
     * GET /api/camera/historico
     * Lists all available images sorted by date
     */
    public function historico(Request $req): Response
    {
        $images = ImageHandler::listImages($this->imageDir);

        return (new Response())->json([
            'total'  => count($images),
            'images' => $images,
        ]);
    }
}
