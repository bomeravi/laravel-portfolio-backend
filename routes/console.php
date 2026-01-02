<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schema;

Artisan::command('schema:generate-json', function () {
    $tables = Schema::getTableListing();
    $ignoreTables = ['migrations', 'failed_jobs', 'password_resets', 'personal_access_tokens', 'sessions', 'cache', 'jobs', 'cache_locks', 'job_batches'];

    if (!file_exists(base_path('zappz'))) {
        mkdir(base_path('zappz'), 0755, true);
    }

    foreach ($tables as $table) {
        if (in_array($table, $ignoreTables)) {
            continue;
        }

        $this->info("Processing table: $table");
        $columns = Schema::getColumns($table);
        $fields = [];

        foreach ($columns as $column) {
             if (in_array($column['name'], ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }
            
            $type = 'text';
            $dbType = $column['type_name'];
            if (str_contains($dbType, 'int')) $type = 'number';
            if (str_contains($dbType, 'text')) $type = 'textarea';
            if (str_contains($dbType, 'date')) $type = 'date';
            if (str_contains($dbType, 'timestamp')) $type = 'datetime-local';
            if (str_contains($dbType, 'bool')) $type = 'select';

            $fields[] = [
                'name' => $column['name'],
                'type' => $type,
            ];
        }

        $jsonContent = json_encode(['fields' => $fields], JSON_PRETTY_PRINT);
        file_put_contents(base_path("zappz/{$table}.json"), $jsonContent);
        $this->info("Generated: zappz/{$table}.json");
    }
})->purpose('Generate JSON schema files');
