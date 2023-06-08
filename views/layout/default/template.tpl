<!DOCTYPE html>
<html>
   <head>
   	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="frame de aplicaciones web">

    <title>{$title|default:"Ensayo Web"}</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {include file="link_css.tpl"}


   </head>
   <body>
  {include file="menu.tpl"}

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <div class="list-group mb-3">
          <a href="{$_layoutParams.root}roles" class="list-group-item list-group-item-action active" aria-current="true">
            Roles
          </a>
          <a href="{$_layoutParams.root}usuarios" class="list-group-item list-group-item-action">Usuarios</a>
          <a href="#" class="list-group-item list-group-item-action">A third link item</a>
          <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
          <a class="list-group-item list-group-item-action disabled">A disabled link item</a>
        </div>
      </div>
      <div class="col-md-9">
        {include file=$_content}
      </div>


    </div>
  </div>


    {include file="link_js.tpl"}

    <noscript>
      <p>Debe tener el soporte de Javascript habilitado</p>
    </noscript>

    {if isset($_layoutParams.js) && count($_layoutParams.js)}
      {foreach item=js from=$_layoutParams.js}
        <script type="text/javascript" src="{$js}"></script>
      {/foreach}

    {/if}
  </body>
</html>