<?php
declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Api;

interface UcpCatalogInterface
{
    /**
     * Browse all visible products (paginated).
     *
     * @param int $page
     * @param int $pageSize
     * @return mixed[]
     */
    public function browse(int $page = 1, int $pageSize = 20): array;

    /**
     * Full-text search across catalog.
     *
     * @param string $q
     * @param int    $page
     * @param int    $pageSize
     * @return mixed[]
     */
    public function search(string $q, int $page = 1, int $pageSize = 20): array;
}
