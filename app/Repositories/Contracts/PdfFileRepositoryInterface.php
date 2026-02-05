<?php

namespace App\Repositories\Contracts;

use App\Models\PdfFile;

interface PdfFileRepositoryInterface
{
    public function create(array $data): PdfFile;

    public function findById(int $id): ?PdfFile;

    public function delete(int $id): bool;

    public function getAll(int $limit = 10);
}
