<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ChauffageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //cree le chemin pour le seeder et le fait lancer le code sql
        $path = database_path('sql/chauffage-import.sql');
        $sql = File::get($path);
        DB::unprepared($sql);
    }
}
