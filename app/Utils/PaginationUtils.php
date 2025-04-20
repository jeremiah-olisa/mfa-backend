<?php

namespace App\Utils;

use Illuminate\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;

class PaginationUtils
{
    /**
     * Format cursor pagination with position tracking
     */
    public static function formatCursorPaginationWithPositions(CursorPaginator $paginator, Builder $query): array
    {
        $baseData = self::basePaginatorData($paginator);
        $currentItems = $paginator->items();
        $itemsCount = count($currentItems);

        // Get all IDs in the same order as the pagination
        $orderedIds = $query->pluck('id')->toArray();
        $totalItems = $query->count();

        // Handle empty results case
        if ($itemsCount === 0) {
            return array_merge($baseData, [
                'from' => 0,
                'to' => 0,
                'total' => $totalItems,
                'items_count' => 0,
                'next_page_url' => null,
                'prev_page_url' => null,
                'has_more_pages' => false,
            ]);
        }

        // Calculate positions
        $positionData = self::calculatePositions($paginator, $orderedIds);

        return array_merge($baseData, [
            'from' => $positionData['from'],
            'to' => $positionData['to'],
            'total' => $totalItems,
            'items_count' => $itemsCount,
        ]);
    }

    /**
     * Basic cursor pagination format
     */
    public static function formatCursorPagination(CursorPaginator $paginator): array
    {
        return self::basePaginatorData($paginator);
    }

    /**
     * Base paginator data shared by both formatters
     */
    protected static function basePaginatorData(CursorPaginator $paginator): array
    {
        return [
            'next_page_url' => $paginator->nextPageUrl(),
            'prev_page_url' => $paginator->previousPageUrl(),
            'per_page' => $paginator->perPage(),
            'items_count' => count($paginator->items()),
            'next_cursor' => optional($paginator->nextCursor())->encode(),
            'prev_cursor' => optional($paginator->previousCursor())->encode(),
            'has_more_pages' => $paginator->hasMorePages(),
            'path' => $paginator->path(),
        ];
    }

    /**
     * Calculate from/to positions
     */
    protected static function calculatePositions(CursorPaginator $paginator, array $orderedIds): array
    {
        $items = $paginator->items();
        $firstItemId = $items[0]->id;
        $lastItem = end($items);
        $lastItemId = $lastItem->id;

        return [
            'from' => array_search($firstItemId, $orderedIds) + 1,
            'to' => array_search($lastItemId, $orderedIds) + 1,
        ];
    }
}