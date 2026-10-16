<tr>
    <td class="i-nro"></td>
    <td>
        <select class="i-art" name="items[{{ $i }}][articulo]" required>
            <option value="">Elegí un artículo</option>
            @foreach ($articulos as $a)
                <option value="{{ $a->CODIGO }}" @selected(($it['articulo'] ?? null) == $a->CODIGO)>{{ $a->IDENTIFICACION }}{{ $a->DESCRIPCION ? ' — ' . $a->DESCRIPCION : '' }}</option>
            @endforeach
        </select>
    </td>
    <td><input class="i-cant" type="number" min="1" step="1" name="items[{{ $i }}][cantidad]" value="{{ $it['cantidad'] ?? '' }}" required style="width:80px"></td>
    <td><input class="i-rec" type="number" step="any" name="items[{{ $i }}][porc_recargo]" value="{{ $it['porc_recargo'] ?? '' }}" required style="width:90px"></td>
    {{-- Kilos: siempre con 2 decimales --}}
    <td class="num"><input class="i-kgs" type="number" step="0.01" min="0" name="items[{{ $i }}][kgs_unit]"
        value="{{ is_numeric($it['kgs_unit'] ?? null) ? number_format((float) $it['kgs_unit'], 2, '.', '') : ($it['kgs_unit'] ?? '') }}" style="width:100px"></td>
    <td class="num i-kgs-total">0,00</td>
    {{-- Importe en pesos: siempre con 2 decimales --}}
    <td class="num"><input class="i-pcio" type="number" step="0.01" min="0" name="items[{{ $i }}][pcio_unit]"
        value="{{ is_numeric($it['pcio_unit'] ?? null) ? number_format((float) $it['pcio_unit'], 2, '.', '') : ($it['pcio_unit'] ?? '') }}" required style="width:125px"></td>
    <td class="num i-pcio-total">0,00</td>
    <td class="iconos">
        <button type="button" class="ico-btn peligro i-del" title="Quitar ítem" aria-label="Quitar ítem">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
        </button>
    </td>
</tr>
