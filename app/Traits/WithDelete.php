<?php

namespace App\Traits;

trait WithDelete {

    public function delete($id, $column = 'id')
    {
        $model = $this->rows->firstWhere($column, $id);

        $model->safeDelete();

        $this->notification()->success(
            "Successfully deleted {$model->firiendly}"
        );
    }
}
