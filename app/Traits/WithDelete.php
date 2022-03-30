<?php

namespace App\Traits;

trait WithDelete {

    public function delete($id, $column = 'id')
    {
        $model = $this->rows->firstWhere($column, $id);

        $modelName = $model->friendly;
        $model->safeDelete();

        $this->notify('success',  "Successfully deleted {$modelName}");
        $this->emit('refresh');
    }
}
