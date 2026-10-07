<?php

namespace App\Http\Controllers;

use App\Http\Requests\CotizacionRequest;
use App\Models\Articulo;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\CotizacionEstado;
use App\Models\FormaPago;
use App\Models\Plazo;
use App\Models\Vigencia;
use App\Services\CotizacionService;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    private const FILTROS = 'filtros.cotizaciones';

    public function __construct(private CotizacionService $svc) {}

    /** URL de la consulta con los últimos filtros usados (para Cancelar / Volver). */
    public static function urlConsulta(): string
    {
        return route('cotizaciones.index', session(self::FILTROS, []));
    }

    /** cmdCons / ConsCotiz del VB: listado con búsqueda. */
    public function index(Request $r)
    {
        $f = $r->validate([
            'nro'       => ['nullable', 'integer'],
            'cliente'   => ['nullable', 'string', 'max:100'],
            'desde'     => ['nullable', 'date'],
            'hasta'     => ['nullable', 'date'],
            'estados'   => ['nullable', 'array'],
            'estados.*' => ['string', 'max:2'],
        ]);

        // Rango de fechas: si vienen invertidas se acomodan.
        $desde = $f['desde'] ?? null;
        $hasta = $f['hasta'] ?? null;
        if ($desde && $hasta && $desde > $hasta) {
            [$desde, $hasta] = [$hasta, $desde];
        }
        $estados = array_values(array_filter($f['estados'] ?? []));   // vacío = todos

        // Se recuerdan filtros y página para volver a la misma consulta desde la ficha o la impresión.
        $r->session()->put(self::FILTROS, $r->query());

        $cotizaciones = Cotizacion::with(['cliente', 'estado'])
            ->when($f['nro'] ?? null, fn ($q, $nro) => $q->where('Nro_Cotizacion', $nro))
            ->when($f['cliente'] ?? null, fn ($q, $cli) => $q->whereHas(
                'cliente', fn ($c) => $c->where('RAZON-SOCIAL', 'like', '%' . $cli . '%')
            ))
            ->when($desde, fn ($q) => $q->whereDate('Fecha', '>=', $desde))
            ->when($hasta, fn ($q) => $q->whereDate('Fecha', '<=', $hasta))
            ->when($estados, fn ($q) => $q->whereIn('IdStatus', $estados))
            ->orderByDesc('Nro_Cotizacion')->orderByDesc('Version')
            ->paginate(25)->withQueryString();

        return view('cotizaciones.index', [
            'cotizaciones' => $cotizaciones,
            'estados'      => CotizacionEstado::orderBy('estado')->get(),
            'filtro'       => ['desde' => $desde, 'hasta' => $hasta, 'estados' => $estados],
        ]);
    }

    public function create()
    {
        return view('cotizaciones.form', $this->datosVista('nueva', $this->valoresIniciales()));
    }

    public function edit(int $nro, int $ver)
    {
        $c = Cotizacion::buscar($nro, $ver);

        return view('cotizaciones.form', $this->datosVista('editar', $this->desdeCotizacion($c), $nro, $ver));
    }

    /** cmdNew del VB: formulario precargado con la versión actual; se guarda como versión siguiente. */
    public function newVersion(int $nro, int $ver)
    {
        $c = Cotizacion::buscar($nro, $ver);

        return view('cotizaciones.form', $this->datosVista('version', $this->desdeCotizacion($c), $nro, $ver)
            + ['proximaVersion' => $this->svc->siguienteVersion($nro)]);
    }

    public function store(CotizacionRequest $request)
    {
        $d = $request->validated();
        $c = $this->svc->crear($d, $d['items'], $d['nro_cotizacion'] ?? null);

        return redirect()->route('cotizaciones.edit', [$c->Nro_Cotizacion, $c->Version])
            ->with('ok', "Cotización {$c->Nro_Cotizacion}/{$c->Version} guardada.");
    }

    public function update(CotizacionRequest $request, int $nro, int $ver)
    {
        Cotizacion::buscar($nro, $ver); // 404 si no existe
        $d = $request->validated();
        $this->svc->actualizar($nro, $ver, $d, $d['items']);

        return redirect()->route('cotizaciones.edit', [$nro, $ver])
            ->with('ok', "Cotización {$nro}/{$ver} actualizada.");
    }

    public function destroy(int $nro, int $ver)
    {
        Cotizacion::buscar($nro, $ver);
        $this->svc->eliminar($nro, $ver);

        return redirect(self::urlConsulta())->with('ok', "Cotización {$nro}/{$ver} eliminada.");
    }

    /** Reemplaza el reporte Crystal: vista imprimible (Ctrl+P / Guardar como PDF). */
    public function imprimir(Request $request, int $nro, int $ver)
    {
        $c = Cotizacion::buscar($nro, $ver)->load(['cliente.ciudad', 'plazo', 'formaPago', 'vigencia']);

        return view('cotizaciones.print', [
            'c'       => $c,
            'items'   => $c->items()->with('detalleArticulo')->get(),
            'rentab'  => $request->boolean('rentab'),   // Check1 "Imprimir % rentabilidad"
        ]);
    }

    /** JSON para el formulario: costo de la lista activa y peso del artículo. */
    public function precioArticulo(int $articulo)
    {
        return response()->json($this->svc->costoYPeso($articulo));
    }

    // ------------------------------------------------------------------

    private function valoresIniciales(): array
    {
        return [
            'fecha' => now()->format('Y-m-d'),
            'porc_recargo' => null, 'id_cliente' => null, 'contacto' => null,
            'id_plazo_entrega' => null,
            'lugar_entrega' => 'En Planta Balcarce',   // valores por defecto de Text2_KeyPress
            'flete' => 'Contratado',
            'observaciones' => null, 'id_forma_pago' => null, 'obs_fpago' => null, 'id_vigencia' => null,
            'porc_iva' => 21,                           // Limpiar(): Text18 = "21"
            'porc_iibb' => null,
            'items' => [],
        ];
    }

    private function desdeCotizacion(Cotizacion $c): array
    {
        return [
            'fecha' => $c->Fecha?->format('Y-m-d'),
            'porc_recargo' => $c->Porc_Recargo,
            'id_cliente' => $c->IdCliente,
            'contacto' => $c->Contacto,
            'id_plazo_entrega' => $c->IdPlazo_Entrega,
            'lugar_entrega' => $c->Lugar_Entrega,
            'flete' => $c->Flete,
            'observaciones' => $c->Observaciones,
            'id_forma_pago' => $c->IdForma_Pago,
            'obs_fpago' => $c->Obs_FPago,
            'id_vigencia' => $c->IdVigencia,
            'porc_iva' => $c->Porc_IVA,
            'porc_iibb' => $c->Porc_IIBB,
            'items' => $c->items()->get()->map(fn ($i) => [
                'articulo' => $i->Articulo, 'cantidad' => $i->Cantidad, 'porc_recargo' => $i->Porc_recargo,
                'kgs_unit' => $i->Kgs_unit, 'pcio_unit' => $i->Pcio_unit,
            ])->all(),
        ];
    }

    private function datosVista(string $modo, array $form, ?int $nro = null, ?int $ver = null): array
    {
        return [
            'modo' => $modo, 'form' => $form, 'nro' => $nro, 'ver' => $ver, 'volver' => self::urlConsulta(),
            'clientes'  => Cliente::with('ciudad')->orderBy('RAZON-SOCIAL')->get(),
            'plazos'    => Plazo::orderBy('DESCRIPCION')->get(),
            'formas'    => FormaPago::orderBy('DESCRIPCION')->get(),
            'vigencias' => Vigencia::orderBy('DESCRIPCION')->get(),
            'articulos' => Articulo::orderBy('IDENTIFICACION')->get(['CODIGO', 'IDENTIFICACION', 'DESCRIPCION']),
        ];
    }
}
