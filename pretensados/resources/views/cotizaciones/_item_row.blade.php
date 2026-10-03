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
    <td class="num"><input class="i-kgs" type="number" step="any" min="0" name="items[{{ $i }}][kgs_unit]" value="{{ $it['kgs_unit'] ?? '' }}" style="width:100px"></td>
    <td class="num i-kgs-total">0.000</td>
    <td class="num"><input class="i-pcio" type="number" step="any" min="0" name="items[{{ $i }}][pcio_unit]" value="{{ $it['pcio_unit'] ?? '' }}" required style="width:110px"></td>
    <td class="num i-pcio-total">0.00</td>
    <td><button type="button" class="btn danger i-del" aria-label="Quitar ítem">Quitar</button></td>
</tr>
