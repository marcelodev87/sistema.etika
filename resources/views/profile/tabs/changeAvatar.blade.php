<div role="tabpanel" class="tab-pane fade in active" id="changeAvatar">
    <form class="row" action="{!! route('app.profile.update_avatar', auth()->user()->id) !!}" method="post"
          enctype="multipart/form-data" id="profile-update-avatar">
        @method('patch')
        <div class="charset-box">

            <div class="col-md-4">
                <a href="#" id="linkUploadAvatar">
                    <img src="{{ getAvatar(auth()->user()->id) }}" id="previewAvatar" class="img-responsive center-block appUserAvatar" style="pointer-events: none">
                </a>
            </div>

            <div class="col-md-8 col-lg-8">
                <div class="alert alert-warning">
                    <p>Olá {{ auth()->user()->name }}, para trocar a imagem de perfil click na imagem e selecione a
                        imagem desejada do seu computador,
                        lembre-se, a imagem deve ter exatamente 200 pixels de largura e 200 pixels de altura.<br>
                        Tente recortar a imagem no photoshop, paint, gimp ou outro de sua escolha.
                    </p>
                    <blockquote><b>Observação</b>:<small>Estamos tentando implementar a funcionalidade que fará isso automáticamente.</small></blockquote>
                </div>
            </div>

            <input type="file" name="avatar" id="inputAvatarFile" style="display: none">
        </div>
    </form>
</div>
