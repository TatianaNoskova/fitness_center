<?php

namespace App\Http\Controllers;

use App\Repositories\SedeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SedeController extends Controller
{
    // Объявляем защищенное свойство для нашего репозитория
    protected $sedeRepository;

    // Внедряем репозиторий через конструктор (Dependency Injection)
    // Laravel сам поймет, какой класс подставить вместо интерфейса!
    public function __construct(SedeRepositoryInterface $sedeRepository)
    {
        $this->sedeRepository = $sedeRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sedes = $this->sedeRepository->all();
        return response()->json($sedes, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $sede = $this->sedeRepository->create($validated);

        return response()->json([
            'message' => 'Sede creada con éxito',
            'data' => $sede
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sede = $this->sedeRepository->find($id);

        if (!$sede) {
            return response()->json(['message' => 'Sede no encontrada'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($sede, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'direccion' => 'sometimes|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $sede = $this->sedeRepository->update($id, $validated);

        if (!$sede) {
            return response()->json(['message' => 'Sede no encontrada'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => 'Sede actualizada con éxito',
            'data' => $sede
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->sedeRepository->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Sede no encontrada or no pudo ser eliminada'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['message' => 'Sede eliminada con éxito'], Response::HTTP_OK);
    }
}