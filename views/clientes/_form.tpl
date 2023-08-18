<form action="{$_layoutParams.root}{$process}" method="post">
    {if $action == 'create'}
        <div class="mb-3">
            <label for="rut" class="form-label">RUT</label>
            <input type="text" name="rut" value="{$cliente.rut|default:""}" class="form-control" id="rut"
                aria-describedby="rut">
            <div id="rut" class="form-text text-danger">Ingrese el rut del cliente</div>
        </div>
    {/if}
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" name="nombre" value="{$cliente.nombre|default:""}" class="form-control" id="nombre"
            aria-describedby="nombre">
        <div id="nombre" class="form-text text-danger">Ingrese el nombre del cliente</div>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" value="{$cliente.email|default:""}" class="form-control" id="email"
            aria-describedby="email">
        <div id="email" class="form-text text-danger">Ingrese el email del cliente</div>
    </div>
    <div class="mb-3">
        <label for="empresa" class="form-label">Empresa</label>
        <select name="empresa" class="form-control">
            {if $action == 'edit'}
                <option value="{$cliente.empresa}">
                    {if $cliente.empresa == 1}
                        Empresa
                    {else}
                        Persona Natural
                    {/if}
                </option>
            {/if}
            <option value="">Seleccione...</option>
            <option value="1">Es empresa</option>
            <option value="2">Persona Natural</option>
        </select>
        <div id="empresa" class="form-text text-danger">Seleccione un tipo de cliente</div>
    </div>
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="send" value="{$send}">
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>