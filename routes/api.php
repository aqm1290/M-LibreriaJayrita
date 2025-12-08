<?php
// CAMPANITA - PEDIDOS RESERVADOS (FUNCIONA EN LARAVEL 12 SIN API.PHP)
Route::get('/pedidos-reservados-data', function () {
    $pedidos = \App\Models\Pedido::where('estado', 'reservado')
     ->select('id', 'cliente_nombre', 'total', 'expira_en', 'created_at')
     ->latest()
     ->take(10)
     ->get()
     ->map(function ($p) {
         $horas = now()->diffInHours($p->expira_en, false);
         $minutos = now()->diffInMinutes($p->expira_en, false) % 60;
         $tiempo = $horas > 0 ? "$horas h $minutos m" : "Vencido";

         return [
             'id' => $p->id,
             'cliente_nombre' => $p->cliente_nombre ?? 'Sin nombre',
             'total' => number_format($p->total, 2),
             'created_at' => $p->created_at->format('d/m H:i'),
             'tiempo_restante' => $tiempo
         ];
     });

 return response()->json([
     'count' => $pedidos->count(),
     'pedidos' => $pedidos
 ]);
});