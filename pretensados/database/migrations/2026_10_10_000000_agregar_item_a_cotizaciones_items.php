<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * cotizaciones_items no tiene PK ni columna de orden: el orden de los ítems era el físico de MyISAM,
 * que cambia al reusar huecos (cada edición borra y reinserta). Se agrega `Item` = número de renglón.
 *
 * Numeración inicial: para cada cotización/versión se toma "el primer renglón todavía sin número"
 * (UPDATE ... LIMIT 1 sin ORDER BY → orden físico, el mismo en que la app los mostraba hasta ahora).
 * Funciona aunque haya renglones idénticos, que no se podrían distinguir por sus valores.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cotizaciones_items', function (Blueprint $t) {
            $t->unsignedSmallInteger('Item')->nullable()->after('Version');
        });

        $grupos = DB::table('cotizaciones_items')
            ->select('Nro_Cotizacion', 'Version', DB::raw('COUNT(*) AS n'))
            ->groupBy('Nro_Cotizacion', 'Version')->get();

        foreach ($grupos as $g) {
            for ($i = 1; $i <= $g->n; $i++) {
                DB::update(
                    'UPDATE cotizaciones_items SET Item = ? WHERE Nro_Cotizacion = ? AND Version = ? AND Item IS NULL LIMIT 1',
                    [$i, $g->Nro_Cotizacion, $g->Version]
                );
            }
        }

        Schema::table('cotizaciones_items', function (Blueprint $t) {
            $t->index(['Nro_Cotizacion', 'Version', 'Item'], 'cotizaciones_items_orden');
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones_items', function (Blueprint $t) {
            $t->dropIndex('cotizaciones_items_orden');
            $t->dropColumn('Item');
        });
    }
};
