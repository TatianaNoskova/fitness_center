<?php

namespace App\Repositories;

use App\Models\Sede;

class SedeRepository implements SedeRepositoryInterface
{
    public function all()
    {
        // Подгружаем связь socios, как у тебя было в контроллере
        return Sede::with(['socios'])->get();
    }

    public function find($id)
    {
        return Sede::with(['socios'])->find($id);
    }

    public function create(array $data)
    {
        return Sede::create($data);
    }

    public function update($id, array $data)
    {
        $sede = Sede::find($id);
        if ($sede) {
            $sede->update($data);
            return $sede;
        }
        return null;
    }

    public function delete($id)
    {
        $sede = Sede::find($id);
        if ($sede) {
            return $sede->delete();
        }
        return false;
    }
}