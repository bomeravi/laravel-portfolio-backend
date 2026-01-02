<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

abstract class BaseCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return array_filter([
            'success' => true,
            'data' => $this->mapData($request),
            'meta' => $this->paginationMeta(),
        ]);
    }

    /**
     * Child collections must return transformed data only
     */
    abstract protected function mapData(Request $request): array;

    protected function paginationMeta(): ?array
    {
        if (! $this->resource instanceof AbstractPaginator) {
            return null;
        }

        return [
            'total' => $this->resource->total(),
            'per_page' => $this->resource->perPage(),
            'current_page' => $this->resource->currentPage(),
            'last_page' => $this->resource->lastPage(),
            'from' => $this->resource->firstItem(),
            'to' => $this->resource->lastItem(),
        ];
    }
}
