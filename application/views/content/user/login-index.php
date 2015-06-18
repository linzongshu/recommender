<div class="page-title">
    <h2><?php echo $title; ?></h2>
</div>

<?php echo validation_errors(); ?>

<?php if (isset($message)) { ?>
<div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <?php echo $message; ?>
</div>
<?php } ?>

<?php echo form_open(uri_string(), 'class="form-horizontal"'); ?>

<div class="form-group <?php echo form_error('identity') ? 'has-error' : ''; ?>">
    <label for="identity" class="col-sm-3 control-label">Username</label>
    <div class="col-sm-5">
        <input type="input" name="identity" class="form-control" value="<?php echo set_value('identity'); ?>">
    </div>
    <div class="col-sm-4">
        <?php echo form_error('identity'); ?>
    </div>
</div>
    
<div class="form-group <?php echo form_error('password') ? 'has-error' : ''; ?>">
    <label for="password" class="col-sm-3 control-label">Password</label>
    <div class="col-sm-5">
        <input type="password" name="password" class="form-control" value="<?php echo set_value('password'); ?>">
    </div>
    <div class="col-sm-4">
        <?php echo form_error('password'); ?>
    </div>
</div>
    
    <input type="hidden" name="csrf" value="<?php echo $csrf; ?>">

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" name="submit" class="btn btn-primary" value="Submit">
    </div>
</div>

</form>

