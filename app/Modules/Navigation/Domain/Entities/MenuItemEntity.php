<?php

namespace App\Modules\Navigation\Domain\Entities;

use App\Modules\Authentication\Domain\ValueObjects\Id;
use App\Modules\Navigation\Domain\Enums\MenuType;

class MenuItemEntity
{
    /**
     * @param MenuItemEntity[] $children
     */
    public function __construct(
        private Id $id,
        private ?Id $moduleId,
        private ?Id $parentId,
        private string $code,
        private MenuType $type,
        private string $routePath,
        private string $icon,
        private array $defaultLabel, // ["en" => "Invoices", "fr" => "Factures"]
        private int $sortOrder,
        private ?Id $createdById,
        private ?Id $updatedById,
        private array $children = []
    ) {}

    /**
     * @param MenuItemEntity[] $children
     */
    public static function create(
        Id $id,
        ?Id $moduleId,
        ?Id $parentId,
        string $code,
        MenuType $type,
        string $routePath,
        string $icon,
        array $defaultLabel,
        int $sortOrder,
        ?Id $createdById,
        ?Id $updatedById,
        array $children = []
    ): self {
        return new self(
            $id,
            $moduleId,
            $parentId,
            $code,
            $type,
            $routePath,
            $icon,
            $defaultLabel,
            $sortOrder,
            $createdById,
            $updatedById,
            $children
        );
    }

    /**
     * Get the value of id
     */
    public function getId(): string | null
    {
        return $this->id?->value();
    }

    /**
     * Get the value of moduleId
     */
    public function getModuleId(): string | null
    {
        return $this->moduleId?->value();
    }

    /**
     * Get the value of parentId
     */
    public function getParentId(): string | null
    {
        return $this->parentId?->value();
    }

    /**
     * Get the value of code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the value of type
     */
    public function getType(): string
    {
        return $this->type->value();
    }

    /**
     * Get the value of routePath
     */
    public function getRoutePath()
    {
        return $this->routePath;
    }

    /**
     * Get the value of icon
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Get the value of defaultLabel
     */
    public function getDefaultLabel()
    {
        return $this->defaultLabel;
    }

    /**
     * Get the value of sortOrder
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Get the value of createdById
     */
    public function getCreatedById(): string | null
    {
        return $this->createdById?->value();
    }

    /**
     * Get the value of updatedById
     */
    public function getUpdatedById(): string | null
    {
        return $this->updatedById?->value();
    }

    /**
     * Get the value of children
     */
    public function getChildren()
    {
        return $this->children;
    }
}
