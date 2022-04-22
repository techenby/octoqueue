<?php

namespace App\Traits;

trait WithDelete
{
    public function delete($id, $column = 'id')
    {
        $model = $this->rows->firstWhere($column, $id);

        $modelName = $model->friendly;
        $model->safeDelete();

        $this->notify('success', "Successfully deleted {$modelName}");
        $this->emit('refresh');
    }

    public function massDelete($column = 'id')
    {
        $models = $this->rows->whereIn($column, $this->selected);

        $models->each(fn($model) => $model->safeDelete());

        $this->emit('refresh');
    }
}
