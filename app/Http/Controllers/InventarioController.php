<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario; // Asegúrate de importar el modelo Inventario

class InventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin')->only(['destroy', 'update']);
    }

    /**
     * Muestra una lista del recurso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lugares = Inventario::select('lugar')->distinct()->get();
        $columnas = Inventario::select('columna')->distinct()->get();
        $inventario = [];

        $fecha = $request->input('fecha');
        $filtroLugar = $request->input('lugar');
        $filtroColumna = $request->input('columna');

        if ($fecha && $filtroLugar) {
            $query = Inventario::where('fecha', 'like', $fecha . '%')
                ->where('lugar', $filtroLugar);
            
            if ($filtroColumna) {
                $query->where('columna', $filtroColumna);
            }
            
            $inventario = $query->get();
        }
        $totalCantidad = collect($inventario)->sum('cantidad');

        return view('inventario.index', compact('inventario', 'lugares', 'columnas', 'totalCantidad'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lastLugar = session('last_lugar');
        $lastColumna = session('last_columna');
        
        return view('inventario.create', compact('lastLugar', 'lastColumna'));
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fecha' => 'required|date',
            'lugar' => 'required|string|max:255',
            'columna' => 'required|integer',
            'numero' => 'required|integer',
            'codigo' => 'required|string|max:255',
            'cantidad' => 'required|integer',
        ]);

        if ($request->input('lugar') === 'new') {
            $validatedData['lugar'] = $request->input('new_lugar');
        }

        try {
            Inventario::create($validatedData);
            
            // Store the last used values in session
            session(['last_lugar' => $validatedData['lugar']]);
            session(['last_columna' => $validatedData['columna']]);

            return redirect()->route('inventario.create')->with([
                'error' => 'Exito',
                'mensaje' => 'Artículo creado exitosamente',
                'tipo' => 'alert-success'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('inventario.create')->with([
                'error' => 'Error',
                'mensaje' => 'Artículo no se ha creado. Detalle: ' . $e->getMessage(),
                'tipo' => 'alert-danger'
            ]);
        }
    }

    /**
     * Muestra un recurso específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inventario = Inventario::findOrFail($id);
        return view('inventario.show', compact('inventario'));
    }

    /**
     * Muestra el formulario para editar un recurso específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inventario = Inventario::findOrFail($id);
        return view('inventario.edit', compact('inventario'));
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'fecha' => 'required|date',
            'lugar' => 'required|string|max:255',
            'columna' => 'required|integer', // renamed from 'fila'
            'numero' => 'required|integer',
            'codigo' => 'required|string|max:255',
            'valor' => 'nullable|numeric',
            'cantidad' => 'required|integer',
            'orden' => 'nullable|integer',
        ]);

        if ($request->input('lugar') === 'new') {
            $validatedData['lugar'] = $request->input('new_lugar');
        }

        try {
            Inventario::whereId($id)->update($validatedData);

            return redirect()->route('inventario.index')->with([
                'error' => 'Exito',
                'mensaje' => 'Artículo actualizado exitosamente',
                'tipo' => 'alert-success'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('inventario.index')->with([
                'error' => 'Error',
                'mensaje' => 'Artículo no se ha actualizado',
                'tipo' => 'alert-danger'
            ]);
        }
    }

    /**
     * Elimina un recurso específico de la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $inventario = Inventario::find($id);
            if($inventario) {
                $inventario->delete();
                return redirect()->route('inventario.index')
                    ->with('error', true)
                    ->with('tipo', 'alert-success')
                    ->with('mensaje', 'Artículo eliminado correctamente');
            }
            return redirect()->route('inventario.index')
                ->with('error', true)
                ->with('tipo', 'alert-danger')
                ->with('mensaje', 'No se encontró el artículo');
        } catch (\Exception $e) {
            return redirect()->route('inventario.index')
                ->with('error', true)
                ->with('tipo', 'alert-danger')
                ->with('mensaje', 'Error al eliminar el artículo');
        }
    }

    public function getNumerosLugar($lugar)
    {
        // removed: pluck('numero_lugar')
        return response()->json([]);
    }

    public function leerQR()
    {
        \Log::info('Accediendo a la vista de lector QR');
        return view('inventario.leerQR');
    }
}