<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB ;

class CleanDatabase extends Command
{
    protected $signature = 'clean:database';
    protected $description = 'Clean database entries for specific fields';

    public function handle()
    {
        $tables = [
            'users' => ['name'],
            'alumnos' => ['nombre', 'apellidos', 'email'],
            'maestros' => ['nombre', 'apellidos', 'email'],
            'registros_torneos' => ['nombre', 'apellidos', 'email'],
            'escuelas' => ['nombre'],
            'formas' => ['nombre'],
            'fusiones' => ['nombre'],
            'categorias' => ['nombre']
        ];

        foreach ($tables as $table => $columns) {
            foreach ($columns as $column) {
                $this->info("Cleaning $column in $table...");
                DB::table($table)
                    ->select('id', $column)
                    ->whereNotNull($column)
                    ->where($column, 'like', ' %') 
                    ->orWhere($column, 'like', '% ') 
                    ->chunkById(100, function ($rows) use ($table, $column) {
                        foreach ($rows as $row) {
                            DB::table($table)
                                ->where('id', $row->id)
                                ->update([$column => trim($row->$column)]);
                        }
                    });
            }
        }

        $this->info('Database limpia.');
    }
}
