<main class="grid">
    <div class="row m-5">
        <h1>Grupos</h1>
    </div>
    <div class="row m-5">
        <div class="cell-md-12">
            <div class="grid">
                <div class="row">
                    <div class="cell-md-12 d-flex flex-justify-center" id="tablePlace">
                        <div class="filtering" style="display: inline-block;position: relative; padding-bottom: 30px">
                            <form method="POST" class="inline-form">
                                <select data-role="select" id="field">
                                    <option value=""> --------------- </option>
                                    <option value="name">Nombre</option>
                                </select>
                                <input type="text" name="value" id="value" data-role="input" />
                                <button class="button success" type="submit" id="search">Buscar</button>&nbsp;&nbsp;
                                <butto n class="button alert" id="clean">Limpiar</button>
                            </form>
                        </div>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="cell-md-12 ">
                        <div id="tbl_groups" class="mx-5"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>

</script>
