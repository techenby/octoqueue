@props(['model' => false, 'label' => false, 'type' => false, 'options' => [], 'placeholder' => false, 'leading' => false, 'trailing' => false])

<div>
    <x-form.label :for="$model" :value="$label"/>

    @if ($type === 'select')
    <x-dynamic-component component="form.select" :id="$model" wire:model="{{ $model }}" :options="$options" :placeholder="$placeholder" {{ $attributes->except('x-show') }}/>
    @elseif (in_array($type, ['email', 'password', 'date']))
    <x-dynamic-component :type="$type ?? 'text'" component="form.input" :id="$model" wire:model.lazy="{{ $model }}" :placeholder="$placeholder" :leading="$leading" :trailing="$trailing" {{ $attributes->except('x-show') }}/>
    @elseif (in_array($type, ['input', 'number', 'text']))
    <x-dynamic-component :type="$type ?? 'text'" component="form.input" :id="$model" wire:model="{{ $model }}" :placeholder="$placeholder" :leading="$leading" :trailing="$trailing" {{ $attributes->except('x-show') }}/>
    @elseif ($type === 'boolean')
    <div class="flex mt-1 space-x-4">
        <x-form.radio :id="$model.'-true'" :name="$model" value="1" wire:model="{{ $model }}" label="Yes" {{ $attributes->except('x-show') }} />
        <x-form.radio :id="$model.'-false'" :name="$model" value="0" wire:model="{{ $model }}" label="No" {{ $attributes->except('x-show') }} />
    </div>
    @elseif ($type)
    <x-dynamic-component :component="'form.' . $type" :id="$model" wire:model="{{ $model }}" {{ $attributes->except('x-show') }}/>
    @else
    {{ $slot }}
    @endif
    <x-form.error :error="$errors->first($model)" />
</div>
