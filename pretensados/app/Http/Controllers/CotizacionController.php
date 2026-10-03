<?php

namespace App\Http\Controllers;

use App\Http\Requests\CotizacionRequest;
use App\Models\Articulo;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\FormaPago;
use App\Models\Plazo;
use App\Models\Vigencia;
use App\Services\CotizacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotizacionController extends Controller
{
    public function __construct(private CotizacionService $svc) {}

    /** cmdCons / ConsCotiz del VB: listado con búsqueda. */
    public function index(Request $r)
    {
        $cotizaciones = Cotizacion::with('cliente')
            ->when($r->filled('nro'), fn ($q) => $q->where('Nro_Cotizacion', (int) $r->nro))
            ->when($r->filled('cliente'), fn ($q) => $q->whereHas(
                'cliente', fn ($c) => $c->where('RAZON-SOCIAL', 'like', '%' . $r->cliente . '%')
            ))
            ->orderByDesc('Nro_Cotizacion')->orderByDesc('Version')
            ->paginate(25)->withQueryString();

        return view('cotizaciones.index', compact('cotizaciones'));
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

        return redirect()->route('cotizaciones.index')->with('ok', "Cotización {$nro}/{$ver} eliminada.");
    }

    /** Reemplaza el reporte Crystal: vista imprimible (Ctrl+P / Guardar como PDF). */
    public function imprimir(Request $request, int $nro, int $ver)
    {
        $c = Cotizacion::buscar($nro, $ver)->load(['cliente.ciudad', 'plazo', 'formaPago', 'vigencia']);

        return view('cotizaciones.print', [
            'c'       => $c,
            'items'   => $c->items()->with('detalleArticulo')->get(),
            'empresa' => DB::table('parametros_empresa')->first(),
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
            'modo' => $modo, 'form' => $form, 'nro' => $nro, 'ver' => $ver,
            'clientes'  => Cliente::with('ciudad')->orderBy('RAZON-SOCIAL')->get(),
            'plazos'    => Plazo::orderBy('DESCRIPCION')->get(),
            'formas'    => FormaPago::orderBy('DESCRIPCION')->get(),
            'vigencias' => Vigencia::orderBy('DESCRIPCION')->get(),
            'articulos' => Articulo::orderBy('IDENTIFICACION')->get(['CODIGO', 'IDENTIFICACION', 'DESCRIPCION']),
        ];
    }
}
