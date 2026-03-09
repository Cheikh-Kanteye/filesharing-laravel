<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Index GIN PostgreSQL pour la recherche full-text en français
        DB::statement("
            ALTER TABLE files
            ADD COLUMN IF NOT EXISTS search_vector tsvector
            GENERATED ALWAYS AS (
                to_tsvector('french',
                    coalesce(title, '') || ' ' ||
                    coalesce(original_name, '') || ' ' ||
                    coalesce(description, '')
                )
            ) STORED
        ");

        DB::statement('CREATE INDEX IF NOT EXISTS files_search_vector_idx ON files USING GIN(search_vector)');

        // Index B-tree classiques pour les tris et filtres
        DB::statement('CREATE INDEX IF NOT EXISTS files_space_id_created_at_idx ON files(space_id, created_at DESC)');
        DB::statement('CREATE INDEX IF NOT EXISTS files_user_id_idx ON files(user_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS files_mime_type_idx ON files(mime_type)');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS files_search_vector_idx');
        DB::statement('DROP INDEX IF EXISTS files_space_id_created_at_idx');
        DB::statement('DROP INDEX IF EXISTS files_user_id_idx');
        DB::statement('DROP INDEX IF EXISTS files_mime_type_idx');
        DB::statement('ALTER TABLE files DROP COLUMN IF EXISTS search_vector');
    }
};
