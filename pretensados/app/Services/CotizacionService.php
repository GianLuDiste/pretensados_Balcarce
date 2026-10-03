<?php

namespace App\Services;

use App\Models\Cotizacion;
use App\Models\CotizacionItem;
use Illuminate\Support\Facades\DB;

class CotizacionService
{
    /** Costo (lista activa) y peso base de un artículo. Equivale a TraerCostos() del VB. */
    public function costoYPeso(int $articulo): array
    {
        $lista = DB::table('costos')->where('ACTIVA', 'S')->value('CODIGO');

        $costo = $lista
            ? (float) DB::table('costos items')->where('LISTA', $lista)->where('ARTICULO', $articulo)->value('COSTO')
            : 0.0;

        $peso = (float) DB::table('articulos_dimensiones')->where('ARTICULO', $articulo)->value('PESO_BASE');

        return ['costo' => $costo, 'peso' => $peso];
    }

    /** Próximo número de cotización. Atómico incluso en MyISAM (no depende de transacciones). */
    public function siguienteNumero(): int
    {
        $afectadas = DB::update('UPDATE contador_cotiz SET Ultima = LAST_INSERT_ID(IFNULL(Ultima, 0) + 1)');
        if ($afectadas === 0) {                       // la tabla del contador estaba vacía
            DB::table('contador_cotiz')->insert(['Ultima' => 1]);
            return 1;
        }
        return (int) DB::selectOne('SELECT LAST_INSERT_ID() AS n')->n;
    }

    public function siguienteVersion(int $nro): int
    {
        return ((int) Cotizacion::where('Nro_Cotizacion', $nro)->max('Version')) + 1;
    }

    /**
     * Alta. Sin $nro => cotización nueva (número del contador, versión 0).
     * Con $nro      => nueva versión de una cotización existente (cmdNew del VB).
     */
    public function crear(array $d, array $items, ?int $nro = null): Cotizacion
    {
        return DB::transaction(function () use ($d, $items, $nro) {
            if ($nro) {
                $ver = $this->siguienteVersion($nro);
            } else {
                $nro = $this->siguienteNumero();
                $ver = 0;
            }

            [$cab, $filas] = $this->armar($d, $items);
            Cotizacion::create($cab + ['Nro_Cotizacion' => $nro, 'Version' => $ver, 'IdStatus' => 'PD']);
            $this->guardarItems($nro, $ver, $filas);

            return Cotizacion::buscar($nro, $ver);
        });
    }

    public function actualizar(int $nro, int $ver, array $d, array $items): void
    {
        DB::transaction(function () use ($nro, $ver, $d, $items) {
            [$cab, $filas] = $this->armar($d, $items);
            Cotizacion::where('Nro_Cotizacion', $nro)->where('Version', $ver)->update($cab);
            $this->guardarItems($nro, $ver, $filas);
        });
    }

    public function eliminar(int $nro, int $ver): void
    {
        DB::transaction(function () use ($nro, $ver) {
            CotizacionItem::where('Nro_Cotizacion', $nro)->where('Version', $ver)->delete();
            Cotizacion::where('Nro_Cotizacion', $nro)->where('Version', $ver)->delete();
        });
    }

    /** Calcula totales en el servidor (no se confía en los totales que manda el navegador). */
    private function armar(array $d, array $items): array
    {
        $filas = [];
        $kgs = 0.0;
        $importe = 0.0;

        foreach (array_values($items) as $i) {
            $cant  = (int) $i['cantidad'];
            $kgsU  = (float) ($i['kgs_unit'] ?? 0);
            $pcioU = (float) $i['pcio_unit'];
            $kgsT  = round($cant * $kgsU, 3);
            $pcioT = round($cant * $pcioU, 2);

            $kgs     += $kgsT;
            $importe += $pcioT;

            $filas[] = [
                'Articulo'     => (int) $i['articulo'],
                'Cantidad'     => $cant,
                'Kgs_unit'     => $kgsU,
                'Kgs_total'    => $kgsT,
                'Porc_recargo' => (float) $i['porc_recargo'],
                'Pcio_unit'    => $pcioU,
                'Pcio_total'   => $pcioT,
            ];
        }

        $pIva  = (float) ($d['porc_iva'] ?? 0);
        $pIibb = (float) ($d['porc_iibb'] ?? 0);
        $iva   = round($importe * $pIva / 100, 2);
        $iibb  = round($importe * $pIibb / 100, 2);

        $cab = [
            'Fecha'           => $d['fecha'],
            'IdCliente'       => $d['id_cliente'],
            'Contacto'        => $d['contacto'] ?? null,
            'IdPlazo_Entrega' => $d['id_plazo_entrega'],
            'Lugar_Entrega'   => $d['lugar_entrega'] ?? null,
            'Flete'           => $d['flete'] ?? null,
            'Observaciones'   => $d['observaciones'] ?? null,
            'IdForma_Pago'    => $d['id_forma_pago'],
            'Obs_FPago'       => $d['obs_fpago'] ?? null,
            'IdVigencia'      => $d['id_vigencia'],
            'Total_kgs'       => round($kgs, 3),
            'Lista_Costo'     => (int) DB::table('costos')->where('ACTIVA', 'S')->value('CODIGO'),
            'Porc_Recargo'    => (float) ($d['porc_recargo'] ?? 0),
            'Importe'         => round($importe, 2),
            'Porc_IVA'        => $pIva,
            'Imp_IVA'         => $iva,
            'Porc_IIBB'       => $pIibb,
            'Imp_IIBB'        => $iibb,
            'Imp_Total'       => round($importe + $iva + $iibb, 2),
        ];

        return [$cab, $filas];
    }

    private function guardarItems(int $nro, int $ver, array $filas): void
    {
        CotizacionItem::where('Nro_Cotizacion', $nro)->where('Version', $ver)->delete();

        CotizacionItem::insert(array_map(
            fn ($f) => $f + ['Nro_Cotizacion' => $nro, 'Version' => $ver],
            $filas
        ));
    }
}
