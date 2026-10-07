<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Ciudad;
use App\Models\Cliente;
use App\Models\CondicionIva;
use App\Models\Provincia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Migración de Clientes.frm (permiso FAC01 del VB). */
class ClienteController extends Controller
{
    /** Combo1 + BCombo del VB: grilla con búsqueda por código, razón social, CUIT o contacto. */
    public function index(Request $r)
    {
        return view('clientes.index', [
            'clientes' => $this->consulta($r)->paginate(25)->withQueryString(),
            'ciudades' => Ciudad::orderBy('DESCRIPCION')->get(),
        ]);
    }

    public function create()
    {
        return view('clientes.form', $this->datosVista(new Cliente()));
    }

    public function store(ClienteRequest $request)
    {
        $c = new Cliente();
        $c->IdStatus = 'N';   // Normal
        $this->guardar($c, $request->validated());

        return redirect()->route('clientes.edit', $c)
            ->with('ok', "Cliente {$c->CODIGO} - {$c->razon_social} creado.");
    }

    /** TraigoDatos del VB. */
    public function edit(Cliente $cliente)
    {
        return view('clientes.form', $this->datosVista($cliente));
    }

    public function update(ClienteRequest $request, Cliente $cliente)
    {
        $this->guardar($cliente, $request->validated());

        return redirect()->route('clientes.edit', $cliente)
            ->with('ok', "Cliente {$cliente->CODIGO} - {$cliente->razon_social} actualizado.");
    }

    /** Command3D3_Click del VB. A diferencia del VB, no borra clientes con movimientos. */
    public function destroy(Cliente $cliente)
    {
        if ($tabla = $cliente->tablaConMovimientos()) {
            return back()->withErrors(['cliente' => "No puede eliminarse: el cliente tiene registros en {$tabla}."]);
        }

        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('ok', "Cliente {$cliente->CODIGO} - {$cliente->razon_social} eliminado.");
    }

    /** Reemplaza el listado Crystal "Clientes": vista imprimible con el filtro actual. */
    public function imprimir(Request $r)
    {
        return view('clientes.print', [
            'clientes' => $this->consulta($r)->get(),
            'filtro'   => trim($r->q . ' ' . ($r->filled('localidad') ? Ciudad::find($r->localidad)?->DESCRIPCION : '')),
        ]);
    }

    // ------------------------------------------------------------------

    private function consulta(Request $r): Builder
    {
        return Cliente::with(['ciudad', 'condicionIva'])
            ->when($r->filled('q'), function ($q) use ($r) {
                $t = '%' . $r->q . '%';
                $q->where(fn ($w) => $w->where('RAZON-SOCIAL', 'like', $t)
                    ->orWhere('CUIT', 'like', $t)
                    ->orWhere('CONTACTO', 'like', $t)
                    ->when(ctype_digit($r->q), fn ($w) => $w->orWhere('CODIGO', (int) $r->q)));
            })
            ->when($r->filled('localidad'), fn ($q) => $q->where('LOCALIDAD', (int) $r->localidad))
            ->orderBy('RAZON-SOCIAL');
    }

    /** Grabo() del VB, dentro de una transacción. */
    private function guardar(Cliente $c, array $d): void
    {
        $c->forceFill([
            'RAZON-SOCIAL' => $d['razon_social'],
            'DIRECCION'    => $d['direccion'] ?? null,
            'TELEFONO'     => $d['telefono'] ?? null,
            'FAX'          => $d['fax'] ?? null,
            'LOCALIDAD'    => $d['localidad'],
            'COD-POSTAL'   => $d['cod_postal'] ?? 0,
            'PROVINCIA'    => $d['provincia'],
            'IVA'          => $d['iva'],
            'CUIT'         => $d['cuit'] ?? null,
            'CONTACTO'     => $d['contacto'] ?? null,
            'E-MAIL'       => $d['email'] ?? null,
        ]);

        DB::transaction(fn () => $c->save());
    }

    private function datosVista(Cliente $cliente): array
    {
        return [
            'cliente'     => $cliente,
            'ciudades'    => Ciudad::orderBy('DESCRIPCION')->get(),
            'provincias'  => Provincia::orderBy('DESCRIPCION')->get(),
            'condiciones' => CondicionIva::orderBy('DESCRIPCION')->get(),
        ];
    }
}
