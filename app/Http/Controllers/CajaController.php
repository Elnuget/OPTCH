<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Pedido;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Caja::with('user');
        
        // Use current date as default if no date filter is provided
        $fechaFiltro = $request->fecha_filtro ?? now()->format('Y-m-d');
        $query->whereDate('created_at', $fechaFiltro);
        
        $movimientos = $query->latest()->get();
        $totalCaja = Caja::sum('valor'); // Calculate total from all records
        
        return view('caja.index', compact('movimientos', 'fechaFiltro', 'totalCaja'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'valor' => 'required|numeric',
            'motivo' => 'required|string'
        ]);

        // Create Caja entry
        Caja::create([
            'valor' => $request->valor,
            'motivo' => $request->motivo,
            'user_id' => Auth::id()
        ]);

        // Create related Pedido
        $lastOrder = Pedido::orderBy('numero_orden', 'desc')->first();
        $nextOrderNumber = $lastOrder ? $lastOrder->numero_orden + 1 : 1;

        $pedido = Pedido::create([
            'fecha' => now(),
            'numero_orden' => $nextOrderNumber,
            'cliente' => 'Anónimo',
            'valor_compra' => $request->valor,
            'motivo_compra' => $request->motivo,
            'total' => $request->valor
        ]);

        // Create related Pago
        Pago::create([
            'pedido_id' => $pedido->id,
            'mediodepago_id' => 1, // Asume que 1 es el ID del método de pago por defecto
            'pago' => $request->valor
        ]);

        return redirect()->back()->with('success', 'Movimiento registrado exitosamente');
    }

    public function destroy(Caja $caja)
    {
        $caja->delete();
        return redirect()->back()->with('success', 'Movimiento eliminado exitosamente');
    }
}
