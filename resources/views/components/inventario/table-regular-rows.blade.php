@props(['items', 'empresas'])

@foreach($items->sortBy('numero') as $item)
    <tr @if($item->cantidad == 0) class="table-danger" @endif data-id="{{ $item->id }}">
        <td class="editable text-center" data-field="numero">
            <span class="display-value">{{ $item->numero }}</span>
            <input type="number" class="form-control edit-input" style="display: none;" value="{{ $item->numero }}">
        </td>
        <td class="editable text-center" data-field="lugar">
            <span class="display-value">{{ $item->lugar }}</span>
            <input type="text" class="form-control edit-input" style="display: none;" value="{{ $item->lugar }}">
        </td>
        <td class="editable text-center" data-field="columna">
            <span class="display-value">{{ $item->columna }}</span>
            <input type="number" class="form-control edit-input" style="display: none;" value="{{ $item->columna }}">
        </td>
        <td class="editable" data-field="codigo">
            <span class="display-value">{{ $item->codigo }}</span>
            <input type="text" class="form-control edit-input" style="display: none;" value="{{ $item->codigo }}">
        </td>
        <td class="editable text-center" data-field="empresa_id">
            <span class="display-value">{{ $item->empresa ? $item->empresa->nombre : 'N/A' }}</span>
            <select class="form-control edit-input" style="display: none;">
                <option value="">Sin empresa</option>
                @if(isset($empresas))
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}" {{ $item->empresa_id == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->nombre }}
                        </option>
                    @endforeach
                @endif
            </select>
        </td>
        <td class="editable text-center" data-field="cantidad">
            <span class="display-value">{{ $item->cantidad }}</span>
            <input type="number" class="form-control edit-input" style="display: none;" value="{{ $item->cantidad }}">
        </td>
        <td class="text-center">
            @if($item->foto)
                <img src="{{ asset($item->foto) }}" alt="Foto de {{ $item->codigo }}" class="img-thumbnail" style="max-height: 50px; max-width: 50px; cursor: pointer;" onclick="mostrarFotoModal('{{ asset($item->foto) }}', '{{ $item->codigo }}')">
            @else
                <span class="text-muted">Sin foto</span>
            @endif
        </td>
        <td class="text-center">
            <div class="btn-group" role="group" aria-label="Acciones del artículo">
                <!-- Botón Ver -->
                <a href="{{ route('inventario.show', $item->id) }}" 
                   class="btn btn-sm btn-outline-info" 
                   title="Ver detalles del artículo {{ $item->codigo }}"
                   data-toggle="tooltip">
                    <i class="fa fa-eye"></i>
                </a>
                
                <!-- Botón Editar -->
                <a href="{{ route('inventario.edit', $item->id) }}" 
                   class="btn btn-sm btn-outline-primary" 
                   title="Editar artículo {{ $item->codigo }}"
                   data-toggle="tooltip">
                    <i class="fa fa-edit"></i>
                </a>
                
                <!-- Botón Eliminar (solo para admins) -->
                @can('admin')
                <form action="{{ route('inventario.destroy', $item->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="fecha" value="{{ request('fecha') }}">
                    <button type="submit" 
                            class="btn btn-sm btn-outline-danger" 
                            title="Eliminar artículo {{ $item->codigo }}"
                            data-toggle="tooltip"
                            onclick="return confirm('¿Está seguro de que desea eliminar el artículo {{ $item->codigo }}?')">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
                @endcan
            </div>
        </td>
    </tr>
@endforeach 