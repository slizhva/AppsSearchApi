<strong>---Enable dangerous actions:---</strong>
<div class="container">
    <form id="enable-dangerous-action-form" class="row">
        {{ csrf_field() }}
        <input name="key" class="col-md-6" type="password" value="" placeholder="Dangerous Action Key"/>
        <input class="col-md-2" type="submit" value="Enable"/>
    </form>
</div>