<div id="hmw_wrap" class="d-flex flex-row my-3 bg-light">
    <?php echo HMW_Classes_ObjController::getClass('HMW_Controllers_Settings')->getAdminTabs('hmw_securitycheck'); ?>
    <div class="hmw_row d-flex flex-row bg-white px-3">
        <div class="hmw_col flex-grow-1 mr-3">
            <div class="card col-sm-12 p-0">
                <h3 class="card-title bg-brown text-white p-2"><?php _e('WordPress Security Check', _HMW_PLUGIN_NAME_); ?>:</h3>
                <div class="card-body">
                    <?php if (HMW_Classes_Tools::getOption('api_token') <> '') { ?>
                        <div class="col-sm-12 my-0 text-center">
                            <a href="<?php echo _HMW_ACCOUNT_SITE_ . '/api/auth/' . HMW_Classes_Tools::getOption('api_token') ?>" target="_blank"><img src="<?php echo _HMW_THEME_URL_ . 'img/monitor_panel.png' ?>" style="width: 100%; max-width: 800px;"/></a>
                        </div>
                    <?php } ?>
                    <div class="col-sm-12 border-bottom border-light py-3 m-0">
                        <div class="card col-sm-12 p-4 bg-light ">
                            <div class="card-body text-center p-0">
                                <div class="start_securitycheck">

                                    <button type="button" class="btn rounded-0 btn-warning btn-lg text-white px-5 "><?php _e('Start Scan', _HMW_PLUGIN_NAME_); ?></button>

                                    <?php if (isset($view->securitycheck_time['timestamp'])) { ?>
                                        <div class="text-center text-black-50 my-2">
                                            <strong><?php _e('Last check:', _HMW_PLUGIN_NAME_); ?></strong> <?php echo date(get_option('date_format') . ' ' . get_option('time_format'), ($view->securitycheck_time['timestamp'] + (get_option('gmt_offset') * HOUR_IN_SECONDS))); ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if (!empty($view->report)) {
                                        $overview = array('success' => 0, 'warning' => 0, 'total' => 0);
                                        foreach ($view->report as $row) {
                                            $overview['success'] += (int)$row['valid'];
                                            $overview['warning'] += (int)$row['warning'];
                                            $overview['total'] += 1;
                                        }
                                        echo '<table class="offset-3 col-sm-6 mt-4">';
                                        echo '<tbody>';
                                        echo '
                                            <tr>
                                                <td class="text-success border-right"><h6>' . __('Passed', _HMW_PLUGIN_NAME_) . '</h6><h2>' . $overview['success'] . '</h2></td>
                                                <td class="text-danger"><h6>' . __('Failed', _HMW_PLUGIN_NAME_) . '</h6><h2>' . ($overview['total'] - $overview['success']) . '</h2></td>
                                            </tr>';
                                        echo '</tbody>';
                                        echo '</table>';

                                        if (($overview['total'] - $overview['success']) == 0) { ?>
                                            <div class="text-center text-success font-weight-bold mt-4"><?php echo __("Congratulations! You completed all the security tasks. Make sure you check your site once a week.", _HMW_PLUGIN_NAME_) ?></div>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <div class="text-center small text-black-50 mt-4"><?php echo sprintf(__("Note! Try to complete all the tasks below and see it represents a real security issue for your site. <br />According to %sGoogle latest stats%s, over 20k websites are hacked every week and over 30&#37; of them are made in WordPress. <br />It's better to prevent an attack than to spend a lot of money and time to recover your data after an attack not to mention the situation when your clients' data are stollen.", _HMW_PLUGIN_NAME_), '<a href="https://transparencyreport.google.com/safe-browsing/overview" target="_blank">', '</a>') ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3 p-0 input-group">
                            <?php
                            if (!empty($view->report)) {
                                echo '<table class="table table-striped table_securitycheck">';
                                echo '
                                <thead>
                                    <tr>
                                        <th scope="col">' . __('Name', _HMW_PLUGIN_NAME_) . '</th>
                                        <th scope="col">' . __('Value', _HMW_PLUGIN_NAME_) . '</th>
                                        <th scope="col">' . __('Valid', _HMW_PLUGIN_NAME_) . '</th>
                                        <th scope="col">' . __('Action', _HMW_PLUGIN_NAME_) . '</th>
                                    </tr>
                                </thead>';

                                echo '<tbody>';
                                foreach ($view->report as $index => $row) {
                                    echo '
                                            <tr>
                                                <td style="min-width: 250px">' . $row['name'] . '</td>
                                                <td style="min-width: 200px; font-weight: bold">' . $row['value'] . '</td>
                                                <td class="' . ($row['valid'] ? 'text-success' : 'text-danger') . '">' . ($row['valid'] ? '<i class="fa fa-check mx-2"></i>' : '<i class="fa fa-times mx-2"></i>' . (isset($row['solution']) ? $row['solution'] : '')) . '</td>
                                                <td style="min-width: 180px; padding-right: 0!important;" >
                                                    <div class="modal fade" id="hmw_securitydetail' . $index . '" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">' . $row['name'] . '</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                               <div class="modal-body">' . $row['message'] . '</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-default" type="button"  data-toggle="modal" data-target="#hmw_securitydetail' . $index . '">' . __('Info', _HMW_PLUGIN_NAME_) . '</button>
                                                    ' . ((!$row['valid'] && isset($row['javascript']) && $row['javascript'] <> '') ? (isset($row['pro']) ? $row['pro'] : '<button class="btn btn-success mx-0" onclick="' . $row['javascript'] . '">' . __('Fix it', _HMW_PLUGIN_NAME_) . '</button>') : '') . '
                                                    <button type="button" class="close m-1" aria-label="Close" style="display: none" onclick="if (confirm(\'' . __('Are you sure you want to ignore this task in the future?') . '\')) {jQuery(this).hmw_securityExclude(\'' . $index . '\');}">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>                                             
                                                </td>
                                            </tr>';
                                }
                                echo '</tbody>';
                                echo '</table>';

                            }
                            ?>
                        </div>
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-default hmw_resetexclude" type="button"><?php echo __('Reset all ingnored tasks', _HMW_PLUGIN_NAME_) ?></button>
                        </div>
                    </div>
                    <?php if (!HMW_Classes_Tools::getOption('api_token') <> '') { ?>
                        <div class="col-sm-12 border-bottom border-light py-3 mx-0 my-3">
                            <div class="card col-sm-12 p-0 input-group">
                                <div class="card-body text-center">
                                    <a href="https://wpplugins.tips/wordpress-vulnerability-detector/?url=<?php echo urlencode(home_url()) ?>" target="_blank"><img src="<?php echo _HMW_THEME_URL_ . 'img/security_check.png' ?>" style="width: 100%"></a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
</div>