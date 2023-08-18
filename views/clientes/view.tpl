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
                    <td>{$cliente.id}</td>
                </tr>
                <tr>
                    <th>RUT:</th>
                    <td>{$cliente.rut}</td>
                </tr>
                <tr>
                    <th>Nombre:</th>
                    <td>{$cliente.nombre}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>{$cliente.email}</td>
                </tr>
                <tr>
                    <th>Empresa:</th>
                    <td>
                        {if $cliente.empresa == 1}
                            Si
                        {else}
                            No
                        {/if}
                    </td>
                </tr>
                <tr>
                    <th>Fecha creación:</th>
                    <td>{$cliente.created_at|date_format:"%d-%m-%Y %H:%M:%S"}</td>
                </tr>
                <tr>
                    <th>Fecha modificación:</th>
                    <td>{$cliente.updated_at|date_format:"%d-%m-%Y %H:%M:%S"}</td>
                </tr>
            </table>
            <p><a href="{$_layoutParams.root}clientes" class="btn btn-primary">Volver</a></p>
        </div>
    </div>
</div>