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
                    <td>{$categoria.id}</td>
                </tr>
                <tr>
                    <th>Nombre:</th>
                    <td>{$categoria.nombre}</td>
                </tr>
                <tr>
                    <th>Fecha creación:</th>
                    <td>{$categoria.created_at|date_format:"%d-%m-%Y %H:%M:%S"}</td>
                </tr>
                <tr>
                    <th>Fecha modificación:</th>
                    <td>{$categoria.updated_at|date_format:"%d-%m-%Y %H:%M:%S"}</td>
                </tr>
            </table>
            <p><a href="{$_layoutParams.root}categorias" class="btn btn-primary">Volver</a></p>
        </div>
    </div>
</div>