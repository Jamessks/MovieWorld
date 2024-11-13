<?php

namespace Core\RedisManagement\jobs;

use Core\App;
use Core\Database;

class CleanupReactionsJob
{
    public int $id;
    public string $reference;

    public function __construct(int $id, string $reference)
    {
        $this->id = $id;
        $this->reference = $reference;
    }

    public function handle()
    {
        $db = App::resolve(Database::class);

        $db->query('DELETE FROM like_dislike WHERE target_id = :id AND target_type = :reference', [
            'id' => $this->id,
            'reference' => $this->reference
        ])->get();
    }
}
