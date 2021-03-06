<div class="nav-content container">
      <h4><?=$title?></h4>
      <br>
    </div>
  </nav>
<div class="container section">
<?php echo form_open_multipart('private/bulletins/save'); ?>
  <input type="hidden" name="id" value="<?php echo $bul->id; ?>">
  <input type="hidden" name="image_path" value="<?php echo $bul->image; ?>">
  <div class="row">
    <div class="input-field col s12">
      <input id="title" name="title" type="text" class="validate <?php if (form_error('title')) {echo 'invalid';}?>" value="<?php echo $bul->title; ?>">
      <label for="title">Título *</label>        
      <span class="helper-text" data-error="<?php echo form_error('title', ' ', ' '); ?>">Informe o título do boletim</span>        
    </div>
  </div>  
  <div class="row">
    <div class="input-field col s12">
      <input id="subtitle" name="subtitle" type="text" value="<?php echo $bul->subtitle; ?>">
      <label for="subtitle">Subtítulo</label>        
      <span class="helper-text">Informe um subtítulo para o boletim (opcional)</span>        
    </div>
  </div>
  <div class="row">
        <img class="responsive-img image-thumb" src="<?php echo $bul->image; ?>"/>
  </div>
  <div class="row">
    <div class="file-field input-field">
      <div class="btn">
        <span>Procurar</span>
        <input type="file" id="image" name="image">
      </div>
      <div class="file-path-wrapper">
        <input class="file-path validate" type="text" placeholder="Escolha uma imagem">
      </div>
    </div>
  </div>
  <div class="row">
    <small>* Campos obrigatórios</small>
  </div>      
  <div class="row">
      <button class="btn waves-effect waves-light blue" type="submit">
        Salvar
        <i class="material-icons left">save</i>          
      </button>
      <a href="<?php echo base_url(); ?>private/bulletins/index" class="waves-effect waves-light btn grey">
        Cancelar
        <i class="material-icons left">close</i>          
      </a>
  </div>
</form>
