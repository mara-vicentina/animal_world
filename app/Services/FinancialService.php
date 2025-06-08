<?php

namespace App\Services;

use App\Models\Financial;

class FinancialService
{
    protected Financial $financial;

    public function __construct(Financial $financial)
    {
        $this->financial = $financial;
    }

    public function store(array $data, int $userId): Financial
    {
        $financial = clone $this->financial;
        $financial->fill($data);
        $financial->user_id = $userId;
        $financial->save();

        return $financial;
    }

    public function update(int $id, array $data): ?Financial
    {
        $financial = $this->financial->find($id);

        if (!$financial) {
            return null;
        }

        $financial->fill($data);
        $financial->save();

        return $financial;
    }

    public function destroy(int $id): bool
    {
        $financial = $this->financial->find($id);

        if (!$financial) {
            return false;
        }

        return $financial->delete();
    }
}
