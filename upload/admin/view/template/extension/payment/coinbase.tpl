<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-payment" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="container-fluid">
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>

            <div class="panel-body">

                <div class="coinbase-preview">
                    <div class="logo">
                        <img src="view/image/payment/Coinbase.png">
                    </div>
                    <div class="preview-content">
                        <p>
                            <span class="title">
                                <?php echo $text_coinbase_preview_heading; ?>
                            </span>
                        </p>
                        <p>
                            <?php echo $text_coinbase_preview_intro; ?>
                        </p>
                        <p>
                            <a class="preview-link" href="https://commerce.coinbase.com/signin" target="_blank"><?php echo $text_sign_in; ?></a>
                            <a class="preview-link" href="https://commerce.coinbase.com/signup" target="_blank"><?php echo $text_sign_up; ?></a>
                            <a class="preview-link" href="https://commerce.coinbase.com/docs/api/" target="_blank"><?php echo $text_support; ?></a>
                        </p>
                    </div>
                </div>

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-payment"
                      class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="coinbase_status" id="input-status" class="form-control">
                                <?php if ($coinbase_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <legend><?php echo $text_api_credentials_section?></legend>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-api-key"><?php echo $entry_api_key;?></label>
                        <div class="col-sm-10">
                            <input type="text" name="coinbase_api_key" value="<?php echo $coinbase_api_key; ?>" placeholder="<?php echo $entry_api_key; ?>" id="input-api-key" class="form-control"/>
                            <?php if ($error_api_key) { ?>
                                <div class="text-danger"></div>
                            <?php } ?>
                            <div class="help-block"><?php echo $help_api_key; ?></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-api-secret"><?php echo $entry_api_secret; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="coinbase_api_secret"
                                   value="<?php echo $coinbase_api_secret; ?>" placeholder="<?php echo $entry_api_secret; ?>" id="input-api-secret" class="form-control"/>
                            <?php if ($error_api_secret) { ?>
                                <div class="text-danger"></div>
                            <?php } ?>
                            <div class="help-block"><?php echo $help_api_secret; ?></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-api-test-mode"><?php echo $entry_api_test_mode; ?></label>
                        <div class="col-sm-10">
                            <select name="coinbase_api_test_mode" id="input-api-test-mode" class="form-control">
                                <?php if ($coinbase_api_test_mode) { ?>
                                    <option value="1" selected="selected"><?php echo $text_api_test_mode_yes; ?></option>
                                    <option value="0"><?php echo $text_api_test_mode_no; ?></option>
                                <?php } else { ?>
                                    <option value="1"><?php echo $text_api_test_mode_yes; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_api_test_mode_no; ?></option>
                                <?php } ?>
                            </select>
                            <div class="help-block">
                                <?php echo $help_api_test_mode;?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-api-callback"><?php echo $entry_api_callback; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="coinbase_api-callback" value="<?php echo $callback_url; ?>"  id="input-api-callback" class="form-control" readonly/>
                            <div class="help-block"><?php echo $help_api_callback; ?></div>
                        </div>
                    </div>

                    <legend><?php echo $text_order_settings_section; ?></legend>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="coinbase_total" value="<?php echo $coinbase_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                        <div class="col-sm-10">
                            <select name="coinbase_order_status_id" id="input-order-status" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $coinbase_order_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"
                                                selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_pending_status; ?></label>
                        <div class="col-sm-10">
                            <select name="coinbase_pending_status_id" id="input-order-status" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $coinbase_pending_status_id ) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"
                                                selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-completed-status"><?php echo $entry_completed_status; ?></label>
                        <div class="col-sm-10">
                            <select name="coinbase_completed_status_id" id="input-completed-status"
                                    class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $coinbase_completed_status_id ) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-confirmed-status"><?php echo $entry_confirmed_status; ?></label>
                        <div class="col-sm-10">
                            <select name="coinbase_resolved_status_id" id="input-confirmed-status" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $coinbase_resolved_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-unresolved-status"><?php echo $entry_unresolved_status; ?></label>
                        <div class="col-sm-10">
                            <select name="coinbase_unresolved_status_id" id="input-unresolved-status" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $coinbase_unresolved_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-expired-status"><?php echo $entry_expired_status; ?></label>
                        <div class="col-sm-10">
                            <select name="coinbase_expired_status_id" id="input-expired-status" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status->order_status_id == $coinbase_expired_status_id ) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                        <div class="col-sm-10">
                            <select name="coinbase_geo_zone_id" id="input-geo-zone" class="form-control">
                                <option value="0"><?php echo $text_all_zones; ?></option>
                                <?php foreach ($geo_zones as $geo_zone) { ?>
                                    <?php if ( $geo_zone->geo_zone_id == $coinbase_geo_zone_id) { ?>
                                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="coinbase_sort_order" value="<?php echo $coinbase_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control"/>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<style>
    legend {
        padding-bottom: 5px;
    }

    .coinbase-preview {
        background: #fff none repeat scroll 0 0;
        border: 1px solid #ededed;
        border-radius: 5px;
        box-shadow: 0 1px 15px 1px rgba(113, 106, 202, .08);
        margin-bottom: 15px;
        padding: 15px;
        display: flex;
        flex-wrap: wrap;
    }

    .coinbase-preview .logo {
        border-right: #e5e5e5 solid 1px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex: 0 0 15%;
        max-width: 15%;
    }

    .coinbase-preview .logo img {
        margin-right: 10px;
    }

    .coinbase-preview .preview-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex: 0 0 85%;
        max-width: 85%;
        padding: 5px 0px 5px 15px;
    }

    .coinbase-preview .preview-content p span.title {
        font-size: 17px;
    }

    .coinbase-preview .preview-content p {
        margin-bottom: 5px;
    }

    .coinbase-preview .preview-content p:last-of-type {
        margin-bottom: 0;
    }

    .coinbase-preview .preview-content p span.api-status.success {
        color: #04a13f;
    }

    .coinbase-preview .preview-content p span.api-status.error {
        color: #ec546d;
    }

    .coinbase-preview .preview-content p a.preview-link {
        background-color: #8255f2;
        color: #fff;
        font-size: 16px;
        padding: 7px 15px 7px 15px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-block;
        margin-right: 5px;
        margin-top: 10px;
        -webkit-box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.2);
        -moz-box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.2);
        box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.2);
    }

    .coinbase-preview .preview-content p a.preview-link:hover {
        background-color: #7647eb;
    }
</style>
<?php echo $footer; ?>