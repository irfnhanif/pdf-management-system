<?php

namespace App\Repositories;

use App\Models\PdfFile;
use App\Repositories\Contracts\PdfFileRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PdfFileRepository implements PdfFileRepositoryInterface
{
    protected PdfFile $model;

    public function __construct(PdfFile $model)
    {
        $this->model = $model;
    }

    public function generate(array $data): PdfFile
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

    public function getAll(int $limit = 10)
    {
        return $this->model->latest()->paginate($limit);
    }
}
