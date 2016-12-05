<?php
add_action('wp_ajax_mg_refinance', 'mg_refinance_fn');
add_action('wp_ajax_nopriv_mg_refinance', 'mg_refinance_fn');

function mg_refinance_fn() {
    if (!empty($_REQUEST['Email-Address']) || !empty($_REQUEST['id'])) {
        global $wpdb;
        $table = $wpdb->prefix . 'refinance_form_record';
        $email = $_REQUEST['Email-Address'];
        $name = $_REQUEST['Name'];
        $phone = $_REQUEST['Telephone'];
        $credit = $_REQUEST['Credit-Score'];
        $mode = $_REQUEST['mode'];
        $date_added = date('Y-m-d H:i:s');
        if ($mode == 'step-1') {
            $query = "SELECT * FROM " . $table . " where email = '" . $email . "'";
            $result = $wpdb->get_row($query);
            $query_field = array('email' => $email, 'name' => $name, 'phone' => $phone, 'credit' => $credit, 'incomplete_form' => '0', 'date_added' => $date_added);
            if (count($result) > 0) {
                $id = $result->id;
                $query = $wpdb->update($table, $query_field, array('id' => $result->id));
                $query = true;
            } else {
                $query = $wpdb->insert($table, $query_field);
                $id = $wpdb->insert_id;
            }
            if ($query) {
                global $to;
                $results = refiance_form_2_html_fn();
                $data = array();
                $data['id'] = $id;
                $data['result'] = $results;
                echo json_encode($data);
                $body = '<table border="1"><tbody>';
                $body .= '<tr><th>Name</th><td>' . $name . '</td></tr>';
                $body .= '<tr><th>Phone</th><td>' . $phone . '</td></tr>';
                $body .= '<tr><th>Email</th><td>' . $email . '</td></tr>';
                $body .= '<tr><th>Credit Score</th><td>' . $credit . '</td></tr>';
                $body .= '<tr><th>Date</th><td>' . $date_added . '</td></tr>';
                $body .= '</tbody></table>';
                $subject = 'Inquiry Recieved from Refinance Form';
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail( $to, $subject, $body, $headers );
                die;
            } else {
                echo 'error';
            }
        } else if ($mode == 'step-2') {
            $id = $_REQUEST['id'];
            $property_type = $_REQUEST['property'];
            $property_purpose = $_REQUEST['purpose'];
            $home_value = $_REQUEST['value_home'];
            $mortgage_balance = $_REQUEST['mortgage_balance'];
            $remain_balance = $_REQUEST['second_mortgage_section'];
            $extra_cash = $_REQUEST['extra_cash'];
            $query = $wpdb->update($table, array('property_type' => $property_type, 'property_purpose' => $property_purpose, 'home_value' => $home_value, 'mortgage_balance' => $mortgage_balance, 'remain_balance' => $remain_balance, 'extra_cash' => $extra_cash), array('id' => $id));
            $result = refiance_form_3_html_fn();
            $data = array();
            $data['id'] = $id;
            $data['result'] = $result;
            echo json_encode($data);
            die;
        } else if ($mode == 'step-3') {
            global $to;
            $id = $_REQUEST['id'];
            $selectDate = $_REQUEST['selectDate'];
            $selectMonth = $_REQUEST['selectMonth'];
            $selectYear = $_REQUEST['selectYear'];
            $birthdate = $selectYear . '-' . $selectMonth . '-' . $selectDate;
            $bankruptcy_forecloser = $_REQUEST['bankruptcy_forecloser_value'];
            $street_address = $_REQUEST['street_address'];
            $city = $_REQUEST['city'];
            $state = $_REQUEST['state'];
            $zipcode = $_REQUEST['zipcode'];
            $date_added = date('Y-m-d H:i:s');
            $query = $wpdb->update($table, array('incomplete_form' => '1', 'date_added' => $date_added, 'birthdate' => $birthdate, 'bankruptcy_forecloser' => $bankruptcy_forecloser, 'street_address' => $street_address, 'city' => $city, 'state' => $state, 'zipcode' => $zipcode), array('id' => $id));
            if ($query) {
                $results = refiance_form_html_fn();
                $data = array();
                $data['msg'] = 'Thank you for completing our form. We will contact you shortly with the best rate and any other questions you may have to get your loan processed quickly';
                $data['result'] = $results;
                echo json_encode($data);
                $query = "SELECT * FROM " . $table . " where id = '" . $id . "'";
                $result = $wpdb->get_row($query);
                $to_client = $result->email;
                $subject = 'Refinance Form';
                global $refinance_thankyou_msg;
                $body = $refinance_thankyou_msg;
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($to_client, $subject, $body, $headers);

                $body = '<table border="1"><tbody>';

                foreach ($result as $key => $value) {
                    if ($value && $key != 'incomplete_form') {
                        $body .= '<tr><th>' . ucwords(str_replace('_', ' ', $key)) . '</th><td>' . ucwords(str_replace('_', ' ', $value)) . '</td></tr>';
                    }
                }
                $body .= '</tbody></table>';
                $subject = 'Inquiry Recieved from Refinance Form';
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($to, $subject, $body, $headers);
                die;
            } else {
                echo 'error';
            }
        }

        die;
    }
}

add_action('wp_ajax_mg_mortgage', 'mg_mortgage_fn');
add_action('wp_ajax_nopriv_mg_mortgage', 'mg_mortgage_fn');

function mg_mortgage_fn() {
    if (!empty($_REQUEST['Email-Address']) || !empty($_REQUEST['id'])) {
        global $wpdb;
        $table = $wpdb->prefix . 'mortgage_form_record';
        $email = $_REQUEST['Email-Address'];
        $name = $_REQUEST['Name'];
        $phone = $_REQUEST['Telephone'];
        $credit = $_REQUEST['Credit-Score'];
        $mode = $_REQUEST['mode'];
        $date_added = date('Y-m-d H:i:s');
        if ($mode == 'step-1') {
            $query = "SELECT * FROM " . $table . " where email = '" . $email . "'";
            $result = $wpdb->get_row($query);
            $query_field = array('email' => $email, 'name' => $name, 'phone' => $phone, 'credit' => $credit, 'incomplete_form' => 0, 'date_added' => $date_added);
            if (count($result) > 0) {
                $id = $result->id;
                $wpdb->update($table, $query_field, array('id' => $id));
                $query = true;
            } else {
                $query = $wpdb->insert($table, $query_field);
                $id = $wpdb->insert_id;
            }

            if ($query) {
                global $to;
                $results = mortgage_form_2_html_fn();
                $data = array();
                $data['id'] = $id;
                $data['result'] = $results;
                $body = '<table border="1"><tbody>';
                $body .= '<tr><th>Name</th><td>' . $name . '</td></tr>';
                $body .= '<tr><th>Phone</th><td>' . $phone . '</td></tr>';
                $body .= '<tr><th>Email</th><td>' . $email . '</td></tr>';
                $body .= '<tr><th>Credit Score</th><td>' . $credit . '</td></tr>';
                $body .= '<tr><th>Date</th><td>' . $date_added . '</td></tr>';
                $body .= '</tbody></table>';
                $subject = 'Inquiry Recieved from Mortgage Form';
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail( $to, $subject, $body, $headers );
                echo json_encode($data);
                die;
            } else {
                echo 'error';
            }
        } else if ($mode == 'step-2') {
            $id = $_REQUEST['id'];
            $property_type = $_REQUEST['property'];
            $property_purpose = $_REQUEST['purpose'];
            $property_city = $_REQUEST['property_city'];
            $property_state = $_REQUEST['property_state'];
            $purchase_price = $_REQUEST['purchase_price'];
            $down_payment = $_REQUEST['down_payment'];
            $query = $wpdb->update($table, array('property_type' => $property_type, 'property_purpose' => $property_purpose, 'property_city' => $property_city, 'purchase_price' => $purchase_price, 'property_state' => $property_state, 'down_payment' => $down_payment), array('id' => $id));
            if ($query) {
                $result = mortgage_form_3_html_fn();
                $data = array();
                $data['id'] = $id;
                $data['result'] = $result;
                echo json_encode($data);
                die;
            } else {
                echo 'error';
            }
        } else if ($mode == 'step-3') {
            $id = $_REQUEST['id'];
            $bankruptcy_forecloser = $_REQUEST['bankruptcy_forecloser_value'];
            $street_address = $_REQUEST['street_address'];
            $city = $_REQUEST['city'];
            $state = $_REQUEST['state'];
            $zipcode = $_REQUEST['zipcode'];
            $annual_income = $_REQUEST['annual_income'];
            $selectDate = $_REQUEST['selectDate'];
            $selectMonth = $_REQUEST['selectMonth'];
            $selectYear = $_REQUEST['selectYear'];
            $birthdate = $selectYear . '-' . $selectMonth . '-' . $selectDate;
            $date_added = date('Y-m-d H:i:s');
            $query = $wpdb->update($table, array('incomplete_form' => '1', 'date_added' => $date_added, 'birthdate' => $birthdate, 'bankruptcy_forecloser' => $bankruptcy_forecloser, 'street_address' => $street_address, 'city' => $city, 'state' => $state, 'zipcode' => $zipcode, 'annual_income' => $annual_income), array('id' => $id));
            if ($query) {
                global $to;
                $result = refiance_form_html_fn();
                $data = array();
                $data['msg'] = 'Thank you for completing our form. We will contact you shortly with the best rate and any other questions you may have to get your loan processed quickly';
                $data['result'] = $result;
                echo json_encode($data);
                $table = $wpdb->prefix . 'mortgage_form_record';
                $query = "SELECT * FROM " . $table . " where id = '" . $id . "'";
                $result = $wpdb->get_row($query);
                $to_client = $result->email;
                $subject = 'Mortgage Form';
                global $mortgage_thankyou_msg;
                $body = $mortgage_thankyou_msg;
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($to_client, $subject, $body, $headers);

                $body = '<table border="1"><tbody>';

                foreach ($result as $key => $value) {
                    if ($value != '' && $key != 'incomplete_form') {
                        $body .= '<tr><th>' . ucwords(str_replace('_', ' ', $key)) . '</th><td>' . ucwords(str_replace('_', ' ', $value)) . '</td></tr>';
                    }
                }
                $body .= '</tbody></table>';
                $subject = 'Inquiry Recieved from Mortgage Form';
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($to, $subject, $body, $headers);
                die;
            } else {
                echo 'error';
            }
        }

        die;
    }
}

add_action('wp_ajax_mg_reverse', 'mg_reverse_fn');
add_action('wp_ajax_nopriv_mg_reverse', 'mg_reverse_fn');

function mg_reverse_fn() {
    if (!empty($_REQUEST['Email-Address']) || !empty($_REQUEST['id'])) {
        global $wpdb;
        $table = $wpdb->prefix . 'reverse_mortgage_form_record';
        $email = $_REQUEST['Email-Address'];
        $name = $_REQUEST['Name'];
        $phone = $_REQUEST['Telephone'];
        $credit = $_REQUEST['Credit-Score'];
        $mode = $_REQUEST['mode'];
        $date_added = date('Y-m-d H:i:s');
        if ($mode == 'step-1') {
            $query = "SELECT * FROM " . $table . " where email = '" . $email . "'";
            $result = $wpdb->get_row($query);
            $query_field = array('email' => $email, 'name' => $name, 'phone' => $phone, 'credit' => $credit, 'incomplete_form' => '0', 'date_added' => $date_added);
            if (count($result) > 0) {
                $id = $result->id;
                $query = $wpdb->update($table, $query_field, array('id' => $result->id));
                $query = true;
            } else {
                $query = $wpdb->insert($table, $query_field);
                $id = $wpdb->insert_id;
            }

            if ($query) {
                global $to;
                $results = reverse_mortgage_form_2_html_fn();
                $data = array();
                $data['id'] = $id;
                $data['result'] = $results;
                $body = '<table border="1"><tbody>';
                $body .= '<tr><th>Name</th><td>' . $name . '</td></tr>';
                $body .= '<tr><th>Phone</th><td>' . $phone . '</td></tr>';
                $body .= '<tr><th>Email</th><td>' . $email . '</td></tr>';
                $body .= '<tr><th>Credit Score</th><td>' . $credit . '</td></tr>';
                $body .= '<tr><th>Date</th><td>' . $date_added . '</td></tr>';
                $body .= '</tbody></table>';
                $subject = 'Inquiry Recieved from Reverse Mortgage Form';
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail( $to, $subject, $body, $headers );
                echo json_encode($data);
                die;
            } else {
                echo 'error';
            }
        } else if ($mode == 'step-2') {
            $id = $_REQUEST['id'];
            $property_type = $_REQUEST['property'];
            $property_purpose = $_REQUEST['purpose'];
            $home_value = $_REQUEST['value_home'];
            $mortgage_balance = $_REQUEST['mortgage_balance'];
            $query = $wpdb->update($table, array('property_type' => $property_type, 'property_purpose' => $property_purpose, 'home_value' => $home_value, 'mortgage_balance' => $mortgage_balance), array('id' => $id));
            $result = reverse_mortgage_form_3_html_fn();
            $data = array();
            $data['id'] = $id;
            $data['result'] = $result;
            echo json_encode($data);
            die;
        } else if ($mode == 'step-3') {
            $id = $_REQUEST['id'];
            $street_address = $_REQUEST['street_address'];
            $person_age = $_REQUEST['person_age'];
            $state = $_REQUEST['state'];
            $zipcode = $_REQUEST['zipcode'];
            $date_added = date('Y-m-d H:i:s');
            $query = $wpdb->update($table, array('incomplete_form' => '1', 'date_added' => $date_added, 'street_address' => $street_address, 'person_age' => $person_age, 'state' => $state, 'zipcode' => $zipcode), array('id' => $id));
            if ($query) {
                global $to;
                $results = refiance_form_html_fn();
                $data = array();
                $data['msg'] = 'Thank you for completing our form. We will contact you shortly with the best rate and any other questions you may have to get your loan processed quickly';
                $data['result'] = $results;
                echo json_encode($data);
                $query = "SELECT * FROM " . $table . " where id = '" . $id . "'";
                $result = $wpdb->get_row($query);
                $to_client = $result->email;
                $subject = 'Reverse Mortgage Form';
                global $rev_mortgage_thankyou_msg;
                $body = $rev_mortgage_thankyou_msg;
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($to_client, $subject, $body, $headers);

                $body = '<table border="1"><tbody>';

                foreach ($result as $key => $value) {
                    if ($value && $key != 'incomplete_form') {
                        $body .= '<tr><th>' . ucwords(str_replace('_', ' ', $key)) . '</th><td>' . ucwords(str_replace('_', ' ', $value)) . '</td></tr>';
                    }
                }
                $body .= '</tbody></table>';
                $subject = 'Inquiry Recieved from Reverse Mortgage';
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($to, $subject, $body, $headers);
                die;
            } else {
                echo 'error';
            }
        }

        die;
    }
}

add_action('wp_ajax_get_record', 'get_record_fn');

function get_record_fn() {
    $id = $_POST['id'];
    if ($id > 0) {
        global $wpdb;
        $table = $wpdb->prefix . 'refinance_form_record';
        $query = "SELECT * FROM " . $table . " where id = " . $id . "";
        $result = $wpdb->get_row($query);
        ?>
        <table class="widefat fixed striped dd_table_style" border="1">
            <tbody>
                <?php if ($result->incomplete_form) { ?>
                    <tr>
                        <th scope="row"><label>Name:</label></th>
                        <td><?php echo $result->name; ?></td>
                        <th scope="row"><label>Email:</label></th>
                        <td><?php echo $result->email; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Phone:</label></th>
                        <td><?php echo $result->phone; ?></td>
                        <th scope="row"><label>Credit Score:</label></th>
                        <td><?php echo $result->credit; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Property Type:</label></th>
                        <td><?php echo $result->property_type; ?></td>
                        <th scope="row"><label>Property Purpose:</label></th>
                        <td><?php echo $result->property_purpose; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Home Value:</label></th>
                        <td><?php echo $result->home_value; ?></td>
                        <th scope="row"><label>Mortgage Balance:</label></th>
                        <td><?php echo $result->mortgage_balance; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Remain Balance:</label></th>
                        <td><?php echo $result->remain_balance; ?></td>
                        <th scope="row"><label>Extra Cash:</label></th>
                        <td><?php echo $result->extra_cash; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Birth Date:</label></th>
                        <td><?php echo $result->birthdate; ?></td>
                        <th scope="row"><label>Bankruptcy Forecloser:</label></th>
                        <td><?php echo $result->bankruptcy_forecloser; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Street Address:</label></th>
                        <td><?php echo $result->street_address; ?></td>
                        <th scope="row"><label>City:</label></th>
                        <td><?php echo $result->city; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>State:</label></th>
                        <td><?php echo $result->state; ?></td>
                        <th scope="row"><label>Zip Code:</label></th>
                        <td><?php echo $result->zipcode; ?></td>
                    </tr>
                    <tr>
                        <?php
                        if ($result->incomplete_form) {
                            $form_status = "Complete";
                        } else {
                            $form_status = "In Complete";
                        }
                        ?>
                        <th scope="row"><label>Form Status:</label></th>
                        <td><?php echo $form_status; ?></td>
                        <th scope="row"><label>Date Added:</label></th>
                        <td><?php echo $result->date_added; ?></td>
                    </tr>

                <?php } else { ?>


                    <tr>
                        <th scope="row"><label>Name:</label></th>
                        <td><?php echo $result->name; ?></td>
                        <th scope="row"><label>Email:</label></th>
                        <td><?php echo $result->email; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Phone:</label></th>
                        <td><?php echo $result->phone; ?></td>
                        <th scope="row"><label>Credit Score:</label></th>
                        <td><?php echo $result->credit; ?></td>
                    </tr>
                    <tr>
                        <?php
                        if ($result->incomplete_form) {
                            $form_status = "Complete";
                        } else {
                            $form_status = "In Complete";
                        }
                        ?>
                        <th scope="row"><label>Form Status:</label></th>
                        <td><?php echo $form_status; ?></td>
                        <th scope="row"><label>Date Added:</label></th>
                        <td><?php echo $result->date_added; ?></td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
        <?php
    }
    die;
}

add_action('wp_ajax_get_record_mortgage', 'get_record_mortgage_fn');

function get_record_mortgage_fn() {
    $id = $_POST['id'];
    if ($id > 0) {
        global $wpdb;
        $table = $wpdb->prefix . 'mortgage_form_record';
        $query = "SELECT * FROM " . $table . " where id = " . $id . "";
        $result = $wpdb->get_row($query);
        ?>
        <table class="widefat fixed striped dd_table_style" border="1">
            <tbody>
                <?php if ($result->incomplete_form) { ?>
                    <tr>
                        <th scope="row"><label>Name:</label></th>
                        <td><?php echo $result->name; ?></td>
                        <th scope="row"><label>Email:</label></th>
                        <td><?php echo $result->email; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Phone:</label></th>
                        <td><?php echo $result->phone; ?></td>
                        <th scope="row"><label>Credit Score:</label></th>
                        <td><?php echo $result->credit; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Property Type:</label></th>
                        <td><?php echo $result->property_type; ?></td>
                        <th scope="row"><label>Property Purpuse:</label></th>
                        <td><?php echo $result->property_purpose; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Property City:</label></th>
                        <td><?php echo $result->property_city; ?></td>
                        <th scope="row"><label>Property State:</label></th>
                        <td><?php echo $result->property_state; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Purchase Price:</label></th>
                        <td><?php echo $result->purchase_price; ?></td>
                        <th scope="row"><label>Down Payment:</label></th>
                        <td><?php echo $result->down_payment; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Birth Date:</label></th>
                        <td><?php echo $result->birthdate; ?></td>
                        <th scope="row"><label>Bankruptcy Forecloser:</label></th>
                        <td><?php echo $result->bankruptcy_forecloser; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Street Address:</label></th>
                        <td><?php echo $result->street_address; ?></td>
                        <th scope="row"><label>City:</label></th>
                        <td><?php echo $result->city; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>State:</label></th>
                        <td><?php echo $result->state; ?></td>
                        <th scope="row"><label>Zip Code:</label></th>
                        <td><?php echo $result->zipcode; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Annual Income:</label></th>
                        <td><?php echo $result->annual_income; ?></td>
                        <?php
                        if ($result->incomplete_form) {
                            $form_status = "Complete";
                        } else {
                            $form_status = "In Complete";
                        }
                        ?>
                        <th scope="row"><label>Form Status:</label></th>
                        <td><?php echo $form_status; ?></td>
                    </tr>
                    <tr>
                        <th colspan="2" scope="row"><label>Date Added:</label></th>
                        <td colspan="2"><?php echo $result->date_added; ?></td>
                    </tr>

                <?php } else { ?>

                    <tr>
                        <th scope="row"><label>Name:</label></th>
                        <td><?php echo $result->name; ?></td>
                        <th scope="row"><label>Email:</label></th>
                        <td><?php echo $result->email; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Phone:</label></th>
                        <td><?php echo $result->phone; ?></td>
                        <th scope="row"><label>Credit Score:</label></th>
                        <td><?php echo $result->credit; ?></td>
                    </tr>
                    <tr>
                        <?php
                        if ($result->incomplete_form) {
                            $form_status = "Complete";
                        } else {
                            $form_status = "In Complete";
                        }
                        ?>
                        <th scope="row"><label>Form Status:</label></th>
                        <td><?php echo $form_status; ?></td>
                        <th scope="row"><label>Date Added:</label></th>
                        <td><?php echo $result->date_added; ?></td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
        <?php
    }
    die;
}

add_action('wp_ajax_get_record_reverse', 'get_record_reverse_fn');

function get_record_reverse_fn() {
    $id = $_POST['id'];
    if ($id > 0) {
        global $wpdb;
        $table = $wpdb->prefix . 'reverse_mortgage_form_record';
        $query = "SELECT * FROM " . $table . " where id = " . $id . "";
        $result = $wpdb->get_row($query);
        ?>
        <table class="widefat fixed striped dd_table_style" border="1">
            <tbody>
                <?php if ($result->incomplete_form) { ?>
                    <tr>
                        <th scope="row"><label>Name</label></th>
                        <td><?php echo $result->name; ?></td>
                        <th scope="row"><label>Email</label></th>
                        <td><?php echo $result->email; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Phone</label></th>
                        <td><?php echo $result->phone; ?></td>
                        <th scope="row"><label>Credit Score</label></th>
                        <td><?php echo $result->credit; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Property Type</label></th>
                        <td><?php echo $result->property_type; ?></td>
                        <th scope="row"><label>Property Purpose</label></th>
                        <td><?php echo $result->property_purpose; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Home Value</label></th>
                        <td><?php echo $result->home_value; ?></td>
                        <th scope="row"><label>Mortgage Balance</label></th>
                        <td><?php echo $result->mortgage_balance; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Street Address</label></th>
                        <td><?php echo $result->street_address; ?></td>
                        <th scope="row"><label>State</label></th>
                        <td><?php echo $result->state; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Zip Code</label></th>
                        <td><?php echo $result->zipcode; ?></td>
                        <th scope="row"><label>Person Age</label></th>
                        <td><?php echo $result->person_age; ?></td>
                    </tr>
                    <tr>
                        <?php
                        if ($result->incomplete_form) {
                            $form_status = "Complete";
                        } else {
                            $form_status = "Incomplete";
                        }
                        ?>
                        <th scope="row"><label>Form Status</label></th>
                        <td><?php echo $form_status; ?></td>
                        <th scope="row"><label>Date Added</label></th>
                        <td><?php echo $result->date_added; ?></td>
                    </tr>

                <?php } else { ?>

                    <tr>
                        <th scope="row"><label>Name</label></th>
                        <td><?php echo $result->name; ?></td>
                        <th scope="row"><label>Email</label></th>
                        <td><?php echo $result->email; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Phone</label></th>
                        <td><?php echo $result->phone; ?></td>
                        <th scope="row"><label>Credit Score</label></th>
                        <td><?php echo $result->credit; ?></td>
                    </tr>
                    <tr>
                        <?php
                        if ($result->incomplete_form) {
                            $form_status = "Complete";
                        } else {
                            $form_status = "Incomplete";
                        }
                        ?>
                        <th scope="row"><label>Form Status</label></th>
                        <td><?php echo $form_status; ?></td>
                        <th scope="row"><label>Date Added</label></th>
                        <td><?php echo $result->date_added; ?></td>
                    </tr>
                    
                <?php } ?>
            </tbody>
        </table>
        <?php
    }
    die;
}
