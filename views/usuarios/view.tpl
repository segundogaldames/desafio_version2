<div class="col-md-12">
    <div class="card">
        <h5 class="card-header">
            {$asunto}
        </h5>
        <div class="card-body">
            {include file="../partials/_messages.tpl"}
            <table class="table table-hover">
                <tr>
                    <th>Id:</th>
                    <td>{$usuario.id}</td>
                </tr>
                <tr>
                    <th>Nombre:</th>
                    <td>{$usuario.nombre}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>{$usuario.email}</td>
                </tr>
                <tr>
                    <th>Rol:</th>
                    <td>{$usuario.role.nombre}</td>
                </tr>
                <tr>
                    <th>Activo:</th>
                    <td>
                        {if $usuario.activo == 1}
                            Si
                        {else}
                            No
                        {/if}
                    </td>
                </tr>
                <tr>
                    <th>Fecha creación:</th>
                    <td>{$usuario.created_at|date_format:"%d-%m-%Y %H:%M:%S"}</td>
                </tr>
                <tr>
                    <th>Fecha modificación:</th>
                    <td>{$usuario.updated_at|date_format:"%d-%m-%Y %H:%M:%S"}</td>
                </tr>
            </table>
            <p><a href="{$_layoutParams.root}usuarios" class="btn btn-primary">Volver</a></p>
        </div>
    </div>
</div>