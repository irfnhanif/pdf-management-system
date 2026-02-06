<?php

namespace App\Repositories\Contracts;

use App\Models\PdfFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PdfFileRepositoryInterface
{
    public function getAll(int $limit = 10): LengthAwarePaginator;

    public function create(array $data): PdfFile;

    public function findById(int $id): ?PdfFile;

    public function updateStatus(int $id, string $status): bool;

    public function softDelete(int $id): bool;
}
