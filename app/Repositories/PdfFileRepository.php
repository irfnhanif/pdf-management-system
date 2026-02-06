<?php

namespace App\Repositories;

use App\Models\PdfFile;
use App\Repositories\Contracts\PdfFileRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PdfFileRepository implements PdfFileRepositoryInterface
{
    protected PdfFile $model;

    public function __construct(PdfFile $model)
    {
        $this->model = $model;
    }

    public function create(array $data): PdfFile
    {
        return $this->model->create($data);
    }

    public function findById(int $id): ?PdfFile
    {
        return $this->model->find($id);
    }

    public function delete(int $id): bool
    {
        $file = $this->findById($id);

        if (!$file) {
            return false;
        }

        return $file->delete();
    }

    public function getAll(int $limit = 10, string $status = ''): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($status)) {
            $query = $query->where('status', $status);
        }

        return $query->latest()->paginate($limit);
    }
}
