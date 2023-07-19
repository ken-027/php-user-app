<?php

namespace Models;

use Lib\Connection;

class User extends Connection
{
    protected $table = 'users';
    private $firstName = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function create(object $user): object
    {
        $conn = parent::get();
        $query = <<<Query
          INSERT INTO {$this->table}(
            `firstName`,
            `middleName`,
            `lastName`,
            `birthDate`,
            `image`
          ) VALUES (
            '{$user->firstName}',
            '{$user?->middleName}',
            '{$user?->lastName}',
            '{$user?->birthDate}',
            '{$user?->image['name']}'
          );
        Query;

        $isSaved = $conn->query($query);
        if (!$isSaved) {
            throw new \Exception("Failed to saved!", 500);
        }

        return $this->getById($conn->insert_id);

    }

    public function getAll(bool $isLatest = true): array
    {
        $conn = parent::get();
        $orderBy = $isLatest ? 'DESC' : 'ASC';
        $query = <<<Query
          SELECT * FROM `{$this->table}` ORDER BY `id` {$orderBy};
        Query;

        return (array) $conn->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): object
    {
        $conn = parent::get();
        $query = <<<Query
          SELECT * FROM `{$this->table}` WHERE `id`={$id};
        Query;

        if (!$user = $conn->query($query)->fetch_object()) {
            throw new \Exception("{$id} not found!", 400);
        }
        return $user;
    }

    public function delete($id): bool
    {
        $conn = parent::get();
        $query = <<<Query
          DELETE FROM `{$this->table}` WHERE `id`={$id};
        Query;

        return $conn->query($query);
    }

    public function update(object $user, int $id): object
    {
        $conn = parent::get();
        $fields = [];

        @$user?->firstName && array_push($fields, "`firstName`='{$user->firstName}'");
        @$user?->middleName && array_push($fields, "`middleName`='{$user->middleName}'");
        @$user?->lastName && array_push($fields, "`lastName`='{$user->lastName}'");
        @$user?->birthDate && array_push($fields, "`birthDate`='{$user->birthDate}'");
        @$user?->image && !empty($user?->image) && array_push($fields, "`image`='{$user?->image['name']}'");

        if (!count($fields)) {
            return $this->getById($id);
        }

        $toQuery = implode(", ", $fields);
        $query = <<<Query
          UPDATE `{$this->table}` SET {$toQuery} WHERE `id`={$id};
        Query;

        $updated = $conn->query($query);
        if (!$updated) {
            throw new \Exception("Error updating data", 500);
        }

        return $this->getById($id);
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}
