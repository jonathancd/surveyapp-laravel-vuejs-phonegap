<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
    }
}

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Pedro Carvajal',
            'email' => 'xd.peter73@gmail.com',
            'password' => '1024456',

            'ci' => '22800114',
            'codigo_estudiante' => '125258',
            'fecha_nacimiento' => '1994-09-14',
            'genero' => 'Masculino',
            'estrato' => '4',
            'programa' => 'Ing',
            'semestre' => '2do semetre',
            'telefono' => '4148504159',
            'rol' => 0
        ]);

        DB::table('factors')->insert([
            'id' => 1,
            'estado' => 1,
            'titulo' => 'Encuesta Principal',
            'parent' => '-1',
            'tipo' => '1',
            'categoria' => '-1',
        ]);

        DB::table('factors')->insert([
            'id' => 2,
            'estado' => 1,
            'titulo' => 'Encuesta: CATEGORIA #1',
            'parent' => '1',
            'tipo' => '2',
            'categoria' => '1',
        ]);

        DB::table('factors')->insert([
            'id' => 3,
            'estado' => 1,
            'titulo' => 'Encuesta: CATEGORIA #2',
            'parent' => '1',
            'tipo' => '2',
            'categoria' => '2',
        ]);

        DB::table('factors')->insert([
            'id' => 4,
            'estado' => 1,
            'titulo' => 'Encuesta: CATEGORIA #3',
            'parent' => '1',
            'tipo' => '2',
            'categoria' => '3',
        ]);

        DB::table('factors')->insert([
            'id' => 5,
            'estado' => 1,
            'titulo' => 'Encuesta: CATEGORIA #4',
            'parent' => '1',
            'tipo' => '2',
            'categoria' => '4',
        ]);

        DB::table('factors')->insert([
            'id' => 6,
            'estado' => 1,
            'titulo' => 'Encuesta: CATEGORIA #5',
            'parent' => '1',
            'tipo' => '2',
            'categoria' => '5',
        ]);

        DB::table('factors')->insert([
            'id' => 7,
            'estado' => 1,
            'titulo' => 'Encuesta PRINCIPAL: CATEGORIA #1',
            'parent' => '-1',
            'tipo' => '2',
            'categoria' => '1',
        ]);

        DB::table('factors')->insert([
            'id' => 8,
            'estado' => 1,
            'titulo' => 'Encuesta PRINCIPAL: CATEGORIA #2',
            'parent' => '-1',
            'tipo' => '2',
            'categoria' => '2',
        ]);

        DB::table('factors')->insert([
            'id' => 9,
            'estado' => 1,
            'titulo' => 'Encuesta PRINCIPAL: CATEGORIA #3',
            'parent' => '-1',
            'tipo' => '2',
            'categoria' => '3',
        ]);

        DB::table('factors')->insert([
            'id' => 10,
            'estado' => 1,
            'titulo' => 'Encuesta PRINCIPAL: CATEGORIA #4',
            'parent' => '-1',
            'tipo' => '2',
            'categoria' => '4',
        ]);

        DB::table('factors')->insert([
            'id' => 11,
            'estado' => 1,
            'titulo' => 'Encuesta PRINCIPAL: CATEGORIA #5',
            'parent' => '-1',
            'tipo' => '2',
            'categoria' => '5',
        ]);





    }
}
