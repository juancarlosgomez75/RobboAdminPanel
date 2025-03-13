<?php

namespace Database\Seeders; // Agregar el namespace correcto

use App\Models\Rank;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    public function run()
    {
        //Creo los rangos inicio con el encargado de ensamble
        $rango=new Rank();

        $rango->name= "Encargado de ensamble";

        $rango->save();

        //Creo los rangos inicio con el encargado de alistamiento
        $rango=new Rank();

        $rango->name= "Encargado de alistamiento";

        $rango->save();

        //Creo los rangos inicio con el encargado de producción
        $rango=new Rank();

        $rango->name= "Encargado de producción";

        $rango->save();

        //Creo los rangos inicio con el encargado de plataforma
        $rango=new Rank();

        $rango->name= "Encargado de plataforma";

        $rango->save();

        //Creo los rangos de desarrollo
        $rango=new Rank();

        $rango->name= "Desarrollador";

        $rango->save();
    }
}
