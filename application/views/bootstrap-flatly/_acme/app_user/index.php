<div class="row module-header">

    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
        <h1><?php echo lang($this->label) ?>
        <?php if($this->description != ''){ ?><small>// <?php echo lang($this->description)?></small> <?php } ?>
        </h1>
    </div>
    
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
        
        <div class="btn-group pull-right clearfix">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-align-justify hidden-lg hidden-md"></i> 
                <div class="hidden-xs hidden-sm">
                    <i class="fa fa-align-justify"></i> 
                    <span><?php echo lang('Ações') ?></span> 
                    <span class="caret"></span>
                </div>
            </button>
            <ul class="dropdown-menu">
                <li><a href="<?php echo URL_ROOT ?>/app_user/new_user"><i class="fa fa-user fa-fw"></i> <?php echo lang('Novo usuário')?></a></li>
                <li><a href="<?php echo URL_ROOT ?>/app_user/groups"><i class="fa fa-group fa-fw"></i> <?php echo lang('Grupos')?></a></li>
            </ul>
        </div>

    </div>
</div>

<div class="row" style="margin-bottom: 30px ">

    <div class="col-sm-12 col-md-6 col-lg-4">

        <div class="input-group" style="margin-bottom: 10px">
            <input type="text" id="search-users" class="form-control input-sm" placeholder="<?php echo lang('Pesquisar usuários') ?>" autofocus>
            <span class="input-group-addon input-sm"><i class="fa fa-search fa-fw"></i></span>
        </div>

    </div>

</div>

<?php 
foreach($users as $user) { 

// user variables
$url_img = get_value($user, 'url_img');
$id_user = get_value($user, 'id_user');
$group = get_value($user, 'user_group');
$name = get_value($user, 'name');
$active = get_value($user, 'dtt_inative') != '' ? 'N' : 'Y';
$email = get_value($user, 'email');

?>

<div class="row user" style="margin-bottom: 50px">

    <div class="col-sm-12">

        <?php 
        
        // Ajusta thumb
        if(basename($url_img) != '' && file_exists(PATH_UPLOAD . '/' . $this->photos_dir . '/' . basename($url_img)))
            $url_img = tag_replace($url_img);
        else
            $url_img = URL_IMG . '/user-unknown.png';
        ?>
        
        <div class="top" style="position: absolute;">
            <img class="img-circular user-profile-img hidden-xs" style="max-width: 105px; max-height: 105px" src="<?php echo $url_img ?>" />
            <img class="img-circular user-profile-img visible-xs" style="max-width: 75px; max-height: 75px" src="<?php echo $url_img ?>" />
        </div>

        <div class="top list-user-info" style="margin: 2px 0 0 135px;">

            <div class="hidden-xs"><h3 class="user-name" style="margin: 0 0 10px 0 "><?php echo $name ?></h3></div>
            <div class="visible-xs"><h4 class="user-name" style="margin: 0 0 10px 0 "><?php echo $name ?></h4></div>

            <div style="margin: 10px 0 15px">
                
                <div class="inline user-email" style="word-break: break-all;"><?php echo $email ?></div>

                <div class="label label-info cursor-default user-group" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo lang('Grupo') ?>"><?php echo $group ?></div>
                
                <?php if($active == 'Y'){ ?>
                <div class="label label-success"><i class="fa fa-check-circle fa-fw"></i> <?php echo lang('Ativo') ?></div>
                <?php } else { ?>
                <div class="label label-danger"><i class="fa fa-minus-circle fa-fw"></i> <?php echo lang('Inativo') ?></div>
                <?php } ?>

            </div>

            <div style="margin-top: 10px">
                
                <a href="<?php echo URL_ROOT ?>/app_user/edit/<?php echo $id_user ?>" class="btn btn-xs btn-primary" style="margin: 0 5px 5px 0">
                    <i class="fa fa-edit fa-fw"></i> 
                    <?php echo lang('Editar') ?>
                </a>

                <a href="<?php echo URL_ROOT ?>/app_user/permissions/<?php echo $id_user ?>" class="btn btn-xs btn-primary" style="margin: 0 5px 5px 0">
                    <i class="fa fa-shield fa-fw"></i> 
                    <?php echo lang('Permissões') ?>
                </a>

                <a href="javascript:void(0)" id="<?php echo $id_user ?>" class="btn btn-xs btn-primary reset-pass" style="margin: 0 5px 5px 0">
                    <i class="fa fa-lock fa-fw"></i> 
                    <?php echo lang('Resetar senha') ?>
                </a>

            </div>

        </div>
    
    </div>

</div>

<?php } ?>

<script>
    // tooltips
    $('body').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    });

    // input de pesquisa
    $("#search-users").keyup( function() {
        
        var exist = false;
        
        if($("#search-users").val().length > 2) {
            
            $('.user').each( function() {
                $(this).hide();                             
            });
            
            var search = $("#search-users").val().toLowerCase();       
            
            $('.user-name, .user-email, .user-group').each( function(index) {
            
                var text = $(this).html().toLowerCase();

                console.log(text);
                
                if(text.indexOf(search) != -1) {
                    exist = true;
                    $(this).closest('.user').show();
                }
            });
            
            if(exist == false)
                return;
        
        } else if($("#search-users").val().length <= 2 || $("#search-users").val().length == '') {
            $('.user').each(function(index) { 
                $(this).show();
            });
        }
    });

    // remove callback
    $('a.reset-pass').click( function () {

        // get id
        var id = $(this).attr('id');
        
        // Confirm this shit
        bootbox.confirm("<?php echo lang('Um email indicando os passos para alteração da senha será encaminhado para o usuário selecionado. Você confirma esta ação ?') ?>", function (result) {

            // Cancel
            if( ! result)
                return;

            // ajax to remove this fucking shit
            enable_loading();
            
            $.ajax({
                url: $('#URL_ROOT').val() + '/app_user/reset_password/' + id,
                context: document.body,
                cache: false,
                async: false,
                type: 'POST',

                complete : function (response) {
                    
                    // Parse json to check errors
                    json = $.parseJSON(response.responseText);
                    
                    // Check return
                    if( ! json.return) { 
                        // close modal and alert
                        bootbox.alert(json.error);
                        return false;
                    }

                    // Alert ok
                    bootbox.alert('<?php echo lang('Feito! Email encaminhado para o usuário selecionado') ?>');
                }
            });

            disable_loading();
            
        });

    });


</script>