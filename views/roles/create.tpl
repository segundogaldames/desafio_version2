<div class="col-md-12">
    <div class="card">
        <h5 class="card-header">
            {$asunto}
        </h5>
        <div class="card-body">
            <div class="col-md-6">
                {include file="../partials/_messages.tpl"}
                <form action="{$_layoutParams.root}{$process}" method="post">
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <input type="text" name="nombre" value="{$role.nombre|default:""}" class="form-control" id="rol" aria-describedby="rol">
                        <div id="rol" class="form-text text-danger">Ingrese el rol del usuario</div>
                    </div>
                    <input type="hidden" name="send" value="{$send}">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>