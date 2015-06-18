<div  class="page-title">
    <h2><?php echo $title; ?></h2>
</div>

<?php if (validation_errors()) { ?>
<div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <?php echo validation_errors(); ?>
</div>
<?php } ?>

<form action="" method="post" class="form-horizontal">

    <div class="form-group <?php echo form_error('title') ? 'has-error' : ''; ?>">
        <label for="title" class="col-sm-3 control-label">Title</label>
        <div class="col-sm-5">
            <input type="input" name="title" class="form-control" value="<?php echo set_value('title'); ?>">
        </div>
        <div class="col-sm-4">
            <?php echo form_error('title'); ?>
        </div>
    </div>
    
    <div class="form-group <?php echo form_error('name') ? 'has-error' : ''; ?>">
        <label for="name" class="col-sm-3 control-label">Name</label>
        <div class="col-sm-5">
            <input type="input" name="name" class="form-control" value="<?php echo set_value('name'); ?>">
        </div>
        <div class="col-sm-4">
            <?php echo form_error('name'); ?>
        </div>
    </div>

    <input type="hidden" name="csrf" value="<?php echo $csrf; ?>">

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" name="submit" class="btn btn-primary" value="Submit">
        </div>
    </div>

</form>

